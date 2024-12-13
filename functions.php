<?php
// Ajouter la prise en charge des images mises en avant
add_theme_support('post-thumbnails');

function register_my_menus() {
    register_nav_menus(array(
        'main_menu' => __('Menu Principal')
    ));
}
add_action('init', 'register_my_menus');


// Charger le fichier CSS du thème
function load_theme_styles() {
    wp_enqueue_style('main-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'load_theme_styles');

function ajouter_style_portrait() {
    // Vérifiez si nous sommes sur un single 'portrait' (page individuelle)
    if (is_singular('portrait')) {
        // Enqueue le fichier CSS spécifique au type de contenu 'portrait'
        wp_enqueue_style('style-portrait', get_template_directory_uri() . '/css/single-portrait.css', array(), null);
    }
}
add_action('wp_enqueue_scripts', 'ajouter_style_actualite');

function ajouter_style_actualite() {
    // Vérifiez si nous sommes sur un single 'actualite' (page individuelle)
    if (is_singular('actualite')) {
        // Enqueue le fichier CSS spécifique au type de contenu 'actualite'
        wp_enqueue_style('style-actualite', get_template_directory_uri() . '/css/single-actualite.css', array(), null);
    }
}
add_action('wp_enqueue_scripts', 'ajouter_style_actualite');

function enqueue_archive_portrait_styles() {
    if (is_archive() && get_post_type() === 'portrait') {
        wp_enqueue_style('archive-portrait-style', get_template_directory_uri() . '/css/archive-portrait.css', array(), null);
    }
}
add_action('wp_enqueue_scripts', 'enqueue_archive_portrait_styles');

function enqueue_archive_actualite_styles() {
    if (is_archive() && get_post_type() === 'actualite') {
        wp_enqueue_style('archive-actualite-style', get_template_directory_uri() . '/css/archive-actualite.css', array(), null);
    }
}
add_action('wp_enqueue_scripts', 'enqueue_archive_actualite_styles');


function custom_search_and_filter_results( $query ) {
    if ( !is_admin() && $query->is_main_query() ) {
        // Vérifier si Search & Filter a envoyé des paramètres de filtre via URL
        if ( isset( $_GET['metier'] ) && !empty( $_GET['metier'] ) ) {
            $query->set( 'tax_query', array(
                array(
                    'taxonomy' => 'metier', // Taxonomie "metier"
                    'field'    => 'slug',
                    'terms'    => $_GET['metier'], // Récupère la valeur du paramètre metier
                    'operator' => 'IN',
                ),
            ));
        }

        if ( isset( $_GET['promotion'] ) && !empty( $_GET['promotion'] ) ) {
            $query->set( 'tax_query', array(
                array(
                    'taxonomy' => 'promotion', // Taxonomie "promotion"
                    'field'    => 'slug',
                    'terms'    => $_GET['promotion'], // Récupère la valeur du paramètre promotion
                    'operator' => 'IN',
                ),
            ));
        }
    }
}
add_action( 'pre_get_posts', 'custom_search_and_filter_results' );

function enqueue_leaflet_map() {
    // Ajouter le CSS de Leaflet
    wp_enqueue_style('leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', [], null);

    // Ajouter le JS de Leaflet
    wp_enqueue_script('leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', [], null, true);

}
add_action('wp_enqueue_scripts', 'enqueue_leaflet_map');


function enqueue_leaflet_assets() {
    // Ajouter le CSS de Leaflet
    wp_enqueue_style('leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', [], null);

    // Ajouter le JS de Leaflet
    wp_enqueue_script('leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', [], null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_leaflet_assets');

// Fonction pour récupérer les coordonnées via OpenCage API
function get_coordinates_from_city($city) {
    $api_key = 'b272ec5a5c3f4ed497131503f8e4fc8f'; // Remplacez par votre clé API OpenCage
    $url = 'https://api.opencagedata.com/geocode/v1/json?q=' . urlencode($city) . '&key=' . $api_key;

    $response = wp_remote_get($url);
    
    if (is_wp_error($response)) {
        return null;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if (isset($data['results'][0]['geometry'])) {
        return [
            'lat' => $data['results'][0]['geometry']['lat'],
            'lng' => $data['results'][0]['geometry']['lng'],
        ];
    }

    return null; // Retourne null si aucune donnée n'est trouvée
}

// Enregistrer les coordonnées à la sauvegarde du portrait
function save_coordinates_for_portrait($post_id) {
    if (get_post_type($post_id) !== 'portrait') {
        return;
    }

    $ville_entreprise = get_post_meta($post_id, 'ville_entreprise', true); // Récupérer le lieu de l'entreprise (ville)

    if ($ville_entreprise) {
        $coordinates = get_coordinates_from_city($ville_entreprise); // Récupérer les coordonnées via OpenCage API
        if ($coordinates) {
            update_post_meta($post_id, '_latitude', $coordinates['lat']);
            update_post_meta($post_id, '_longitude', $coordinates['lng']);
        }
    }
}
add_action('save_post', 'save_coordinates_for_portrait');

// Assurez-vous que les portraits restent publiés après modification
add_action('acf/save_post', function($post_id) {
    if (get_post_type($post_id) === 'portrait') {
        // Forcer le statut du post à 'publish'
        $post_status = get_post_status($post_id);
        if ($post_status !== 'publish') {
            wp_update_post(array(
                'ID' => $post_id,
                'post_status' => 'publish'
            ));
        }

        // Associer l'utilisateur connecté au portrait
        $user_id = get_current_user_id();
        update_post_meta($post_id, 'attributed_user', $user_id);
    }
}, 20);

// S'assurer que chaque utilisateur ne peut avoir qu'un portrait


function set_attributed_user( $post_id ) {
    // Vérifiez que nous sommes dans le bon post_type
    if (get_post_type($post_id) !== 'portrait') {
        return;
    }

    // Vérifiez que ce n'est pas une sauvegarde automatique
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Vérifiez qu'un utilisateur est connecté
    if (is_user_logged_in()) {
        $current_user_id = get_current_user_id();

        // Mettre à jour le champ attributed_user avec l'ID de l'utilisateur connecté
        update_field('attributed_user', $current_user_id, $post_id); // Remplacez 'attributed_user' par le nom exact de votre champ ACF
    }
}
add_action('acf/save_post', 'set_attributed_user', 10);



function set_portrait_title( $post_id ) {
    // Vérifiez que nous sommes dans le bon post_type
    if (get_post_type($post_id) !== 'portrait') {
        return;
    }

    // Vérifiez que ce n'est pas une sauvegarde automatique
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Récupérer les champs Prénom et Nom
    $prenom = get_field('prenom', $post_id); // Remplacez 'prenom' par le nom exact de votre champ ACF
    $nom = get_field('nom', $post_id); // Remplacez 'nom' par le nom exact de votre champ ACF

    // Vérifiez que les deux champs existent
    if ($prenom && $nom) {
        // Mettre à jour le titre du post
        $new_title = $prenom . ' ' . $nom;
        $new_slug = sanitize_title($new_title);

        // Mettre à jour le post
        wp_update_post(array(
            'ID'         => $post_id,
            'post_title' => $new_title,
            'post_name'  => $new_slug, // Met à jour le slug
        ));
    }
}
add_action('acf/save_post', 'set_portrait_title', 20); // Priorité 20 pour s'assurer que les champs sont sauvegardés avant




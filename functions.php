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

    // Ajouter un script personnalisé
    wp_enqueue_script('custom-map-js', get_template_directory_uri() . '/js/custom-map.js', ['leaflet-js'], null, true);
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




















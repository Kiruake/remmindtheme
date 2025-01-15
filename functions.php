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

function enqueue_homepage_styles() {
    if (is_front_page()) {
        wp_enqueue_style('homepage-style', get_template_directory_uri() . '/css/page-accueil.css', array(), null);
    }
}

add_action('wp_enqueue_scripts', 'enqueue_homepage_styles');


function theme_enqueue_styles() {
    wp_enqueue_style( 'theme-style', get_stylesheet_uri() );
    
    // Ajoutez un fichier CSS personnalisé si nécessaire
    wp_enqueue_style('custom-fonts', get_template_directory_uri() . '/css/font.css', array(), null);

}

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );


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



// Dans votre functions.php, ajoutez ces lignes pour inclure Slick Carousel
function ajouter_slick_carousel() {
    // Ajouter le CSS de Slick
    wp_enqueue_style('slick-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css');
    wp_enqueue_style('slick-theme', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css');
    
    // Ajouter le JS de Slick
    wp_enqueue_script('slick-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'ajouter_slick_carousel');

// Ajouter des champs dans le formulaire d'ajout de la taxonomie
// Ajouter des champs personnalisés à la création de la taxonomie
function edit_metier_custom_fields($term) {
    // Récupérer les valeurs existantes
    $niveau = get_term_meta($term->term_id, 'niveau', true);
    $lien_rome = get_term_meta($term->term_id, 'lien_rome', true);
    $image_url = get_term_meta($term->term_id, 'metier_image', true);
    $titre1 = get_term_meta($term->term_id, 'titre1', true);
    $paragraphe1 = get_term_meta($term->term_id, 'paragraphe1', true);
    $titre2 = get_term_meta($term->term_id, 'titre2', true);
    $paragraphe2 = get_term_meta($term->term_id, 'paragraphe2', true);
    ?>

    <tr class="form-field">
        <th scope="row" valign="top"><label for="titre1">Niveau</label></th>
        <td>
            <input type="number" name="niveau" id="niveau" value="<?php echo esc_attr($niveau); ?>">
            <p class="description">Entrez le niveau de BAC.</p>
        </td>
    </tr>

    <tr class="form-field">
        <th scope="row" valign="top"><label for="lien_rome">Lien fiche ROME</label></th>
        <td>
            <input type="text" name="lien_rome" id="lien_rome" value="<?php echo esc_url($lien_rome); ?>">
            <p class="description">Entrez le lien vers la fiche ROME.</p>
        </td>
    </tr>

    <tr class="form-field">
        <th scope="row" valign="top"><label for="metier_image">Image</label></th>
        <td>
            <input type="text" name="metier_image" id="metier_image" value="<?php echo esc_url($image_url); ?>" class="image-url">
            <button type="button" class="upload-image-button button">Uploader une image</button>
            <p class="description">Ajoutez une image représentative pour cette taxonomie.</p>
        </td>
    </tr>

    <tr class="form-field">
        <th scope="row" valign="top"><label for="titre1">Titre 1</label></th>
        <td>
            <input type="text" name="titre1" id="titre1" value="<?php echo esc_attr($titre1); ?>">
            <p class="description">Entrez le premier titre pour cette taxonomie.</p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="paragraphe1">Paragraphe 1</label></th>
        <td>
            <textarea name="paragraphe1" id="paragraphe1" rows="5"><?php echo esc_textarea($paragraphe1); ?></textarea>
            <p class="description">Entrez le premier paragraphe pour cette taxonomie.</p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="titre2">Titre 2</label></th>
        <td>
            <input type="text" name="titre2" id="titre2" value="<?php echo esc_attr($titre2); ?>">
            <p class="description">Entrez le second titre pour cette taxonomie.</p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="paragraphe2">Paragraphe 2</label></th>
        <td>
            <textarea name="paragraphe2" id="paragraphe2" rows="5"><?php echo esc_textarea($paragraphe2); ?></textarea>
            <p class="description">Entrez le second paragraphe pour cette taxonomie.</p>
        </td>
    </tr>
    <?php
}
add_action('metier_edit_form_fields', 'edit_metier_custom_fields');


function save_metier_custom_fields($term_id) {

    if (isset($_POST['niveau'])) {
        update_term_meta($term_id, 'niveau', sanitize_text_field($_POST['niveau']));
    }

    if (isset($_POST['lien_rome'])) {
        update_term_meta($term_id, 'lien_rome', sanitize_text_field($_POST['lien_rome']));
    }

    if (isset($_POST['metier_image'])) {
        update_term_meta($term_id, 'metier_image', intval($_POST['metier_image']));
    }
    
    if (isset($_POST['titre1'])) {
        update_term_meta($term_id, 'titre1', sanitize_text_field($_POST['titre1']));
    }
    if (isset($_POST['paragraphe1'])) {
        update_term_meta($term_id, 'paragraphe1', sanitize_textarea_field($_POST['paragraphe1']));
    }
    if (isset($_POST['titre2'])) {
        update_term_meta($term_id, 'titre2', sanitize_text_field($_POST['titre2']));
    }
    if (isset($_POST['paragraphe2'])) {
        update_term_meta($term_id, 'paragraphe2', sanitize_textarea_field($_POST['paragraphe2']));
    }
}
add_action('created_metier', 'save_metier_custom_fields');
add_action('edited_metier', 'save_metier_custom_fields');

function enqueue_media_uploader_script($hook) {
    if (in_array($hook, ['edit-tags.php', 'term.php'])) {
        wp_enqueue_media();
        wp_enqueue_script('custom-taxonomy-media-uploader', get_template_directory_uri() . '/js/taxonomy-media-uploader.js', ['jquery'], null, true);
    }
}
add_action('admin_enqueue_scripts', 'enqueue_media_uploader_script');


add_action('user_register', 'set_user_pending_status');
function set_user_pending_status($user_id) {
    update_user_meta($user_id, 'account_status', 'pending');
}

add_filter('authenticate', 'block_unapproved_users', 30, 3);
function block_unapproved_users($user, $username, $password) {
    if (!is_wp_error($user)) {
        $account_status = get_user_meta($user->ID, 'account_status', true);
        if ($account_status === 'pending') {
            return new WP_Error('pending_approval', __('Votre compte est en attente de validation.'));
        }
    }
    return $user;
}

function approve_user($user_id) {
    update_user_meta($user_id, 'account_status', 'approved');
}



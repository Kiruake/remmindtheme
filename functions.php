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













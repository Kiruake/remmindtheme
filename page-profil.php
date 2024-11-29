<?php

 /*
Template Name: Profils
*/

if (is_user_logged_in()) {
    $current_user = wp_get_current_user();
    echo '<h2>Bienvenue, ' . esc_html($current_user->display_name) . '!</h2>';
    echo '<a href="' . esc_url(get_edit_user_link()) . '">Modifier mon profil</a>';
    echo '<a href="' . esc_url(wp_logout_url(home_url())) . '">Se déconnecter</a>';
} else {
    wp_redirect(site_url('/login'));
    exit;
}
?>

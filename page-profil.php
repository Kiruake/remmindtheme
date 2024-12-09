<?php
// Charger ACF
acf_form_head();
get_header();
?>

<div class="profile-container">
    <?php if (is_user_logged_in()) : 
        
        echo '<a href="' . wp_logout_url() . '"><p>Se déconnecter</p></a>';
        ?>
        <?php
        // Récupérer l'ID de l'utilisateur connecté
        $user_id = get_current_user_id();

        // Vérifier si un portrait existe pour cet utilisateur
        $existing_portrait = get_posts([
            'post_type'  => 'portrait',
            'meta_key'   => 'attributed_user',
            'meta_value' => $user_id,
            'posts_per_page' => 1,
        ]);

        if ($existing_portrait) :
            $portrait_id = $existing_portrait[0]->ID;

            // Formulaire pour modifier le portrait existant
            echo '<h2>Modifier votre portrait</h2>';

            acf_form(array(
                'post_id'       => $portrait_id,
                'field_groups'  => array('group_672deab18d338'), // ID du groupe ACF
                'fields'        => array('prenom', 'nom', 'age', 'lien_linkedin', 'lien_portfolio', 'ville_entreprise', 'nom_entreprise', 'photo_profil', 'presentation', 'competence', 'metier', 'promotion'), // Remplacez par les noms des champs à afficher
                'submit_value'  => 'Mettre à jour mon portrait',
                'updated_message' => 'Votre portrait a été mis à jour.',
            ));
            
        else :
            // Formulaire pour créer un nouveau portrait
            echo '<h2>Créer votre portrait</h2>';

            acf_form(array(
                'post_id'       => 'new_post',
                'new_post'      => array(
                    'post_type'   => 'portrait',
                    'post_status' => 'publish',
                ),
                'field_groups'  => array('group_672deab18d338'), // ID du groupe ACF
                'fields'        => array('prenom', 'nom', 'age', 'lien_linkedin', 'lien_portfolio', 'ville_entreprise', 'nom_entreprise', 'photo_profil', 'presentation', 'competence', 'metier', 'promotion'), // Remplacez par les noms des champs à afficher
                'submit_value'  => 'Créer mon portrait',
                'updated_message' => 'Votre portrait a été créé.',
            ));            
        endif;
        ?>
    <?php else : ?>
        <p>Veuillez vous connecter pour gérer votre portrait.</p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>

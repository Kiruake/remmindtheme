 <?php
// Charger ACF
acf_form_head();
get_header();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


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

<style>

/* Styles de base */
body {
    line-height: 1.6;
    font-family: 'Montserrat', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f9f9f9;
    color: #333;
}

h2 {
    color: #444;
    font-size: 1.8em;
    margin-bottom: 1rem;
    font-family: 'Aristotelica Display Trial', sans-serif;
}

p {
    font-size: 1rem;
    margin: 0.5rem 0;
}

a {
    text-decoration: none;
    color: #007BFF;
    transition: color 0.3s ease;
}

a:hover {
    color: #0056b3;
}

/* Conteneur principal */
.profile-container {
    max-width: 80%;
    margin: 2rem auto;
    padding: 1.5rem;
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Bouton déconnexion */
.profile-container a p {
    display: inline-block;
    padding: 0.5rem 1rem;
    background-color: #ff4d4f;
    color: white;
    border-radius: 5px;
    margin-bottom: 1rem;
    font-size: 0.9rem;
    text-align: center;
    transition: background-color 0.3s ease;
}

.profile-container a p:hover {
    background-color: #e04345;
}

/* Formulaire ACF */
.acf-form {
    margin-top: 1rem;
}

.acf-form .acf-field {
    margin-bottom: 1.5rem;
}

.acf-form .acf-field label {
    font-weight: bold;
    display: block;
    margin-bottom: 0.5rem;
}

.acf-form .acf-field input,
.acf-form .acf-field textarea,
.acf-form .acf-field select {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
    background-color: #f9f9f9;
    transition: border 0.3s ease;
}

/* Alignement spécifique pour métier, compétences, et promo */
.acf-field[data-name="metier"],
.acf-field[data-name="competence"],
.acf-field[data-name="promotion"] {
    display: flex;
    flex-direction: column;
}

.acf-field[data-name="metier"] label,
.acf-field[data-name="competence"] label,
.acf-field[data-name="promotion"] label {
    margin-bottom: 0.3rem;
    font-size: 1rem;
}

.acf-field[data-name="metier"] input,
.acf-field[data-name="competence"] input,
.acf-field[data-name="promotion"] input {
    padding: 0.6rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.9rem;
    width: 100%;
}

.acf-form .acf-field label[for="metier"],
.acf-form .acf-field label[for="competence"],
.acf-form .acf-field label[for="promotion"] {
        font-size: 0.9rem;
        display: flex;
        flex-direction: row-reverse;
        align-items: center;
    }

.acf-form .acf-field input:focus,
.acf-form .acf-field textarea:focus,
.acf-form .acf-field select:focus {
    border-color: #007BFF;
    outline: none;
    background-color: #ffffff;
}

.acf-button {
    padding: 0.8rem 1.5rem;
    background-color: #FFCC00;;
    color: black;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease;
    margin: auto;
    display: flex;
}

.acf-button:hover {
    background-color:rgb(255, 216, 59);;
}

ul.acf-radio-list li input[type=checkbox], ul.acf-radio-list li input[type=radio], ul.acf-checkbox-list li input[type=checkbox], ul.acf-checkbox-list li input[type=radio] {
    margin: -1px 4px 0 0;
    vertical-align: middle;
    position: absolute;
}

/* Responsivité */
@media (max-width: 768px) {
    .profile-container {
        padding: 1rem;
    }

    h2 {
        font-size: 1.5em;
    }

    .acf-form .acf-field label {
        font-size: 0.9rem;
    }
}

</style>
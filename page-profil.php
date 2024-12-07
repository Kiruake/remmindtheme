<a href="<?php echo wp_logout_url(); ?>"><p>Se deconnecter</p></a>
<?php
// Assurez-vous que l'utilisateur est connecté
if (is_user_logged_in()) {
    $user_id = get_current_user_id();

    // Vérifier si un portrait existe déjà pour cet utilisateur
    $existing_portrait = get_posts([
        'post_type' => 'portrait',
        'meta_key' => 'attributed_user', // Supposons que tu as un champ personnalisé pour lier le portrait à l'utilisateur
        'meta_value' => $user_id
    ]);

    if ($existing_portrait) {
        echo '<p>Vous avez déjà un portrait. Vous pouvez le modifier si nécessaire.</p>';
    } else {
        ?>


        <form action="" method="post" enctype="multipart/form-data">
            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" required>

            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required>

            <label for="age">Âge :</label>
            <input type="number" id="age" name="age" required>

            <label for="ville_entreprise">Ville :</label>
            <input type="text" id="age" name="ville_entreprise" required>

            <label for="nom_entreprise">Nom de l'entreprise :</label>
            <input type="text" id="nom_entreprise" name="nom_entreprise" required>

            <label for="photo_profil">Photo de Profil :</label>
            <input type="file" id="photo_profil" name="photo_profil" accept="image/*">

            <label for="presentation">Présentation :</label>
            <textarea id="presentation" name="presentation"></textarea>

            <label for="competences">Compétences :</label>
            <?php
            $competences = get_terms(['taxonomy' => 'competence', 'orderby' => 'name']);
            if ($competences) {
                foreach ($competences as $competence) {
                    echo '<input type="checkbox" name="competences[]" value="' . esc_attr($competence->slug) . '">' . esc_html($competence->name) . '<br>';
                }
            }
            ?>

            <label for="metier">Métier :</label>
            <?php
            $metiers = get_terms(['taxonomy' => 'metier', 'orderby' => 'name']);
            if ($metiers) {
                foreach ($metiers as $metier) {
                    echo '<input type="radio" name="metier" value="' . esc_attr($metier->slug) . '">' . esc_html($metier->name) . '<br>';
                }
            }
            ?>

            <label for="promotion">Promotion :</label>
            <?php
            $promotions = get_terms(['taxonomy' => 'promotion', 'orderby' => 'name']);
            if ($promotions) {
                foreach ($promotions as $promotion) {
                    echo '<input type="checkbox" name="promotion[]" value="' . esc_attr($promotion->slug) . '">' . esc_html($promotion->name) . '<br>';
                }
            }
            ?>

            <button type="submit" name="submit_portrait">Créer mon portrait</button>
        </form>

        <?php
        // Si le formulaire est soumis
        if (isset($_POST['submit_portrait'])) {
            $prenom = sanitize_text_field($_POST['prenom']);
            $nom = sanitize_text_field($_POST['nom']);
            $ville_entreprise = sanitize_text_field($_POST['ville_entreprise']);
            $nom_entreprise = sanitize_text_field($_POST['nom_entreprise']);
            $age = intval($_POST['age']);
            $photo_profil = $_FILES['photo_profil'];
            $presentation = sanitize_textarea_field($_POST['presentation']);
            $competences = isset($_POST['competences']) ? $_POST['competences'] : [];
            $metier = isset($_POST['metier']) ? $_POST['metier'] : '';
            $promotion = isset($_POST['promotion']) ? $_POST['promotion'] : [];

            // Créer un nouveau post 'portrait'
            $post_id = wp_insert_post([
                'post_type' => 'portrait',
                'post_status' => 'publish',
                'post_title' => $prenom . ' ' . $nom,
                'meta_input' => [
                    'attributed_user' => $user_id, // Lier le portrait à l'utilisateur
                    'prenom' => $prenom,
                    'nom' => $nom,
                    'age' => $age,
                    'ville_entreprise' => $ville_entreprise,
                    'nom_entreprise' => $nom_entreprise,
                    'presentation' => $presentation
                ]
            ]);

            // Ajouter la photo de profil
            if ($photo_profil && !empty($photo_profil['tmp_name'])) {
                $attachment_id = media_handle_upload('photo_profil', $post_id);
                if (!is_wp_error($attachment_id)) {
                    update_post_meta($post_id, 'photo_profil', $attachment_id);
                }
            }

            // Associer les taxonomies (compétences, métier, promotion)
            if ($competences) {
                wp_set_object_terms($post_id, $competences, 'competence');
            }
            if ($metier) {
                wp_set_object_terms($post_id, $metier, 'metier');
            }
            if ($promotion) {
                wp_set_object_terms($post_id, $promotion, 'promotion');
            }

            // Rediriger vers la page du portrait
            wp_redirect(get_permalink($post_id));
            exit;
        }
    }
} else {
    echo '<p>Vous devez être connecté pour créer un portrait.</p>';
}
?>

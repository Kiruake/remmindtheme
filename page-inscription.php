<?php
/*
Template Name: Inscription
*/

get_header();

// Vérifier si l'utilisateur est déjà connecté
if (is_user_logged_in()) {
    wp_redirect(site_url('/mon-profil'));
    exit;
}

// Formulaire d'inscription personnalisé
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = sanitize_text_field($_POST['password']);

    // Vérifier que tous les champs sont remplis
    if (!empty($username) && !empty($email) && !empty($password)) {
        $user_id = wp_create_user($username, $password, $email);
        if (!is_wp_error($user_id)) {
            wp_redirect(site_url('/login?register=success'));
            exit;
        } else {
            echo '<p class="error-message">' . $user_id->get_error_message() . '</p>';
        }
    } else {
        echo '<p class="error-message">Veuillez remplir tous les champs.</p>';
    }
}
?>

<div class="container register-page">
    <!-- Section Image -->
    <div class="image-section">
        <img src="<?php echo get_template_directory_uri(); ?>/images/TemplateRemmind.png" alt="Image d'inscription">
    </div>

    <!-- Section Formulaire -->
    <div class="form-section">
        <h1>Inscription</h1>
        <p class="info-text">Si vous êtes étudiant à l’Université, vous pouvez vous inscrire avec votre adresse mail universitaire.</p>

        <?php if (isset($error_message)) : ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="post" class="register-form">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" name="username" required>

            <label for="email">Email</label>
            <input type="email" name="email" required>

            <label for="password">Mot de passe</label>
            <input type="password" name="password" required>

            <button type="submit">S'inscrire</button>
        </form>

        <!-- Lien pour se connecter si l'utilisateur a déjà un compte -->
        <p class="has-account">Déjà un compte ? <a href="<?php echo site_url('/login'); ?>">Se connecter</a></p>
    </div>
</div>

<style>

body {
    margin: 0;
    font-family: 'Montserrat', sans-serif;
    background-color: #f9f9f9;
}

.container.register-page {
    display: flex;
    height: 100vh; /* Hauteur complète de l'écran */
    flex-direction: row-reverse;
}

.register-page .image-section {
    flex-basis: 50%; /* Prend 50 % de la largeur */
    display: flex; /* Centrage de l'image */
    justify-content: center;
    align-items: center;
    overflow: hidden; /* Empêche les débordements */
}

.register-page .image-section img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Adapte l'image à la taille sans déformation */
}

.register-page .form-section {
    flex-basis: 50%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: #ffffff;
    padding: 2rem;
    box-shadow: -2px 0 8px rgba(0, 0, 0, 0.1);
}

.register-page h1 {
    font-size: 2.5rem;
    color: #333333;
    margin-bottom: 20px;
}

.register-page .info-text {
    font-size: 0.9rem;
    color: #555555;
    margin-bottom: 2rem;
    text-align: center;
    max-width: 400px;
}

.register-page .register-form {
    width: 100%;
    max-width: 400px;
    display: flex;
    flex-direction: column;
}

.register-form label {
    margin-bottom: 0.5rem;
    font-size: 1rem;
    color: #555555;
}

.register-form input {
    margin-bottom: 1rem;
    padding: 0.75rem;
    font-size: 1rem;
    border: 1px solid #cccccc;
    border-radius: 4px;
    width: 100%;
}

.register-form button {
    padding: 0.75rem;
    font-size: 1rem;
    color: #ffffff;
    background-color: #002C40;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 1rem;
}

.register-form button:hover {
    background-color: #005bb5;
}

.has-account {
    margin-top: 1.5rem;
    font-size: 0.9rem;
    color: #555555;
}

.has-account a {
    color: #002C40;
    text-decoration: none;
}

.has-account a:hover {
    text-decoration: underline;
}

.error-message {
    color: #ff0000;
    margin-bottom: 1rem;
    font-size: 0.9rem;
}

</style>

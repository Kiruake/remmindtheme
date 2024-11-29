<?php
/*
Template Name: Connexion
*/
get_header();

// Rediriger l'utilisateur s'il est déjà connecté
if (is_user_logged_in()) {
    wp_redirect(site_url('/Profils'));
    exit;
}

// Vérification des informations d'identification lors de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize_user($_POST['username']);
    $password = sanitize_text_field($_POST['password']);

    $credentials = array(
        'user_login'    => $username,
        'user_password' => $password,
        'remember'      => isset($_POST['remember']),
    );

    $user = wp_signon($credentials, false);

    if (is_wp_error($user)) {
        $error_message = 'Identifiant ou mot de passe incorrect.';
    } else {
        wp_redirect(site_url('/Profils'));
        exit;
    }
}
?>

<!-- Formulaire de connexion personnalisé -->
<div class="container connexion-page">
    <!-- Section Image -->
    <div class="image-section">
        <img src="<?php echo get_template_directory_uri(); ?>/images/ImageLogin.png" alt="Image de connexion">
    </div>

    <!-- Section Formulaire -->
    <div class="form-section">
        <h1>Connexion</h1>
        
        <?php if (isset($error_message)) : ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>

            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Se souvenir de moi</label>
            </div>

            <button type="submit">Se connecter</button>
        </form>

        <!-- Lien vers la page d'inscription -->
        <p class="no-account">Pas encore de compte ? <a href="<?php echo site_url('/register'); ?>">S'inscrire</a></p>
    </div>
</div>


<style>

body {
    margin: 0;
    font-family: 'Montserrat', sans-serif;
    background-color: #f9f9f9;
}

.container.connexion-page {
    display: flex;
    flex-direction: row-reverse;
    height: 100vh; /* Hauteur complète de l'écran */
}

.connexion-page .image-section {
    flex-basis: 50%; /* Prend 50 % de la largeur */
    display: flex; /* Permet de centrer l'image */
    justify-content: center;
    align-items: center;
    overflow: hidden; /* Empêche les débordements */
}

.connexion-page .image-section img {
    width: 100%; /* S'étend sur la largeur du conteneur */
    height: 100%; /* S'étend sur la hauteur du conteneur */
    object-fit: cover; /* Adapte l'image à la taille sans déformer */
}

.connexion-page .form-section {
    flex-basis: 50%; /* Prend 50 % de la largeur */
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: #ffffff;
    padding: 2rem;
    box-shadow: -2px 0 8px rgba(0, 0, 0, 0.1);
}

.connexion-page h1 {
    font-size: 2.5rem;
    color: #333333;
    margin-bottom: 80px;
}

.connexion-page .login-form {
    width: 100%;
    max-width: 400px;
    display: flex;
    flex-direction: column;
}

.login-form label {
    margin-bottom: 0.5rem;
    font-size: 1rem;
    color: #555555;
}

.login-form input {
    margin-bottom: 1rem;
    padding: 0.75rem;
    font-size: 1rem;
    border: 1px solid #cccccc;
    border-radius: 4px;
    width: 100%;
}

.login-form #remember {
    margin-bottom: 1rem;
    padding: 0.75rem;
    font-size: 1rem;
    border: 1px solid #cccccc;
    border-radius: 4px;
    width: auto;
}

.login-form button {
    padding: 0.75rem;
    font-size: 1rem;
    color: #ffffff;
    background-color: #002C40;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 1rem;
}

.login-form button:hover {
    background-color: #005bb5;
}

.remember-me {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

.remember-me input {
    margin-right: 0.5rem;
}

.no-account {
    margin-top: 1.5rem;
    font-size: 0.9rem;
    color: #555555;
}

.no-account a {
    color: #002C40;
    text-decoration: none;
}

.no-account a:hover {
    text-decoration: underline;
}

.error-message {
    color: #ff0000;
    margin-bottom: 1rem;
    font-size: 0.9rem;
}


</style>

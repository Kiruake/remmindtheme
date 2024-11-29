<?php wp_head(); ?>
<header class="site-header">
    <div class="header-menu-container">
        <!-- Afficher le menu principal -->
        <?php wp_nav_menu(array(
            'theme_location' => 'main-menu',
            'container' => 'nav',
            'container_class' => 'main-menu',
            'menu_class' => 'menu',
        )); ?>

        <?php
        // Vérifier si l'utilisateur est connecté
        if (is_user_logged_in()) {
            ?>
            <nav class="user-menu">
                <ul>
                    <li><a href="<?php echo site_url('/Profils'); ?>">Mon Profil</a></li>
                </ul>
            </nav>
            <?php
        }
        ?>
    </div>
</header>

<style>

/* Style global pour le header */
.header-menu-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #002C40;
    height: 60px;
}

/* Menu principal */
.main-menu {
    display: flex;
    justify-content: flex-start; /* Aligner à gauche le menu principal */
    align-items: center;
}

.main-menu ul {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
}

.main-menu ul li {
    margin-left: 20px; /* Espacement entre les items */
}

.main-menu ul li a {
    color: white;
    text-decoration: none;
    font-size: 18px;
    padding: 0px 12px;
    transition: all 0.3s ease;
    height: 60px;
    display: flex;
    align-items: center;
}

.main-menu ul li a:hover {
    background-color: white;
    color: #002C40; /* Bleu pour le texte */
}

/* Menu utilisateur (Mon Profil) */
.user-menu {
    display: flex;
    justify-content: flex-end; /* Aligner à droite le menu utilisateur */
    align-items: center;
}

.user-menu ul {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
}

.user-menu ul li {
    margin-left: 20px; /* Espacement entre les items */
}

.user-menu ul li a {
    color: white;
    text-decoration: none;
    font-size: 18px;
    padding: 0px 12px;
    transition: all 0.3s ease;
    height: 60px;
    display: flex;
    align-items: center;
}

.user-menu ul li a:hover {
    background-color: white;
    color: #002C40; /* Bleu pour le texte */
}

/* Style pour l'élément actif */
.main-menu .current-menu-item a,
.main-menu .current-menu-parent a,
.user-menu .current-page {
    background-color: white;
    color: #002C40; /* Texte en bleu */

}

/* Responsive - Menu en colonne pour les petits écrans */
@media (max-width: 768px) {
    .header-menu-container {
        flex-direction: column; /* Passer en disposition colonne pour les petits écrans */
        align-items: flex-start;
    }

    .main-menu, .user-menu {
        width: 100%; /* Prendre toute la largeur */
    }

    .main-menu ul li, .user-menu ul li {
        margin-left: 0;
        margin-bottom: 10px;
    }
}

</style>
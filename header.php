<?php wp_head(); ?>
<header class="site-header">
    <div class="header-menu-container">
        <!-- Logo -->
        <div class="site-logo">
            <a href="<?php echo site_url(); ?>">
                <img src="<?php echo get_template_directory_uri(); ?>/images/logo_remmind_blanc.png" alt="Logo" />
            </a>
        </div>

        <!-- Bouton du menu burger -->
        <button class="menu-toggle" aria-label="Menu">
            <span class="burger-icon"></span>
        </button>

        <!-- Menu principal -->
        <nav class="main-menu mobile-menu">
            <!-- Bouton fermer -->
            <button class="close-menu" aria-label="Fermer le menu">&times;</button>
            
            <?php
            wp_nav_menu(array(
                'theme_location' => 'main-menu',
                'container' => '',
                'menu_class' => 'menu',
            ));

            if (is_user_logged_in()) {
                echo '<ul class="user-menu">';
                echo '<li><a href="' . site_url('/Profils') . '">Mon Profil</a></li>';
                echo '</ul>';
            }
            ?>
        </nav>
    </div>
</header>

<style>

/* Général */
.header-menu-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #002C40;
    padding: 0 15px;
    height: 60px;
}

/* Logo */
.site-logo img {
    height: 30px;
}

/* Bouton menu burger */
.menu-toggle {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
}

.burger-icon {
    width: 25px;
    height: 2px;
    background-color: white;
    position: relative;
    display: block;
}

.burger-icon::before,
.burger-icon::after {
    content: '';
    width: 25px;
    height: 2px;
    background-color: white;
    position: absolute;
    left: 0;
    transition: transform 0.3s ease;
}

.burger-icon::before {
    top: -8px;
}

.burger-icon::after {
    top: 8px;
}

/* Menu principal */
.main-menu {
    display: flex;
    flex-direction: row;
}

.main-menu ul {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
}

.main-menu ul li {
    margin-left: 20px;
    justify-content: center;
}

.main-menu ul li a {
    color: white;
    text-decoration: none;
    font-size: 18px;
    padding: 0px 12px;
    height: 60px;
    display: flex;
    align-items: center;
}

.main-menu ul li a:hover {
    background-color: white;
    color: #002C40;
}

/* Cacher le bouton "close-menu" par défaut */
.close-menu {
    display: none;
}

.user-menu {
        margin-right: 2rem;
    }

/* Responsive - Menu burger */
@media (max-width: 800px) {
    .menu-toggle {
        display: block;
    }

    .main-menu ul li a { 
        font-size:25px;
    }

    .main-menu {
        position: fixed;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        z-index: 1000;
        background-color: #002C40;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: left 0.3s ease;
    }

    .main-menu ul {
        flex-direction: column;
        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .main-menu ul li {
        margin-left: 0;
        margin-bottom: 15px;
    }

    /* Afficher le bouton "close-menu" uniquement quand le menu est ouvert */
    .main-menu.active .close-menu {
        display: block;
        position: absolute;
        top: 15px;
        right: 15px;
        background: none;
        border: none;
        color: white;
        font-size: 40px;
        margin-top: 40px;
        cursor: pointer;
    }

    .main-menu.active {
        left: 0;
    }

    .user-menu {
        margin-right: 2rem;
    }

}

</style>

<script>

document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.querySelector(".menu-toggle");
    const mainMenu = document.querySelector(".main-menu");
    const closeMenu = document.querySelector(".close-menu");

    // Ouvrir le menu
    if (menuToggle) {
        menuToggle.addEventListener("click", () => {
            mainMenu.classList.add("active");
        });
    }

    // Fermer le menu
    if (closeMenu) {
        closeMenu.addEventListener("click", () => {
            mainMenu.classList.remove("active");
        });
    }
});

</script>


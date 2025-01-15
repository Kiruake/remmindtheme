<footer class="site-footer">
    <div class="footer-container">
        <!-- Section Infos Légales -->
        <div class="footer-section">
            <h4>INFOS LÉGALES</h4>
            <ul>
                <li><a href="<?php echo get_permalink(get_page_by_title('ML')); ?>">Mentions Légales</a></li>
                <li><a href="<?php echo get_permalink(get_page_by_title('PL')); ?>">Politique de confidentialité</a></li>
            </ul>
        </div>

        <!-- Section Contact -->
        <div class="footer-section">
            <h4>CONTACT</h4>
            <p>03 81 99 47 34</p>
            <p><a href="mailto:but-mmi-montbeliard@univ-fcomte.fr">but-mmi-montbeliard@univ-fcomte.fr</a></p>
            <a href="https://www.linkedin.com/school/mmimontbeliard" target="_blank"><p>Suivez-nous sur <i class="fab fa-linkedin"></i> LinkedIn</p></a>
        </div>

        <!-- Section Adresse -->
        <div class="footer-section">
            <h4>ADRESSE</h4>
            <p>Département MMI Montbéliard</p>
            <p>4 Place Tharradin, 25200 Montbéliard</p>
        </div>

        <!-- Section Post MMI -->
        <div class="footer-section">
            <h4>POST MMI</h4>
            <ul>
            <li><a href="<?php echo home_url(); ?>">Accueil</a></li>
                <li><a href="<?php echo get_post_type_archive_link('portrait'); ?>">Portraits</a></li>
                <li><a href="<?php echo get_permalink(get_page_by_title('Metier')); ?>">Fiches métiers</a></li>
                <li><a href="<?php echo get_post_type_archive_link('actualite'); ?>">Articles</a></li>
                <li><a href="<?php echo get_permalink(get_page_by_title('FAQ')); ?>">FAQ</a></li>
            </ul>
        </div>

        <!-- Section Liens -->
        <div class="footer-section">
            <h4>Retrouvez les sites MMI</h4>
            <ul>
                <li><a href="#">Site web MMI</a></li>
                <li><a href="#">JPO</a></li>
            </ul>
        </div>
    </div>

    <!-- Logos et copyright -->
    <div class="footer-bottom">
        <div class="footer-logos">
        <img src="<?php echo get_template_directory_uri(); ?>/images/MMILogo.png"alt="MMI">
        <a href ="https://www.iut-nfc.univ-fcomte.fr/" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/IUTLogo.png" alt="IUT Nord Franche-Comté"></a>
        <a href="https://www.univ-fcomte.fr/" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/UFCLogo.png" alt="Université de Franche-Comté"></a>
        </div>
        <p>©2024 POST MMI – Tous droits réservés.</p>
    </div>
</footer>


<?php wp_footer(); ?>
</body>
</html>


<style>

.site-footer {
    background-color: #00293C;
    color: #fff;
    font-family: 'Montserrat', sans-serif;
    padding: 40px 20px;
}

.footer-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    padding-bottom: 20px;
}

.footer-section {
    flex: 1;
    min-width: 200px;
    margin: 10px;
}

.footer-section a {
    text-decoration: none;
    color: inherit;
}

.footer-section h4 {
    color: #F5A623;
    font-size: 18px;
    margin-bottom: 10px;
}

.footer-section ul {
    list-style: none;
    padding: 0;
}

.footer-section ul li {
    margin: 5px 0;
}

.footer-section ul li a {
    color: #fff;
    text-decoration: none;
}

.footer-section ul li a:hover {
    text-decoration: underline;
}

.footer-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding-top: 20px;
    font-size: 14px;
    border-top: 1px solid #ccc;
}

.footer-logos img {
    max-height: 50px;
    margin: 0 10px;
}

</style>

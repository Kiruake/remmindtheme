<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/single-portrait.css">

<?php
get_header();
?>

<main id="primary" class="site-main">

    <?php
    while (have_posts()) :
        the_post();

        // Afficher le contenu principal du post (si nécessaire)
        get_template_part('template-parts/content', get_post_type());

        // Récupérer les champs ACF
        $prenom = get_field('prenom');
        $nom = get_field('nom');
        $age = get_field('age');
        $lien_linkedin = get_field('lien_linkedin');
        $lien_portfolio= get_field('lien_portfolio');
        $presentation = get_field('presentation');
        $photo_profil = get_field('photo_profil');
        $question1 = get_field('question1');
        $reponse1 = get_field('reponse1');
        $question2 = get_field('question2');
        $reponse2 = get_field('reponse2');
        $question3 = get_field('question3');
        $reponse3 = get_field('reponse3');
        $competences = get_the_terms(get_the_ID(), 'competence');
        $metiers = get_the_terms(get_the_ID(), 'metier');
        $promotions = get_the_terms(get_the_ID(), 'promotion');
        $lieu_metier = get_field('lieu_metier');
    ?>

        <!-- Affichage des champs récupérés -->

        <section class="section-portrait">
    <div class="portrait-left">
        <?php 
        if ($photo_profil) :
            if (is_array($photo_profil)) :
                $photo_url = $photo_profil['url'];
                $photo_alt = $photo_profil['alt'];
            else :
                $photo_url = $photo_profil;
                $photo_alt = ''; 
            endif;
        ?>
            <img src="<?php echo esc_url($photo_url); ?>" alt="<?php echo esc_attr($photo_alt); ?>">
        <?php else : ?>
            <p>Aucune photo disponible.</p>
        <?php endif; ?>
    </div>

    <div class="portrait-right">
        <?php if ($promotions && !is_wp_error($promotions)) : ?>
            <h3 class="titre-promo">MMI Promotion
                <?php foreach ($promotions as $promotion) : ?>
                    <?php echo esc_html($promotion->name); ?>
                <?php endforeach; ?>
            </h3>
        <?php endif; ?>

        <?php if ($prenom || $nom || $age) : ?>
            <h2 class="titre-nom"><?php echo esc_html($prenom) . ' ' . esc_html($nom) . ', ' . esc_html($age); ?> ans</h2>
        <?php endif; ?>

        <div class="metiers">

            <div>
                <?php if ($metiers && !is_wp_error($metiers) || $lieu_metier) : ?>
            
                <?php foreach ($metiers as $metier) : ?>
                    <h2><?php echo esc_html($metier->name) . ' - ' . esc_html($lieu_metier); ?></h2>
                <?php endforeach; ?>
            
                <?php endif; ?>
            </div>

            <div class="LinkedinPortfolio">
                <?php if ($lien_linkedin) : ?>
                    <a href="<?php echo esc_url($lien_linkedin); ?>" target="_blank" rel="noopener"><img class="linkedin-icon" src="<?php echo get_template_directory_uri(); ?>/images/LinkedinIcon.png" alt="Lien vers LinkedIn"></a>
                <?php endif; ?>
                <?php if ($lien_portfolio) : ?>
                    <a href="<?php echo esc_url($lien_portfolio); ?>" target="_blank" rel="noopener"><img class="portfolio-icon" src="<?php echo get_template_directory_uri(); ?>/images/Portfolio.png" alt="Lien vers Portfolio"></a>
                <?php endif; ?>
            </div>

        </div>

        <?php if ($presentation) : ?>
            <p><?php echo esc_html($presentation); ?></p>
        <?php endif; ?>

        <?php if ($competences && !is_wp_error($competences)) : ?>
    <h3 class="sous-titre">Compétences</h3>
    <div class="competence-container">
        <?php foreach ($competences as $competence) : ?>
            <div class="competence-item"><?php echo esc_html($competence->name); ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>


        

        <h3 class="sous-titre">Témoignage</h3>

        <!-- Afficher les questions et réponses -->
        <div class="portrait-questions">
            <?php if ($question1 && $reponse1) : ?>
                <h4 class="questions"><?php echo esc_html($question1); ?></h4>
                <p><?php echo esc_html($reponse1); ?></p>
                
            <?php endif; ?>

            <?php if ($question2 && $reponse2) : ?>
                <h4 class="questions"><?php echo esc_html($question2); ?></h4>
                <p><?php echo esc_html($reponse2); ?></p>
            <?php endif; ?>

            <?php if ($question3 && $reponse3) : ?>
                <h4 class="questions"><?php echo esc_html($question3); ?></h4>
                <p><?php echo esc_html($reponse3); ?></p>
            <?php endif; ?>

            <?php if (!$question1 && !$question2 && !$question3) : ?>
        <p class="none-temoignage"><?php echo $prenom ?> n'a pas de témoignage disponible.</p>
    <?php endif; ?>
        </div>
    </div>
</section>

        <!-- Navigation entre les posts -->
        <?php

    endwhile; // Fin de la boucle
    ?>

</main><!-- #main -->

<?php get_footer(); ?>

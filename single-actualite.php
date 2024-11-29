<?php
get_header();
?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/single-actualite.css">

<main id="primary" class="site-main">

    <?php
    if (have_posts()) : while (have_posts()) : the_post();

        // Récupérer les champs personnalisés ACF
        $introduction = get_field('paragraphe_introduction');
        $author = get_the_author();
        $publish_date = get_the_date(); 
    ?>

    <h1>Articles</h1>

    <!-- Section principale de l'article -->
    <section class="section-article">

        <div class="article-content-wrapper">

            <!-- Afficher le titre -->
            <h1 class="article-title"><?php the_title(); ?></h1>

            <!-- Afficher l'introduction -->
            <?php if ($introduction) : ?>
                <div class="article-intro">
                    <p><?php echo esc_html($introduction); ?></p>
                </div>
            <?php endif; ?>

            <!-- Afficher la date de publication et l'auteur -->
            <p class="article-meta">
                Publié par <?php echo esc_html($author); ?> le <?php echo esc_html($publish_date); ?>
            </p>

              <!-- Boutons de partage -->
<div class="share-buttons">
    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank" rel="noopener" class="share-icon facebook">
        <img src="<?php echo get_template_directory_uri(); ?>/images/facebook-icon.png" alt="Partager sur Facebook">
    </a>
    <a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>" target="_blank" rel="noopener" class="share-icon twitter">
        <img src="<?php echo get_template_directory_uri(); ?>/images/twitter-icon.webp" alt="Partager sur Twitter">
    </a>
</div>



            <!-- Boucle pour afficher jusqu'à 3 sections (photo, sous-titre, paragraphe) -->
            <?php for ($i = 1; $i <= 3; $i++) :
                $photo = get_field('image_' . $i); // Champ image (photo_1, photo_2, photo_3)
                $sous_titre = get_field('sous-titre_' . $i); // Champ sous-titre
                $paragraphe = get_field('paragraphe_' . $i); // Champ paragraphe
            ?>

                <!-- Afficher la photo -->
                <?php if ($photo) : ?>
                    <div class="article-photo">
                        <img src="<?php echo esc_url($photo['url']); ?>" alt="<?php echo esc_attr($photo['alt']); ?>" />
                    </div>
                <?php endif; ?>

                <!-- Afficher le sous-titre -->
                <?php if ($sous_titre) : ?>
                    <h2 class="article-subtitle"><?php echo esc_html($sous_titre); ?></h2>
                <?php endif; ?>

                <!-- Afficher le paragraphe -->
                <?php if ($paragraphe) : ?>
                    <div class="article-paragraph">
                        <p><?php echo wp_kses_post($paragraphe); ?></p>
                    </div>
                <?php endif; ?>

            <?php endfor; ?>

            <!-- Afficher les images supplémentaires si elles existent -->
            <?php 
            $images = get_field('images_supplementaires'); // Champ Galerie d'images
            if ($images) :
            ?>
                <div class="article-images">
                    <h3 class="sous-titre">Galerie d'images</h3>
                    <?php foreach ($images as $image) : ?>
                        <div class="image-item">
                            <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                            <?php if (!empty($image['caption'])) : ?>
                                <p class="image-caption"><?php echo esc_html($image['caption']); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </section>

    <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>

<?php
get_header(); ?>

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/archive-actualite.css">
<link href="https://fonts.cdnfonts.com/css/aristotelica-display-trial" rel="stylesheet">

<div class="container">
    <h1 class="page-title">Les Actualités</h1>

    <!-- Formulaire de recherche -->
    <form method="GET" class="filter-form">
        <!-- Champ de recherche -->
        <div class="filter-group">
            <label for="search">Recherche</label>
            <input type="text" name="search" id="search" placeholder="Rechercher..." value="<?php echo isset($_GET['search']) ? esc_attr($_GET['search']) : ''; ?>">
        </div>
        <div class="filter-buttons">
            <button class="btnSubmit" type="submit">Rechercher</button>
        </div>
    </form>

    <?php
    // Récupérer la recherche
    $search_query = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';

    // Préparer la requête WP_Query pour les actualités
    $args = array(
        'post_type' => 'actualite',
        'posts_per_page' => 9,
        's' => $search_query,
    );

    $actualite_query = new WP_Query($args);

    if ($actualite_query->have_posts()) : ?>
        <div class="actualite-archive-grid">
            <?php while ($actualite_query->have_posts()) : $actualite_query->the_post(); ?>
                <article id="post-<?php the_ID(); ?>" class="actualite-item">
                    <a href="<?php the_permalink(); ?>" class="actualite-link">
                        
                        <!-- Afficher la Thumbnail -->
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="actualite-photo">
                                <?php the_post_thumbnail('large'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="actualite-info">
                            <!-- Afficher le titre -->
                            <h2 class="actualite-title"><?php the_title(); ?></h2>

                            <!-- Afficher l'introduction avec une limite de 100 caractères -->
                            <?php $paragraphe_intro = get_field('paragraphe_introduction'); ?>
                            <?php if ($paragraphe_intro) : ?>
                                <p class="actualite-intro"><?php echo wp_trim_words($paragraphe_intro, 28, ' [...]'); ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="actualite-details">
                            <!-- Afficher l'auteur et la date -->
                            <span class="actualite-author">Écrit par <?php the_author(); ?></span>
                        </div>
                    </a>
                </article>
            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => __('« Précédent', 'textdomain'),
                'next_text' => __('Suivant »', 'textdomain'),
            ));
            ?>
        </div>
    <?php else : ?>
        <p>Aucune actualité trouvée.</p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>

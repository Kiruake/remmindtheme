<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/archive-portrait.css">
<link href="https://fonts.cdnfonts.com/css/aristotelica-display-trial" rel="stylesheet">

<?php get_header(); ?>

<div class="bannierePortraits">
    <img src="<?php echo get_template_directory_uri(); ?>/images/BannierePortraits.png" alt="Bannière Alumnis">
    <h1 class="texteAlumnis">Le réseau Alumnis<br> de MMI Montbéliard</h1>
</div>

<div class="container">
    <h1 class="page-title">Les Portraits</h1>

    <!-- Formulaire de filtre -->
    <form method="GET" class="filter-form">
        <!-- Champ de recherche -->
        <div class="filter-group">
            <label for="search">Recherche</label>
            <input type="text" name="search" id="search" placeholder="John Doe.." value="<?php echo isset($_GET['search']) ? esc_attr($_GET['search']) : ''; ?>">
        </div>

        <!-- Filtre par métier -->
        <div class="filter-group">
            <label for="metier">Métier</label>
            <select name="metier" id="metier">
                <option value="">Tous les métiers</option>
                <?php
                $metiers = get_terms('metier', array('hide_empty' => true));
                foreach ($metiers as $metier) {
                    $selected = (isset($_GET['metier']) && $_GET['metier'] == $metier->slug) ? 'selected' : '';
                    echo '<option value="' . esc_attr($metier->slug) . '" ' . $selected . '>' . esc_html($metier->name) . '</option>';
                }
                ?>
            </select>
        </div>

        <!-- Filtre par promotion -->
        <div class="filter-group">
            <label for="promotion">Promotion</label>
            <select name="promotion" id="promotion">
                <option value="">Toutes les promotions</option>
                <?php
                $promotions = get_terms('promotion', array('hide_empty' => true));
                foreach ($promotions as $promotion) {
                    $selected = (isset($_GET['promotion']) && $_GET['promotion'] == $promotion->slug) ? 'selected' : '';
                    echo '<option value="' . esc_attr($promotion->slug) . '" ' . $selected . '>' . esc_html($promotion->name) . '</option>';
                }
                ?>
            </select>
        </div>

        <!-- Boutons de recherche -->
        <div class="filter-buttons">
            <button class="btnSubmit" type="submit">Rechercher</button>
        </div>
    </form>

    <?php
    // Récupérer les valeurs des champs de recherche et des filtres
    $search_query = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';
    $metier_filter = isset($_GET['metier']) ? sanitize_text_field($_GET['metier']) : '';
    $promotion_filter = isset($_GET['promotion']) ? sanitize_text_field($_GET['promotion']) : '';

    // Préparer la requête WP_Query avec les filtres et la recherche
    $args = array(
        'post_type' => 'portrait',
        'posts_per_page' => 9,
        's' => $search_query,
        'tax_query' => array(
            'relation' => 'AND',
        )
    );

    // Ajouter le filtre par métier si sélectionné
    if (!empty($metier_filter)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'metier',
            'field'    => 'slug',
            'terms'    => $metier_filter,
        );
    }

    // Ajouter le filtre par promotion si sélectionné
    if (!empty($promotion_filter)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'promotion',
            'field'    => 'slug',
            'terms'    => $promotion_filter,
        );
    }

    $portrait_query = new WP_Query($args);

    // Afficher les résultats de la requête
    if ($portrait_query->have_posts()) : ?>
        <div class="portrait-archive-grid">
            <?php while ($portrait_query->have_posts()) : $portrait_query->the_post(); ?>
                <article id="post-<?php the_ID(); ?>" class="portrait-item">
                    <a href="<?php the_permalink(); ?>" class="portrait-link">
                        <?php
                        $photo_profil = get_field('photo_profil');
                        if ($photo_profil && isset($photo_profil['url'])) : ?>
                            <div class="portrait-photo">
                                <img src="<?php echo esc_url($photo_profil['url']); ?>" alt="<?php echo esc_attr($photo_profil['alt']); ?>" />
                            </div>
                        <?php endif; ?>

                        <div class="portrait-info">
                            <h2 class="portrait-name">
                                <?php echo esc_html(get_field('prenom')) . ' ' . esc_html(get_field('nom')); ?>
                            </h2>
                            <?php
                            $metiers = get_the_terms(get_the_ID(), 'metier');
                            if ($metiers && !is_wp_error($metiers)) :
                                echo '<p class="portrait-metier">' . esc_html($metiers[0]->name) . '</p>';
                            endif;
                            ?>
                        </div>

                        <div class="portrait-details">
                            <span class="portrait-age"><?php echo esc_html(get_field('age')); ?> ans</span>
                            <?php
                            $promotions = get_the_terms(get_the_ID(), 'promotion');
                            if ($promotions && !is_wp_error($promotions)) :
                                echo '<span class="portrait-promo">Promo : ' . esc_html($promotions[0]->name) . '</span>';
                            endif;
                            ?>
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
        <p>Aucun portrait trouvé.</p>
    <?php endif; ?>

</div>

<?php get_footer(); ?>


<?php
get_header(); ?>

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/archive-actualite.css">
<link href="https://fonts.cdnfonts.com/css/aristotelica-display-trial" rel="stylesheet">

<div class="container">
    <h1 class="page-title">Les Actualités</h1>

    <!-- Formulaire de recherche et filtre par tag -->
    <form method="GET" class="filter-form">
        <!-- Champ de recherche -->
        <div class="filter-group">
            <label for="search">Rechercher un article</label>
            <input type="text" name="search" id="search" placeholder="Rechercher..." value="<?php echo isset($_GET['search']) ? esc_attr($_GET['search']) : ''; ?>">
        </div>

        <!-- Filtre par tag -->
        <div class="filter-group">
            <label for="tag">Par tag</label>
            <select name="tag" id="tag">
                <option value="">Tous les Tags</option>
                <?php
                $tags = get_terms(array('taxonomy' => 'post_tag', 'hide_empty' => true));
                foreach ($tags as $tag) {
                    $selected = (isset($_GET['tag']) && $_GET['tag'] === $tag->slug) ? 'selected' : '';
                    echo '<option value="' . esc_attr($tag->slug) . '" ' . $selected . '>' . esc_html($tag->name) . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="filter-buttons">
            <button class="btnSubmit" type="submit">Filtrer</button>
        </div>
    </form>

    <?php
    // Récupérer la recherche et le tag sélectionné
    $search_query = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';
    $tag_query = isset($_GET['tag']) ? sanitize_text_field($_GET['tag']) : '';

    // Préparer la requête WP_Query pour les actualités
    $args = array(
        'post_type' => 'actualite',
        'posts_per_page' => 9,
        's' => $search_query,
    );

    // Ajouter le filtre par tag si un tag est sélectionné
    if (!empty($tag_query)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'post_tag',
                'field'    => 'slug',
                'terms'    => $tag_query,
            ),
        );
    }

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

                            <!-- Afficher les tags sous forme de boutons -->
                            <?php
                            $tags = get_the_terms(get_the_ID(), 'post_tag');
                            if ($tags && !is_wp_error($tags)) :
                                echo '<div class="actualite-tags">';
                                foreach ($tags as $tag) {
                                    echo '<a href="' . esc_url(add_query_arg('tag', $tag->slug)) . '" class="tag-button">' . esc_html($tag->name) . '</a>';
                                }
                                echo '</div>';
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
        <p>Aucune actualité trouvée.</p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>

<style>

/* Formulaire de filtre principal */
.filter-form {
    display: flex;
    flex-wrap: wrap;
    align-items: center; /* Aligner les éléments verticalement */
    gap: 20px;
    padding: 20px;
    background-color: #f9f9f9;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    margin-bottom: 20px;
}

/* Groupe de filtres */
.filter-group {
    flex: 1 1 calc(40% - 10px); /* Ajuster la taille pour laisser de l'espace au bouton */
    display: flex;
    flex-direction: column;
}

/* Label des champs */
.filter-group label {
    font-size: 14px;
    font-weight: bold;
    margin-bottom: 8px;
    color: #333;
}

/* Champs de saisie */
.filter-group input[type="text"],
.filter-group select {
    padding: 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 90%;
    box-sizing: border-box;
}

/* Bouton de soumission */
.filter-buttons {
    flex: 0 0 auto; /* Empêche le bouton de s'étendre */
    margin-left: auto; /* Place le bouton à la fin de la ligne */
}

.filter-buttons .btnSubmit {
    padding: 10px 24px;
    font-size: 14px;
    font-weight: bold;
    color: #fff;
    background-color:#002C40;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 20px;
}

.filter-buttons .btnSubmit:hover {
    background-color: #005885;
}

/* Responsive Styles */
@media (max-width: 600px) {

    .filter-group {
        flex: 1 1 100%; /* Les champs prennent toute la largeur */
    }

    .filter-buttons {
        margin-left: 0;
        text-align: center;
        width: 100%; /* Le bouton prend toute la largeur */
        margin-top: 10px;
    }

    .filter-buttons .btnSubmit {
        width: 100%; /* Le bouton prend toute la largeur */
    }

    .filter-group input[type="text"], .filter-group select {
        width: 100%;
    }
}


</style>
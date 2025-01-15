<?php
get_header();
$term = get_queried_object(); // Récupère l'objet de la taxonomie
?>

<main id="primary" class="site-main">
    <!-- Image et Titre -->
    <div class="metier-image">
    <?php
        $image_id = get_term_meta($term->term_id, 'metier_image', true);
        if ($image_id) {
            $image_url = wp_get_attachment_url($image_id);
            if ($image_url) {
                echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($term->name) . '">';
            }
        } else {
            echo '<p>Aucune image disponible pour ce métier.</p>';
        }
        
        ?>

        <h1><?php echo esc_html($term->name); ?></h1>
    </div>

    <section class="container-section">


    <div class="container">
            <?php 
            $niveau = get_term_meta($term->term_id, 'niveau', true); 
            if ($niveau) {
                echo '<h2 style="color: #F4BB46;">Niveau minimum : BAC + ' . (int) $niveau . '</h2>';
            }
        ?>
        <p class="description"><?php echo esc_html($term->description); ?></p>

        <div class="section">
            <h2><?php echo esc_html(get_term_meta($term->term_id, 'titre1', true)); ?></h2>
            <p class="paragraphe-limite"><?php echo esc_html(get_term_meta($term->term_id, 'paragraphe1', true)); ?></p>
        </div>

        <div class="section">
            <h2><?php echo esc_html(get_term_meta($term->term_id, 'titre2', true)); ?></h2>
            <p class="paragraphe-limite"><?php echo esc_html(get_term_meta($term->term_id, 'paragraphe2', true)); ?></p>
        </div>

        <div class="section">
        <p class="paragraphe-limite">Pour en savoir plus sur ce métier, consultez la fiche ROME dédiée au <a href="<?php echo esc_url(get_term_meta($term->term_id, 'lien_rome', true)); ?>"><?php echo esc_html($term->name); ?></a></p>

        </div>
    </div>
    
    
    <!-- Affichage des posts associés -->
    <?php   
            // Affiche les posts associés à ce métier
            $args = array(
                'post_type' => 'portrait',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'metier',
                        'field' => 'term_id',
                        'terms' => $term->term_id,
                    ),
                ),
            );
            
            $query = new WP_Query($args);
            if ($query->have_posts()) :
                echo '<h2 class="section-title">Portraits liés à ce métier</h2>';
                echo '<div class="portraits-grid">';
                while ($query->have_posts()) : $query->the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <?php if (get_field('photo_profil')) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <img src="<?php echo esc_url(get_field('photo_profil')['url']); ?>" alt="<?php echo esc_attr(get_field('photo_profil')['alt']); ?>">
                            </a>
                        <?php endif; ?>
                        <h2><?php the_title(); ?></h2>
                    </article>
                <?php endwhile;
                wp_reset_postdata();
            else :
                echo '<p class="no-portrait-found">Aucun portrait trouvé pour ce métier</p>';
            endif;
            ?>
        </div>
        </section>
</main>

<?php get_footer(); ?>

<style>

/* Règles de base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background-color: #f4f7fa;
    color: #333;
    line-height: 1.6;
    font-size: 16px;
}

/* Conteneur principal */
.site-main {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0;
}

.section-title {
    padding-left: 20px;
    margin-top:-20px;
    margin-bottom: 30px;
}

/* Conteneur de l'image de la taxonomie */
.metier-image {
    position: relative;
    width: 100%;
    height: 400px; /* Hauteur de l'image, ajustez selon vos besoins */
    margin-bottom: 40px;
}

.metier-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(0.5); /* Optionnel pour assombrir l'image */
}

.metier-image h1 {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 3rem;
    font-family: 'Aristotelica Display Trial', sans-serif;
    color: #fff;
    text-align: center;
    font-weight: 700;
    z-index: 1;
}

/* Description de la taxonomie */
.description {
    font-size: 1.125rem;
    color: #555;
    line-height: 1.8;
    margin: 0 auto 40px;
    word-wrap: break-word;
}

/* Titres des sections */
h2 {
    font-size: 1.5rem;
    color: #1e3d58;
    margin-bottom: 10px;
    font-weight: 600;
}

/* Paragraphe de la taxonomie avec limite de ligne */
.paragraphe-limite {
    font-size: 1rem;
    color: #555;
    line-height: 1.6;
    margin-bottom: 40px;
    text-align: left;
    margin-left: auto;
    margin-right: auto;
    display: -webkit-box;
    -webkit-line-clamp: 6; /* Limite le nombre de lignes visibles (à ajuster selon vos besoins) */
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Mise en forme des articles */
article {
    background-color: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
    padding: 20px;
    width: 300px;
    height: 350px;
    max-height: 350px;
    transition: transform 0.3s ease;
    overflow: hidden;
    margin: auto;
}

article:hover {
    transform: translateY(-5px);
}

article img {
    width: 100%;
    height: auto;
    margin-bottom: 15px;
    max-width: 300px;
    overflow: hidden;
    max-height: 260px;
    object-fit: cover;
}

article h2 {
    font-size: 1.5rem;
    color: #1e3d58;
    text-align: center;
}

article a {
    text-decoration: none;
}


.no-portrait-found {
    background-color: #F4BB46;
    width: fit-content;
    padding: 8px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 2rem;
}

/* Responsive */
@media (min-width: 730px) {
    .container {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .container {
        grid-template-columns: repeat(3, 1fr);
    }
    article {
        max-width: 300px;
    }
}

@media (max-width: 480px) {
    h1 {
        font-size: 2.5rem;
    }

    h2 {
        font-size: 1.5rem;
    }

    p {
        font-size: 1rem;
    }
}

.portraits-grid {
    display: grid;
    grid-template-columns: repeat(1, 1fr); 
    gap: 20px;
    justify-content: center;
    padding:10px;
    margin-bottom:3rem;
}

@media (min-width: 640px) {
    .portraits-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .portraits-grid {
        grid-template-columns: repeat(3, 1fr); /* 3 colonnes pour les grands écrans */
    }
}

</style>
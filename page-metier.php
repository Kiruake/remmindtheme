<?php
/* Template Name: Metiers */
get_header(); ?>

<main id="primary" class="site-main">
<h1 class="page-metier">Fiches Métiers</h1>
    
    <div class="metiers-grid">
        <?php
        $metiers = get_terms(array(
            'taxonomy' => 'metier',
            'hide_empty' => false,
        ));

        if (!empty($metiers) && !is_wp_error($metiers)) {
            foreach ($metiers as $metier) {
                $image_url = get_term_meta($metier->term_id, 'metier_image', true);
                ?>
               
                <div class="metier-card">
                <?php 
                    $image_id = get_term_meta($metier->term_id, 'metier_image', true);
                    $image_url = $image_id ? wp_get_attachment_url($image_id) : '';

                    if ($image_url) : ?>
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($metier->name); ?>" class="metier-card-image">
                    <?php endif; ?>

                    <a href="<?php echo esc_url(get_term_link($metier)); ?>">
                     <div class="metier-card-content">
                        <h2 class="metier-card-title"><?php echo esc_html($metier->name); ?></h2>
                        <?php 
                            $niveau = get_term_meta($metier->term_id, 'niveau', true); 
                            if ($niveau) {
                                echo '<h2 class="niveau-bac">Niveau minimum : BAC +' . (int) $niveau . '</h2>';
                            }
                        ?>
                    </div>
                    </a>
                </a>
                </div>
                <?php
            }
        } else {
            echo '<p>Aucun métier trouvé.</p>';
        }
        ?>
    </div>
</main>

<?php get_footer(); ?>

<style>

/* Règles de base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.page-metier {
    font-size: 2em;
    text-transform: uppercase;
    text-align: center;
    margin-top:30px;
    font-family: 'Aristotelica Display Trial', sans-serif;
}

body {
    background-color: #f4f7fa;
    color: #333;
    font-family: 'Montserrat', sans-serif;
    line-height: 1.6;
}

.niveau-bac {
    font-size: 0.8rem;
    font-weight: lighter;
    color: initial;
}


/* Grille des métiers */
.metiers-grid {
    display: grid;
    grid-template-columns: repeat(1, 1fr); /* 1 colonne par défaut */
    gap: 20px;
    justify-content: center;
    padding: 40px;
}

@media (min-width: 768px) {
    .metiers-grid {
        grid-template-columns: repeat(2, 1fr); /* 2 colonnes pour les écrans moyens */
        padding: 40px;
    }

    .page-metier {
        text-align : start;
        padding-left: 40px;
    }

}

@media (min-width: 1024px) {
    .metiers-grid {
        grid-template-columns: repeat(3, 1fr); /* 3 colonnes pour les grands écrans */
        padding: 50px;
    }

    .page-metier {
        text-align : start;
        padding-left: 40px;
    }
}

/* Carte de métier */
.metier-card {
    background-color: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
}

.metier-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.metier-card-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.metier-card-content {
    padding: 15px;
    text-align: center;
}

.metier-card-title {
    font-size: 1rem;
    color: black;
}

.metier-card a {
    text-decoration: none;
}


</style>
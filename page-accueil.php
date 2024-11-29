<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/page-accueil.css">

<?php
/*
Template Name: Accueil
*/

get_header();
?>

<div>
    <img class="image-section" src="<?php echo get_template_directory_uri(); ?>/images/ImageAccueil.png" alt="Image de l'accueil">
</div>

<!-- Bande jaune pour les membres -->
<div class="yellow-band">
    <p class="alumni-text">Membre des Alumni? C'est par ici !</p>
    <a href="<?php echo site_url('/login'); ?>" class="espace-membre">
        Espace Membre
        <span class="icon-lock">🔒</span>
    </a>
</div>

<h1 class="page-titleAccueil">au BUT Métiers du Multimédia et de l'Internet</h1>

<p class="paragrapheAccueil">Le Bachelor Universitaire de Technologie (BUT) en Métiers du Multimédia et de l'Internet (MMI) de Montbéliard affiche un fort taux d’insertion professionnelle dans les domaines du numérique, de la communication et du design interactif. La majorité des diplômés trouvent un emploi grâce au solide réseau d’anciens étudiants, qui jouent un rôle clé dans la recommandation et l’accompagnement des jeunes diplômés, notamment via des stages. Les spécialisations en UX design, développement web et motion design sont particulièrement recherchées.</p>

<div class="sectionStat">

<div class="cardStat">
<h2 class="chiffreStat">80%</h2>
<p>trouvent un emploi dans les six mois<br> suivant l'obtention de leur diplôme</p>
</div>

<div class="cardStat2">
<h2 class="chiffreStat">40 à 50 %</h2>
<p>poursuives leurs études en<br> master après MMI</p>
</div>

<div class="cardStat">
<h2 class="chiffreStat">40%</h2>
<p>choisissent également de suivre leur<br> cursus en alternance</p>
</div>

</div>

<div class="container">

<h1 class="page-titleAccueil">Les portraits des Alumnis</h1>

<div class="portrait-archive-grid">
    <?php
    // Requête pour récupérer 3 portraits aléatoires
    $args = array(
        'post_type' => 'portrait', // Remplacez 'portrait' par le slug de votre type de contenu personnalisé
        'posts_per_page' => 4,
        'orderby' => 'rand', // Aléatoire
        'meta_query' => [
        ['key' => '_latitude', 'compare' => 'EXISTS'],
        ['key' => '_longitude', 'compare' => 'EXISTS'],
    ],
    );

    $random_portraits = new WP_Query($args);

    if ($random_portraits->have_posts()) :
        while ($random_portraits->have_posts()) : $random_portraits->the_post(); ?>
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
        <?php endwhile;
        wp_reset_postdata();
    else :
        echo '<p>Aucun portrait trouvé.</p>';
    endif;
    ?>
</div>

<!-- Bouton Voir plus -->
<div class="voir-plus-container">
    <a href="<?php echo get_post_type_archive_link('portrait'); ?>" class="voir-plus-button">voir tout</a>
</div>

</div>

<div class="containerArticle">

<h1 class="page-titleArticle">Les derniers articles</h1>

<div class="actualite-archive-grid">
    <?php
    // Requête pour récupérer 3 articles récents (ou aléatoires, selon vos besoins)
    $args = array(
        'post_type' => 'actualite', // Type de contenu : article
        'posts_per_page' => 3, // Nombre d'articles à afficher
        'orderby' => 'date', // Trier par date (ou 'rand' pour aléatoire)
        'order' => 'DESC', // Du plus récent au plus ancien
    );

    $actualite_query = new WP_Query($args);

    if ($actualite_query->have_posts()) :
        while ($actualite_query->have_posts()) : $actualite_query->the_post(); ?>
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
        <?php endwhile;
        wp_reset_postdata();
    else :
        echo '<p>Aucune actualité trouvée.</p>';
    endif;
    ?>
</div>

<!-- Bouton Voir plus -->
<div class="voir-plus-container">
    <a href="<?php echo get_post_type_archive_link('actualite'); ?>" class="voir-plus-button">Voir plus</a>
</div>

</div>

<?php
// Récupérer toutes les publications "portrait" avec des coordonnées
$args = [
    'post_type' => 'portrait',
    'posts_per_page' => -1,
    'meta_query' => [
        ['key' => '_latitude', 'compare' => 'EXISTS'],
        ['key' => '_longitude', 'compare' => 'EXISTS'],
    ],
];
$query = new WP_Query($args);

$locations = [];
if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        $ville_entreprise = get_post_meta(get_the_ID(), 'ville_entreprise', true);
        $nom_entreprise = get_post_meta(get_the_ID(), 'nom_entreprise', true);

        // Ajouter les informations à la liste
        if ($ville_entreprise && $nom_entreprise) {
            $locations[] = [
                'nom_entreprise' => $nom_entreprise,
                'ville_entreprise' => $ville_entreprise,
                'lat' => get_post_meta(get_the_ID(), '_latitude', true),
                'lng' => get_post_meta(get_the_ID(), '_longitude', true),
            ];
        }
    }
}
wp_reset_postdata();
?>

<div id="map" style="height: 500px; width: 75%; float: left;"></div>
<div id="overlay" style="height: 500px; width: 25%; float: left; background-color: #f4f4f4; padding: 20px; display: none;">
    <h3>Entreprises dans la ville </h3>
    <div id="company-list"></div>
</div>





<?php get_footer(); ?>




<script>
document.addEventListener('DOMContentLoaded', function () {
    const map = L.map('map').setView([48.8566, 2.3522], 5); // Centré sur la France

    // Ajouter la couche OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map);

    // Liste des entreprises avec leurs coordonnées
    const locations = <?php echo json_encode($locations); ?>;

    // Créer un objet pour organiser les entreprises par ville
    const cities = {};

    locations.forEach(location => {
        if (location.lat && location.lng) {
            const city = location.ville_entreprise;
            if (!cities[city]) {
                cities[city] = [];
            }
            cities[city].push(location);
        }
    });

    // Ajouter un marqueur pour chaque entreprise
    locations.forEach(location => {
        if (location.lat && location.lng) {
            const marker = L.marker([location.lat, location.lng]).addTo(map);

            // Lier un événement au marqueur pour afficher l'overlay
            marker.on('click', function() {
                const city = location.ville_entreprise; // Récupérer la ville du marqueur cliqué
                const cityLocations = cities[city]; // Filtrer les entreprises de cette ville

                // Mettre à jour l'overlay avec la liste des entreprises de cette ville
                const companyListDiv = document.getElementById('company-list');
                companyListDiv.innerHTML = ''; // Vider le contenu précédent

                // Afficher les entreprises dans l'overlay
                cityLocations.forEach(loc => {
                    const card = document.createElement('div');
                    card.className = 'card';
                    card.innerHTML = `
                        <h4>${loc.nom_entreprise}</h4>
                        <p><strong>Entreprise:</strong> ${loc.nom_entreprise}</p>
                        <p><strong>Ville:</strong> ${loc.ville_entreprise}</p>
                        <p><strong>Latitude:</strong> ${loc.lat}</p>
                        <p><strong>Longitude:</strong> ${loc.lng}</p>
                    `;
                    companyListDiv.appendChild(card);
                });

                // Afficher l'overlay
                document.getElementById('overlay').style.display = 'block';
                document.getElementById('map').style.width = '75%'; // Redimensionner la carte
            });
        }
    });

    // Ajouter un bouton pour fermer l'overlay
    const closeOverlayButton = document.createElement('button');
    closeOverlayButton.textContent = 'Fermer';
    closeOverlayButton.style.position = 'absolute';
    closeOverlayButton.style.top = '20px';
    closeOverlayButton.style.left = '80%';
    closeOverlayButton.style.zIndex = '1000';
    closeOverlayButton.style.padding = '10px';
    closeOverlayButton.addEventListener('click', function() {
        document.getElementById('overlay').style.display = 'none';
        document.getElementById('map').style.width = '100%'; // Rétablir la largeur de la carte
    });
    document.body.appendChild(closeOverlayButton);
});


</script>

<style>

#map {
    height: 500px;
    width: 75%;
    float: left;
    box-sizing: border-box;
}

#overlay {
    height: 500px;
    width: 25%;
    float: left;
    background-color: #f9f9f9;
    padding: 20px;
    box-sizing: border-box;
    display: none;
    position: relative;
    overflow-y: auto;
    border-left: 2px solid #ccc;
    box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
}

.card {
    background: #fff;
    padding: 15px;
    margin: 10px 0;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.card h4 {
    margin: 0 0 10px;
    font-size: 18px;
}

.card p {
    margin: 0;
    color: #555;
}

button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px;
    margin: 10px;
    cursor: pointer;
}

button:hover {
    background-color: #45a049;
}

/* Empêche l'overlay de dépasser la hauteur de la carte */
#overlay {
    max-height: 500px;
    overflow-y: auto;
}

/* Style de la carte et de l'overlay quand l'overlay est ouvert */
#map-wrapper {
    display: flex;
    width: 100%;
    height: 500px;
}

#map-wrapper #map {
    width: 75%; /* La carte prend 75% de la largeur de la fenêtre */
}

#map-wrapper #overlay {
    width: 25%; /* L'overlay prend 25% de la largeur */
    display: block;
}

</style>
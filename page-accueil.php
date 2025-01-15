<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remmind</title>
</head>
<body>
    
</body>
</html>

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/page-accueil.css">

<?php
/*
Template Name: Accueil
*/

get_header();
?>

<div class="image-container">
    <img class="image-section" src="<?php echo get_template_directory_uri(); ?>/images/BanniereAccueil.png" alt="Image de l'accueil">

    <!-- Text overlay on the left -->
    <div class="text-overlay">
        <h2>Parce qu’on ne quitte<br>
        jamais vraiment la team !</h2>
    </div>

    <!-- Logo overlay on the right -->
    <div class="logo-overlay">
        <img class="logo-section" src="<?php echo get_template_directory_uri(); ?>/images/logo_remmind_blanc.png" alt="Logo">
    </div>
</div>



<!-- Bande jaune pour les membres -->
<div class="yellow-band">
    <p class="alumni-text">Membre des Alumni? C'est par ici !</p>
    <a href="<?php echo site_url('/login'); ?>" class="espace-membre">
        Espace Membre
        <span class="icon-lock">🔒</span>
    </a>
</div>

<section class="first-section">
    <div class="first-section-container">
        <div class="first-section-text">
            <h2 class="first-section-title">Plus qu’un diplôme, une famille !</h2>
            <p class="first-section-paragraph">
            Parce qu’être MMI, ce n’est pas juste un diplôme, c’est une aventure qui continue bien après la remise des chapeaux.<br><br>
            La communauté MMI Montbéliard, c’est un espace où anciens, étudiants et enseignants partagent, inspirent et créent ensemble.<br><br>
            Rejoignez-nous pour vivre l’esprit MMI : mentorat, événements, collaborations… et bien sûr, des souvenirs inoubliables !
            </p>
        </div>
        <div class="first-section-images">
            <img src="<?php echo get_template_directory_uri(); ?>/images/SectionAccueil.png" alt="Image à droite" class="first-section-right-image">
        </div>
    </div>
</section>

<div class="container">

<h1 class="page-titleAccueil">Nos héros du numérique : Portraits des anciens</h1>

<div class="portrait-archive-grid">
    <?php
    // Requête pour récupérer 3 portraits aléatoires
    $args = array(
        'post_type' => 'portrait', // Remplacez 'portrait' par le slug de votre type de contenu personnalisé
        'posts_per_page' => 3,
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
                    <?php else : ?>
                        <div class="portrait-photo">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/images/DefaultProfil.png'); ?>" alt="Default Profile Picture" />
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
                        <span class="portrait-age"><?php echo (get_field('age') ? esc_html(get_field('age')) . ' ans' : 'Âge non renseigné'); ?></span>
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
        $photo_profil_id = get_post_meta(get_the_ID(), 'photo_profil', true); // ID ou URL stockée
$photo_profil = wp_get_attachment_url($photo_profil_id); // Convertit en URL si c'est un ID




        // Récupération des termes des taxonomies
        $metier_terms = get_the_terms(get_the_ID(), 'metier'); // Remplacez "metier" par le slug de la taxonomy réelle
        $promotion_terms = get_the_terms(get_the_ID(), 'promotion'); // Idem pour "promotion"

        $metiers = $metier_terms ? wp_list_pluck($metier_terms, 'name') : [];
        $promotions = $promotion_terms ? wp_list_pluck($promotion_terms, 'name') : [];

        if ($ville_entreprise && $nom_entreprise) {
            $locations[] = [
                'nom_entreprise' => $nom_entreprise,
                'ville_entreprise' => $ville_entreprise,
                'lat' => get_post_meta(get_the_ID(), '_latitude', true),
                'lng' => get_post_meta(get_the_ID(), '_longitude', true),
                'nom_portrait' => get_the_title(),
                'photo_profil' => $photo_profil,
                'permalink' => get_permalink(get_the_ID()),
                'metiers' => $metiers,  // Liste des métiers
                'promotions' => $promotions, // Liste des promotions
            ];
        }
    }
}
wp_reset_postdata();
?>

<h1 class="page-titleMap">Ils ont quitté le nid MMI, découvrez leur planque !</h1>




<div id="map-wrapper">
    <div id="map">
        <div class="filter-container">
            <select id="city-filter">
                <option value="">Sélectionnez une ville</option>
            </select>
        
            <select id="promotion-filter">
                <option value="">Sélectionnez une promotion</option>
            </select>

            <!-- Champ de recherche par nom/prénom -->
            <input type="text" id="name-filter" placeholder="Rechercher par nom / prénom">
        </div>
    </div>
    <div id="overlay">
        <h3>MMI présents à</h3>
        <div id="company-list"></div>
    </div>
</div>


<div class="containerArticle">

<h1 class="page-titleArticle">Breaking News MMI</h1>

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


                        <p class="actualite-meta" style="padding-top: 10px;">
                            Publié par <?php echo esc_html(get_the_author()); ?> le <?php echo esc_html(get_the_date()); ?>
                        </p>

                    </div>

                    
                    <div class="actualite-details">
                     
                            <?php
                            // Utiliser get_the_terms pour récupérer les tags
                            $tags = get_the_terms(get_the_ID(), 'post_tag');
                            if ($tags && !is_wp_error($tags)) :
                                echo '<div class="actualite-tags">';
                                foreach ($tags as $tag) {
                                    // Utiliser un span au lieu d'un lien
                                    echo '<span class="tag-button">' . esc_html($tag->name) . '</span> ';
                                }
                                echo '</div>';
                            endif;
                            ?>
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



<script src="https://www.goat1000.com/tagcanvas.min.js"></script>
<script src="https://www.goat1000.com/tagcanvas.js"></script>

<h1 class="page-titleCloud">Ce qu’ils retiennent et ce qu’ils veulent oublier en un mot !</h1>

<canvas id="tag-canvas" width="500" height="500"></canvas>
<ul id="tags" style="display: none;">
    <li><a href="#">Diversité</a></li>
    <li><a href="#">pluridisciplinaire</a></li>
    <li><a href="#">Famille</a></li>
    <li><a href="#">Excellence</a></li>
    <li><a href="#">Rigueur</a></li>
    <li><a href="#">Intense</a></li>
    <li><a href="#">Ambiance</a></li>
    <li><a href="#">Inoubliable</a></li>
    <li><a href="#">Qualité</a></li>
    <li><a href="#">Profs</a></li>
    <li><a href="#">Opportunité</a></li>
    <li><a href="#">Échnage</a></li>
    <li><a href="#">Changement</a></li>
    <li><a href="#">Fierté</a></li>
    <li><a href="#">Euh...</a></li>
    <li><a href="#">Perte de cheveux</a></li>
    <li><a href="#">Amitité</a></li>
    <li><a href="#">Souvenirs</a></li>
    
</ul>

<p style="text-align: center;">Si vous avez des questions n’hesitez pas a regarder notre FAQ !</p>

<div class="voir-faq-container">
    <a href="<?php echo get_permalink(get_page_by_title('FAQ')); ?>" class="voir-faq">FAQ</a>
</div>

<?php get_footer(); ?>

<script>

window.onload = function () {
        TagCanvas.Start('tag-canvas', 'tags', {
            outlineColour: 'transparent',
            textColour: ['#002C40', '#D90B3D', '#1673BA', '#8a2be2'],
            reverse: true,
            depth: 0.8,
            maxSpeed: 0.05,
        });
    };

document.addEventListener('DOMContentLoaded', function () {
    const map = L.map('map').setView([48.8566, 2.3522], 5); // Centré sur la France

    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://carto.com/">CartoDB</a>'
    }).addTo(map);

    const locations = <?php echo json_encode($locations); ?>;

    const markerGroup = L.featureGroup().addTo(map);

    const cities = {};
    const promotions = {};

    locations.forEach(location => {
        if (location.lat && location.lng) {
            const city = location.ville_entreprise;
            const promo = location.promotions ? location.promotions.join(', ') : '';

            if (!cities[city]) cities[city] = [];
            cities[city].push(location);

            if (promo) {
                if (!promotions[promo]) promotions[promo] = [];
                promotions[promo].push(location);
            }
        }
    });

    const cityFilter = document.getElementById('city-filter');
    const promoFilter = document.getElementById('promotion-filter');
    const nameFilter = document.getElementById('name-filter'); // Champ de recherche pour nom/prénom

    Object.keys(cities).forEach(city => {
        const option = document.createElement('option');
        option.value = city;
        option.textContent = city;
        cityFilter.appendChild(option);
    });

    Object.keys(promotions).forEach(promo => {
        const option = document.createElement('option');
        option.value = promo;
        option.textContent = promo;
        promoFilter.appendChild(option);
    });

    function updateMap() {
        const selectedCity = cityFilter.value;
        const selectedPromo = promoFilter.value;
        const nameQuery = nameFilter.value.toLowerCase();

        markerGroup.clearLayers();

        const filteredLocations = locations.filter(location => {
            const matchesCity = selectedCity ? location.ville_entreprise === selectedCity : true;
            const matchesPromo = selectedPromo ? location.promotions.includes(selectedPromo) : true;

            // Recherche avancée par prénom ou nom
            const fullName = location.nom_portrait ? location.nom_portrait.split(' ') : [];
            const firstName = fullName[0]?.toLowerCase() || ''; // Prénom
            const lastName = fullName[1]?.toLowerCase() || ''; // Nom

            const matchesName = nameQuery
                ? firstName.startsWith(nameQuery) || lastName.startsWith(nameQuery)
                : true;

            return matchesCity && matchesPromo && matchesName;
        });

        filteredLocations.forEach(location => {
            const marker = L.marker([location.lat, location.lng]).addTo(markerGroup);

            marker.on('click', function () {
                const city = location.ville_entreprise;
                const nameQuery = nameFilter.value.toLowerCase(); // Rechercher uniquement les résultats liés à la recherche

                // Filtrer uniquement les résultats de la recherche dans cette ville
                const filteredCityLocations = cities[city].filter(loc => {
                    const fullName = loc.nom_portrait.split(' ');
                    const firstName = fullName[0]?.toLowerCase() || '';
                    const lastName = fullName[1]?.toLowerCase() || '';

                    return (
                        (!nameQuery || firstName.startsWith(nameQuery) || lastName.startsWith(nameQuery))
                    );
                });

                filteredCityLocations.sort((a, b) => a.nom_portrait.localeCompare(b.nom_portrait));

                // Mise à jour de l'overlay
                const overlayTitle = nameQuery
                    ? `Résultat de la recherche pour "${nameQuery}" à ${city}`
                    : `MMI présents à ${city}`;
                document.querySelector('#overlay h3').textContent = overlayTitle;

                const companyListDiv = document.getElementById('company-list');
                companyListDiv.innerHTML = '';

                filteredCityLocations.forEach(loc => {
                    const imgTag = loc.photo_profil
                        ? `<img src="${loc.photo_profil}" alt="Photo de ${loc.nom_portrait}" class="profile-photo">`
                        : `<img src="<?php echo get_template_directory_uri() . '/images/DefaultProfil.png'; ?>" alt="Default Profile Picture" class="profile-photo">`;
                    const metiers = loc.metiers.length
                        ? `<p class="card-metier">${loc.metiers.join(', ')}</p>`
                        : '';
                    const promotions = loc.promotions.length
                        ? `<p class="card-promotion">Promotion : ${loc.promotions.join(', ')}</p>`
                        : '';

                    const card = document.createElement('div');
                    card.className = 'card';
                    card.innerHTML = `
                        <div class="card-left">
                            ${imgTag}
                        </div>
                        <div class="card-right">
                            <div class="card-header">
                                <a href="${loc.permalink}" class="card-name">${loc.nom_portrait}</a>
                                <p class="card-company">${loc.nom_entreprise}</p>
                            </div>
                            ${metiers}
                            ${promotions}
                        </div>
                    `;
                    companyListDiv.appendChild(card);
                });

                document.getElementById('overlay').style.display = 'block';
            });
        });

        if (filteredLocations.length > 0) {
            const bounds = markerGroup.getBounds();
            map.fitBounds(bounds, { padding: [20, 20] });
        }
    }

    updateMap();

    cityFilter.addEventListener('change', updateMap);
    promoFilter.addEventListener('change', updateMap);
    nameFilter.addEventListener('input', updateMap);
});



</script>

<style>

.first-section {
    padding: 40px 0px;
    text-align: center;
}

.first-section-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 0 auto;
}

.first-section-text {
    flex: 1;
    padding-right: 40px;
    padding-left:40px;
}

.first-section-title {
    font-size: 45px;
    font-family: 'Aristotelica Display Trial', sans-serif;
    font-weight: bold;
    text-align: start;
    margin-bottom: 10px;
    color: #333;
}

.first-section-paragraph {
    font-size: 20px;
    color: black;
    text-align: start;
    line-height: 1.6;
}

.first-section-images {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex: 1;
}

.first-section-left-image,
.first-section-right-image {
    max-width: 100%;
    height: auto;
    border-top-left-radius: 65px;
}

@media (max-width: 768px) {
    .first-section-container {
        flex-direction: column;
        text-align: center;
    }

    .first-section-text {
        padding-right: 40px;
        margin-bottom: 20px;
    }

    .first-section-images {
        flex-direction: column;
        align-items: center;
    }

    .first-section-left-image,
    .first-section-right-image {
        max-width: 90%;
        margin-bottom: 20px;
        border-top-left-radius:0;
    }
}

@media screen and (max-width: 1150px) {

    .first-section-title {
        font-size: 30px;
    }

    .first-section-paragraph {
        font-size: 16px;
    }

}


@media screen and (max-width: 900px) {

    .first-section-title {
        font-size: 25px;
    }

.first-section-paragraph {
    font-size: 15px;
}

}

#tag-canvas {
    margin: auto;
    display: flex;
    justify-content: center;
    margin-bottom: 40px;
}

.page-titleCloud {
    font-size: 2em;
    text-transform: uppercase;
    text-align: center;
    font-family: 'Aristotelica Display Trial', sans-serif;
}

.word-cloud {
    width: 80%;
    height: 80vh;
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    overflow: hidden;
    border: 2px solid #ccc;
    border-radius: 10px;
    background: #fff;
}

.word-cloud .word {
    position: absolute;
    font-size: 14px;
    font-weight: bold;
    white-space: nowrap;
    transition: transform 0.3s, color 0.3s;
}

.word-cloud .word:hover {
    transform: scale(1.3);
    cursor: pointer;
}

.color-1 {
    color: #ff4500; /* Orange */
}

.color-2 {
    color: #1e90ff; /* Bleu */
}

.color-3 {
    color: #32cd32; /* Vert */
}

.color-4 {
    color: #8a2be2; /* Violet */
}

/* Conteneur global pour la carte et l'overlay */
#map-wrapper {
    display: flex;
    justify-content: center; 
    position : relative;
    align-items: center;     
    margin: auto;
    width: 90%;
    height: 500px;
    margin-bottom: 85px;
}

/* Carte */
#map {
    height: 100%;            
    width: 90%;
    box-sizing: border-box;
}

/* Overlay */
#overlay {
    height: 100%;
    width: 40%;
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
    display: flex;
    align-items: stretch; 
    background: #fff;
    width: 100%; 
    height: 120px;
    margin: 10px 0;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.card-left {
    flex: 0 0 33%; 
    display: flex; 
    align-items: center;
    justify-content: center;
}

.profile-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.voir-faq-container {
    text-align: center;
    margin-top: 40px;
    margin-bottom: 60px;
}

.voir-faq {
    background-color: #F4BB46;
    color: white;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.page-titleMap{
    font-size: 2em;
    text-transform: uppercase;
    margin-bottom: 85px;
    text-align: center;
    margin-top: 85px;
    font-family: 'Aristotelica Display Trial', sans-serif;
}

.card-right {
    flex: 1; 
    padding: 15px; 
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.card-header {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.card-name {
    margin: 0;
    font-size: 18px;
    font-weight: bold;
    color: #333;
}

.card-company {
    margin: 0;
    font-size: 16px;
    color: #777;
}

.card-metier,
.card-promotion {
    margin: 5px 0;
    font-size: 14px;
    color: #555;
}


.filter-container {
        position: absolute;
        top: 100px;
        left: 10px;
        background-color: rgba(255, 255, 255, 0.8); /* Fond semi-transparent pour les filtres */
        border-radius: 8px;
        padding: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 1000; /* Assurez-vous que les filtres soient au-dessus de la carte */
        width: auto;
        max-width: 300px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* Style des labels des filtres */
    .filter-container label {
        font-weight: bold;
        margin-bottom: 5px;
        font-size: 14px;
        color: #333;
    }

    /* Style des champs de sélection */
    .filter-container select, input {
        padding: 8px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
        transition: border-color 0.3s ease;
    }

    .filter-container select:focus {
        border-color: #002C40; /* Changement de couleur lors du focus */
        outline: none;
    }


    .actualite-tags {
    margin-top: 10px;
    display: flex;
    flex-direction: row;
    position: absolute;
    top: 210px;
}

.tag-button {
    display: inline-block;
    padding: 5px 10px;
    background-color: #F4BB46; /* Bleu */
    color: black;
    border-radius: 15px;
    position: relative;
    font-size: 0.9em;
    margin-right: 5px;
    cursor: default; /* Désactive le curseur de lien */
}

.tag-button:hover {
    background-color: #002C40;
    color: #fff;
}


/* Styles du carousel */
.avis-carousel {
    margin: 0 auto;
    padding: 20px;
}

/* Styles pour chaque card */
.avis-card {
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 40px;
}

.card-content-avis {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.card-left-avis {
    flex-shrink: 0;
    margin-right: 10px;
}

.card-left-avis img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.card-right-avis {
    flex-grow: 1;
}

.card-name-avis {
    font-weight: 500;
    font-size: 16px;
}

.card-paragraph-avis {
    margin-top: 10px;
    font-size: 14px;
    color: #555;
    text-align: start;
}

/* Styles des flèches et des points de navigation */
.slick-prev, .slick-next {
    font-size: 24px;
    color: #333;
}

.slick-dots {
    position: unset;
    display: block;
    width: 100%;
    padding: 0;
    margin: auto;
    list-style: none;
    text-align: center;
    margin-top: 50px;
}

.slick-dots li button:before {
    font-size: 10px;
    color: #333;
}

.slick-slide {
    display: none;
    float: left;
    height: unset;
    min-height: 1px;
}

.slick-track {
    
    opacity: 1;
    width: 981px;
    transform: translate3d(0px, 0px, 0px);
    display: flex;
    gap: 2rem;
}

.image-container {
    position: relative;
}

.image-section {
    width: 100%;
}

.text-overlay {
    position: absolute;
    top: 50%;
    left: 120px;
    transform: translateY(-50%);
    color: white;
    font-size: 26px;
    font-weight: bold;
    font-family: 'Aristotelica Display Trial', sans-serif;
}

.logo-overlay {
    position: absolute;
    top: 50%;
    right: 170px;
    transform: translateY(-50%);
}

.logo-section {
    max-width: 400px;
}

@media screen and (max-width: 1000px) {
    .filter-container {
            top: 380px;
            left: 10px;
            max-width: 200px;
    }

    .filter-container select, input {
        padding: 5px;
        font-size: 11px;
    }
}

@media screen and (max-width: 880px) {

#map-wrapper {
    display: block;
}

    #map {
        position: relative;
    width: 90%;
    margin: auto;
    }

    #overlay {
    height: 100%;
    width: 90%;
    margin: auto;
    height: fit-content;
    margin-top:10px;
}

}

@media screen and (max-width: 1200px) {

    .logo-overlay {

        top: 50%;
        right: 100px;
 
    }

    .text-overlay {
        left:90px
    }
}

@media screen and (max-width: 1050px) {

    .logo-section{
        max-width: 300px;
    }

    .text-overlay {
        left:70px;
        font-size: 20px;
    }
}

@media screen and (max-width: 830px) {
    .logo-overlay {
        left: 50%;
        top: 20%;
        transform: translate(-50%, 50%);
        margin-right:0px;
        right: auto;
    }

    .image-section {
        height: 400px;
        object-fit: cover;
    }

    .text-overlay {
        left: 50%;
        font-size: 20px;
        transform: translate(-50%, 50%);
        top: 30%;
    }
}

@media screen and (max-width: 600px) {
    .text-overlay {
        width: 300px;
        font-size:18px;
    }

    .alumni-text {
        font-size: 14px;
    }

    .espace-membre {
        font-size: 12px;
    }

    .yellow-band {
        gap:10px;
    }

    .page-titleCloud {
            font-size: 1.5em;
        }

        #tag-canvas {
            width: 80%;
        }

  
}

</style>


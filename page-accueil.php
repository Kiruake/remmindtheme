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

<h1 class="page-titleAccueil">Les portraits des Alumni</h1>

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
                'metiers' => $metiers,  // Liste des métiers
                'promotions' => $promotions, // Liste des promotions
            ];
        }
    }
}
wp_reset_postdata();
?>

<h1 class="page-titleAccueil">Carte des anciens MMI : Où sont-ils maintenant ?</h1>




<div id="map-wrapper">
    <div id="map">
    <div class="filter-container">
        <select id="city-filter">
            <option value="">Sélectionnez une ville</option>
            <!-- Options seront ajoutées dynamiquement -->
        </select>
    
        <select id="promotion-filter">
            <option value="">Sélectionnez une promotion</option>
            <!-- Options seront ajoutées dynamiquement -->
        </select>
        </div>
    </div>
    <div id="overlay">
        <h3>MMi présents à</h3>
        <div id="company-list"></div>
    </div>
</div>







<?php get_footer(); ?>




<script>
document.addEventListener('DOMContentLoaded', function () {
    const map = L.map('map').setView([48.8566, 2.3522], 5); // Centré sur la France

    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://carto.com/">CartoDB</a>'
    }).addTo(map);

    // Liste des entreprises avec leurs coordonnées
    const locations = <?php echo json_encode($locations); ?>;

    // Créer un objet pour organiser les entreprises par ville et promotion
    const cities = {};
    const promotions = {};

    locations.forEach(location => {
        if (location.lat && location.lng) {
            const city = location.ville_entreprise;
            const promo = location.promotions ? location.promotions.join(', ') : ''; // Promotion(s) des entreprises
            
            // Ajouter la ville
            if (!cities[city]) {
                cities[city] = [];
            }
            cities[city].push(location);

            // Ajouter la promotion
            if (promo) {
                if (!promotions[promo]) {
                    promotions[promo] = [];
                }
                promotions[promo].push(location);
            }
        }
    });

    // Remplir les filtres par ville et promotion
    const cityFilter = document.getElementById('city-filter');
    const promoFilter = document.getElementById('promotion-filter');


    // Remplir le filtre des villes
    Object.keys(cities).forEach(city => {
        const option = document.createElement('option');
        option.value = city;
        option.textContent = city;
        cityFilter.appendChild(option);
    });

    // Remplir le filtre des promotions
    Object.keys(promotions).forEach(promo => {
        const option = document.createElement('option');
        option.value = promo;
        option.textContent = promo;
        promoFilter.appendChild(option);
    });

    // Fonction pour mettre à jour la carte avec les filtres
    function updateMap() {
        const selectedCity = cityFilter.value;
        const selectedPromo = promoFilter.value;

        // Supprimer tous les marqueurs existants
        map.eachLayer(function(layer) {
            if (layer instanceof L.Marker) {
                map.removeLayer(layer);
            }
        });

        // Fermer l'overlay avant de mettre à jour
        document.getElementById('overlay').style.display = 'none';
        document.getElementById('map').style.width = '100%'; // Revenir à la taille d'origine de la carte

        // Filtrer les locations en fonction des filtres sélectionnés
        const filteredLocations = locations.filter(location => {
            const matchesCity = selectedCity ? location.ville_entreprise === selectedCity : true;
            const matchesPromo = selectedPromo ? location.promotions.includes(selectedPromo) : true;
            return matchesCity && matchesPromo;
        });

        // Ajouter les marqueurs filtrés sur la carte
        filteredLocations.forEach(location => {
            const marker = L.marker([location.lat, location.lng]).addTo(map);

            // Lier un événement au marqueur pour afficher l'overlay
            marker.on('click', function() {
                const city = location.ville_entreprise; // Récupérer la ville du marqueur cliqué
                const selectedPromo = promoFilter.value; // Récupérer la promotion sélectionnée
                const cityLocations = cities[city]; // Filtrer les entreprises de cette ville

                // Filtrer les profils de la ville par la promotion sélectionnée
                const filteredCityLocations = cityLocations.filter(loc => {
                    // Si une promotion est sélectionnée, vérifier si cette promotion existe dans le profil
                    if (selectedPromo) {
                        return loc.promotions && loc.promotions.includes(selectedPromo);
                    }
                    return true; // Si aucune promotion n'est sélectionnée, on retourne tous les profils de la ville
                });

                filteredCityLocations.sort((a, b) => a.nom_portrait.localeCompare(b.nom_portrait));

                // Mettre à jour le titre de l'overlay avec le nom de la ville
                document.querySelector('#overlay h3').textContent = `MMI présents à ${city}`;

                // Mettre à jour l'overlay avec la liste des entreprises et des noms associés
                const companyListDiv = document.getElementById('company-list');
                companyListDiv.innerHTML = ''; // Vider le contenu précédent

                filteredCityLocations.forEach(loc => {
                    const imgTag = loc.photo_profil
                        ? `<img src="${loc.photo_profil}" alt="Photo de ${loc.nom_portrait}" class="profile-photo">`
                        : '';

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
                                <h4 class="card-name">${loc.nom_portrait}</h4>
                                <p class="card-company">${loc.nom_entreprise}</p>
                            </div>
                            ${metiers}
                            ${promotions}
                        </div>
                    `;
                    companyListDiv.appendChild(card);
                });

                // Réouvrir l'overlay
                document.getElementById('overlay').style.display = 'block';
                document.getElementById('map').style.width = '75%'; // Redimensionner la carte
            });
        });
    }

    // Fonction pour fermer l'overlay dès que les filtres sont appliqués
    function closeOverlay() {
        document.getElementById('overlay').style.display = 'none';
        document.getElementById('map').style.width = '100%'; // Revenir à la taille d'origine de la carte
    }

    // Initialiser la carte avec tous les marqueurs
    updateMap();


    // Fermer l'overlay lors du changement de filtre
    cityFilter.addEventListener('change', function() {
        closeOverlay(); // Fermer l'overlay
        updateMap(); // Mettre à jour les marqueurs
    });

    promoFilter.addEventListener('change', function() {
        closeOverlay(); // Fermer l'overlay
        updateMap(); // Mettre à jour les marqueurs
    });
});
</script>

<style>

/* Conteneur global pour la carte et l'overlay */
#map-wrapper {
    display: flex;
    justify-content: center; 
    position : relative;
    align-items: center;     
    margin: auto;
    width: 90%;
    height: 500px;
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
    width: 30%;
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
    .filter-container select {
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

</style>


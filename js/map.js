document.addEventListener('DOMContentLoaded', function () {
    const map = L.map('map').setView([48.8566, 2.3522], 13); // Centré sur Paris

    // Ajouter la couche OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map);

    // Ajouter un marqueur
    const marker = L.marker([48.8566, 2.3522]).addTo(map);
    marker.bindPopup("<b>Bienvenue à Paris !</b>").openPopup();

    // S'assurer que la carte s'affiche correctement
    setTimeout(() => {
        map.invalidateSize();
    }, 100);
});

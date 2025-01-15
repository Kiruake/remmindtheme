jQuery(document).ready(function ($) {
    $('.upload-image-button').on('click', function (e) {
        e.preventDefault();
        const button = $(this);
        const input = button.siblings('.image-url');

        const customUploader = wp.media({
            title: 'Sélectionnez une image',
            button: { text: 'Utiliser cette image' },
            multiple: false,
        }).on('select', function () {
            const attachment = customUploader.state().get('selection').first().toJSON();
            input.val(attachment.id); // Enregistrer l'ID de l'image
        }).open();
    });
});

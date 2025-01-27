jQuery(document).ready(function ($) {
    let mediaUploader;

    // Botão de upload de imagem para o favicon
    $('.tutz-upload-button').on('click', function (e) {
        e.preventDefault();

        const $button = $(this);
        const $uploaderContainer = $button.closest('.tutz-image-uploader');

        // Reutiliza o uploader se já estiver criado
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        // Cria o uploader
        mediaUploader = wp.media({
            title: 'Selecionar Imagem',
            button: {
                text: 'Usar esta imagem',
            },
            multiple: false,
        });

        // Quando uma imagem é selecionada
        mediaUploader.on('select', function () {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            $uploaderContainer.find('input[type="hidden"]').val(attachment.url);
            $uploaderContainer.find('.tutz-image-preview')
                .html('<img src="' + attachment.url + '" style="max-width: 150px; height: auto;" />')
                .show();
            $uploaderContainer.find('.tutz-remove-button').show();
        });

        mediaUploader.open();
    });

    // Botão para remover o favicon
    $('.tutz-remove-button').on('click', function (e) {
        e.preventDefault();

        const $button = $(this);
        const $uploaderContainer = $button.closest('.tutz-image-uploader');

        $uploaderContainer.find('input[type="hidden"]').val('');
        $uploaderContainer.find('.tutz-image-preview').hide().html('');
        $button.hide();
    });
});

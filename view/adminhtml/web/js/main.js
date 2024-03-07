require(['jquery'], function($) {
    $(document).ready(function() {
        var importButton = $('[data-role="import-button"]');
        var importPopup = $('[data-role="import-popup"]');

        importButton.click(function() {
            importPopup.modal('openModal');
        });
    });
});

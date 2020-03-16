jQuery(function () {
    var $iframes = jQuery('.plugin__pdfjs');
    $iframes.contents().find('#openFile').hide();

    if (JSINFO['plugin_pdfjs']['hide_download_button'] === 1) {
        $iframes.contents().find('#download').hide();
    }
});

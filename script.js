jQuery(document).ready(function () {
    function remove_buttons() {
        var $iframes = jQuery('.plugin__pdfjs');
        $iframes.contents().find('#openFile').remove();
        $iframes.contents().find('#secondaryOpenFile').remove();
        if (JSINFO['plugin_pdfjs']['hide_download_button'] === 1) {
            $iframes.contents().find('#download').remove();
            $iframes.contents().find('#secondaryDownload').remove();
        }
    }
    remove_buttons();
    // do it once again after 4 seconds for slower networks -> refer #18
    setTimeout(remove_buttons, 4000);
});

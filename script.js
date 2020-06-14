jQuery(document).ready(function(){
	function remove_buttons() {
		var $iframes = jQuery('.plugin__pdfjs');
		$iframes.contents().find('#openFile').remove();

		if (JSINFO['plugin_pdfjs']['hide_download_button'] === 1) {
			$iframes.contents().find('#download').remove();
		}
	}
	setTimeout(remove_buttons,4000);
});

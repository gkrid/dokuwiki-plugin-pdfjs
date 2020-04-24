<?php

if(!defined('DOKU_INC')) die();

class action_plugin_pdfjs extends DokuWiki_Action_Plugin {
    /**
     * @param Doku_Event_Handler $controller
     */
    public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('DOKUWIKI_STARTED', 'AFTER', $this, 'add_jsinfo');
    }

    /**
     * @param Doku_Event $event
     */
    public function add_jsinfo(Doku_Event $event) {
        global $JSINFO;

        $JSINFO['plugin_pdfjs']['hide_download_button'] = $this->getConf('hide_download_button');
    }
}

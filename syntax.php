<?php
/**
 * DokuWiki Plugin Pdfjs (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Sahara Satoshi <sahara.satoshi@gmail.com>
 * @author  Szymon Olewniczak <solewniczak@rid.pl>
 *
 * SYNTAX: {{pdfjs [size] > mediaID?zoom|title }}
 *
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once DOKU_PLUGIN.'syntax.php';

class syntax_plugin_pdfjs extends DokuWiki_Syntax_Plugin {

    public function getType()  { return 'substition'; }
    public function getPType() { return 'normal'; }
    public function getSort()  { return 305; }
    public function connectTo($mode) {
        $this->Lexer->addSpecialPattern('{{pdfjs.*?>.*?}}',$mode,'plugin_pdfjs');
    }

    /*
     * @var possible zoom values
     *
     * @see https://github.com/mozilla/pdf.js/wiki/Viewer-options
     */
    protected $zoom_opts = array('auto', 'page-actual', 'page-fit', 'page-width',
        '50', '75', '100', '125', '150', '200', '300', '400');

    /**
     * handle syntax
     */
    public function handle($match, $state, $pos, Doku_Handler $handler){

        $opts = array( // set default
                       'id'      => '',
                       'title'   => '',
                       'width'   => '100%',
                       'height'  => '600px',
                       'zoom'    => '',
                     );

        list($params, $media) = explode('>', trim($match,'{}'), 2);

        // handle media parameters (linkId and title)
        list($link, $title) = explode('|', $media, 2);

        //get the zoom
        list($id, $zoom) = explode('?', $link, 2);
        if ($zoom) {
            if (in_array($zoom, $this->zoom_opts)) {
                $opts['zoom'] = $zoom;
            } else {
                msg('pdfjs: unknown zoom: '.$zoom, -1);
            }
        }

        // handle viewer parameters
        // split phrase of parameters by white space
        $tokens = preg_split('/\s+/', $params);

        foreach ($tokens as $token) {
            // get width and height of iframe
            $matches=array();
            if (preg_match('/(\d+(%|em|pt|px)?)([,xX](\d+(%|em|pt|px)?))?/',$token,$matches)){
                if ($matches[4]) {
                    // width and height was given
                    $opts['width'] = $matches[1];
                    if (!$matches[2]) $opts['width'].= 'px'; //default to pixel when no unit was set
                    $opts['height'] = $matches[4];
                    if (!$matches[5]) $opts['height'].= 'px'; //default to pixel when no unit was set
                    continue;
                } elseif ($matches[2]) {
                    // only height was given
                    $opts['height'] = $matches[1];
                    if (!$matches[2]) $opts['height'].= 'px'; //default to pixel when no unit was set
                    continue;
                }
            }

        }

        $opts['id'] = trim($id);
        if (!empty($title)) $opts['title'] = trim($title);

        return array($state, $opts);
    }

    public function render($format, Doku_Renderer $renderer, $data) {

        if ($format != 'xhtml') return false;

        list($state, $opts) = $data;
        if ($opts['id'] == '') return false;

        $html = $this->_html_embed_pdfjs($opts);
        $renderer->doc .= $html;

        return true;
    }

    /**
     * Generate html for sytax {{pdfjs>}}
     *
     */
    private function _html_embed_pdfjs($opts) {
        // make reference link
        $src = DOKU_URL.'lib/plugins/pdfjs/pdfjs/web/viewer.html';
        $src.= '?file=' . rawurlencode(ml($opts['id']));
        if ($opts['zoom']) $src .= '#zoom='.$opts['zoom'];

		$html = '<iframe src="' . $src . '"';
		$html.= ' style="';
		if ($opts['width'])  $html.= ' width: '.$opts['width'].';';
		if ($opts['height']) $html.= ' height: '.$opts['height'].';';
		$html.= ' border: none;';
		$html.= '"></iframe>'.NL;

        return $html;
    }

}

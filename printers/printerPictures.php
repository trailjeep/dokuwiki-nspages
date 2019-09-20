<?php
/**
* Plugin nspages : Displays nicely a list of the pages of a namespace
 *
 * @license GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

if(!defined('DOKU_INC')) die();
require_once 'printer.php';

class nspages_printerPictures extends nspages_printer {
    private static $_dims = array('w' => 0, 'h' => 96);
    function __construct($plugin, $mode, $renderer, $data){
        parent::__construct($plugin, $mode, $renderer, $data);
        $this->_defaultPicture = $data['defaultPicture'];
    }

    function _print($tab, $type) {
        $default  = $this->_defaultPicture;
        $this->renderer->doc .= '<div class="nspagesImagesModeMain">';
        foreach($tab as $item) {
                                $url      = wl($item['id']);
                                $meta     = p_get_metadata($item['id']);
                                $title    = "";
                                $abstract = $meta['description']['abstract'];
                                $source   = $meta['source'];
                                $author   = $meta['creator'];
                                $face     = 'https://secure.gravatar.com/avatar/13455b965f20bdea48fe1f1978125ea6%253Fs%253D16%2526d%253Dmm%2526r%253Dg%2526.png?s=16';
                                $created  = date('Y-m-d', $meta['date']['created']);
                                $modified = date('Y-m-d', $meta['date']['modified']);
								if (is_array($meta['relation']['media'])) {
									$media = key($meta['relation']['media']);
								} else {
									$media = '';
								}
                                $image    = ml(current(array_filter(array($meta['relation']['firstimage'], $media, $default, 'wiki:dokuwiki-128.png'))), self::$_dims, true);
                                $tags     = $meta['subject'];
                                $tagss    = '<span>';
                                if ( !empty($tags)) {
                                        foreach ($tags as $tag) {
                                                $tagss .= '<a class="nspagesImagesModeTag label label-default" title="tag:' . $tag . '" rel="tag" href="' . $DOKU_URL . 'tag/'.$tag.'?do=showtag&tag='.$tag.'"><i class="fa fa-fw fa-tag"></i>'.$tag.'</a> ';
                                        }
                                }
                                $tagss   .= '</span>';
                                $this->renderer->doc .= '<div class="nspagesImagesModeItem">';
                $this->renderer->doc .= '<a href="' . $url . '"><img class="nspagesImagesModeImg" src="'. $image . '&t=' . microtime() .'" height="96" /></a>';
                $this->renderer->doc .= '<h3 class="nspagesImagesModeTitle"><a href="' . $url . '">'.$item['nameToDisplay'] . '</a></h3>';
                                if ( $abstract !== "" ) {
                                        $this->renderer->doc .= '<br /><span class="nspagesImagesModeAbstract">' . $abstract . '</span><br />';
                                } else {
                                        $this->renderer->doc .= '<br />';
                                }
                                if ( $source !== "" ) {
                                    $this->renderer->doc .= '<span class="nspagesImagesModeSource"><a class="urlextern" href="' . $source . '" title="' . $source . '" target="_blank" rel="nofollow noopener">Source</a></span> &middot; <a href="https://web.archive.org/web/' . $source . '" title="Internet Archive" target="_blank" rel="nofollow noopener"><i class="fa fa-university"></i></a> &middot; ';
                                }
                                if ( $tags ) {
                                    $this->renderer->doc .= '<span class="nspagesImagesModeTags">' . $tagss . '</span> &middot; ';
                                }
                                $this->renderer->doc .= '<span class="nspagesImagesModeAuthor"><i class="fa fa-calendar"></i> ' . $created . ' by <img src="' . $face . '" style="vertical-align: sub" /> ' . $author . '</span>';
                                $this->renderer->doc .= '</div><br />';
        }
        $this->renderer->doc .= '</div>';
    }
}

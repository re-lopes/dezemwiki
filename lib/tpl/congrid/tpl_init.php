<?php
/**
 * DokuWiki Congrid Template: Setup/Initialization
 *
 * @author  LarsDW223
 * @license GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

// must be run from within DokuWiki
if (!defined('DOKU_INC')) die();

global $INFO;
global $INPUT;

// Get the template info (useful for debug)
if ($INFO['isadmin'] && $INPUT->str('do') && $INPUT->str('do') == 'check') {
    $template_info = confToHash(dirname(__FILE__).'/template.info.txt');
    msg('Congrid-Template version: v' . $template_info['date'], 1, '', '', MSG_ADMINS_ONLY);
}

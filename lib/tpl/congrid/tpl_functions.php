<?php
/**
 * DokuWiki Congrid Template: template functions
 *
 * @author  LarsDW223
 * @license GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

// must be run from within DokuWiki
if (!defined('DOKU_INC')) die();

require_once(dirname(__FILE__).'/tpl_default.php');

/* The type is well-known and by it's name it is clear what to do,
   e.g. 'title', 'content', 'sitetools'... */
define('TEMPLATE_KNOWN_TYPE', 1);
/* The type is unknown and it is not clear what to do.
   An empty cell will be rendered with some error text. */
define('TEMPLATE_INVALID_TYPE', 2);
/* The type is not known but is a container for other items
   which could be pages or well-known types. */
define('TEMPLATE_CONTAINER_ITEMS', 3);
/* The type is not known but defines a page which shall be rendered.
   This can e.g. be used to render the sidebar. */
define('TEMPLATE_CONTAINER_PAGES', 4);

/**
 * Check if the given selector matches the current requests context.
 * 
 * The selector may include the following simple comparisons:
 * - "do==value":
 *   value must match the 'do' parameter of the current request URL
 * - "do!=value":
 *   value must NOT match the 'do' parameter of the current request URL
 * - "ID==value":
 *   value must match the current page ID
 * - "ID!=value":
 *   value must NOT match the current page ID
 * - "page==value":
 *   value must match the 'page' parameter of the current request URL
 * - "page!=value":
 *   value must NOT match the 'page' parameter of the current request URL
 * - "ACT==value":
 *   value must match the current action ($ACT)
 * - "ACT!=value":
 *   value must NOT match the current action ($ACT)
 *
 * Multiple comparisons can be given separated by spaces. In this case all
 * comparisons must be matched to let the whole selector match (logical and).
 * 
 * The return value 0 means no match. Numbers higher than 0 means a match.
 * A higher number means a more specific match.
 * 
 * @param string $selector
 * @return integer
 */
function tpl_selector_matches($selector) {
    global $INPUT;
    global $ID;
    global $ACT;

    $parts = explode(' ', $selector);
    $match = 0;
    $match_count = 0;
    foreach ($parts as $part) {
        if ($part == '*') {
            $match += 1;
            $match_count++;
        } else {
            $part = str_replace(' ', '', $part);
            if (strncmp($part, 'do==', 4) == 0) {
                $value = substr($part, 4);
                if ($INPUT->str('do') == $value) {
                    $match += 2;
                    $match_count++;
                }
            } else if (strncmp($part, 'do!=', 4) == 0) {
                $value = substr($part, 4);
                if ($INPUT->str('do') != $value) {
                    $match += 2;
                    $match_count++;
                }
            } else if (strncmp($part, 'ID==', 4) == 0) {
                $value = substr($part, 4);
                if ($ID == $value) {
                    $match += 2;
                    $match_count++;
                }
            } else if (strncmp($part, 'ID!=', 4) == 0) {
                $value = substr($part, 4);
                if ($ID != $value) {
                    $match += 2;
                    $match_count++;
                }
            } else if (strncmp($part, 'page==', 6) == 0) {
                $value = substr($part, 6);
                if ($INPUT->str('page') == $value) {
                    $match += 2;
                    $match_count++;
                }
            } else if (strncmp($part, 'page!=', 6) == 0) {
                $value = substr($part, 6);
                if ($INPUT->str('page') != $value) {
                    $match += 2;
                    $match_count++;
                }
            } else if (strncmp($part, 'ACT==', 5) == 0) {
                $value = substr($part, 5);
                if ($ACT == $value) {
                    $match += 2;
                    $match_count++;
                }
            } else if (strncmp($part, 'ACT!=', 5) == 0) {
                $value = substr($part, 5);
                if ($ACT != $value) {
                    $match += 2;
                    $match_count++;
                }
            }
        }
    }

    if ($match_count == count($parts)) {
        // All parts matched
        return $match;
    }

    // No match or only partial match
    return 0;
}
/**
 * Select the layout to use as specified in the template's configuration.
 * 
 * The function JSON decodes the config parameter 'Layouts', selects
 * the best match and returns the array (Layouts[x]).
 * 
 * @return array|NULL
 */
function tpl_get_layout() {
    global $default_layout;
    global $conf;

    $config = tpl_getConf('Layouts', NULL);
    if (empty($config)) {
        $config = $default_layout;
    }

    $layouts = json_decode($config, true);
    if ($layouts === NULL) {
        $layouts = json_decode($default_layout, true);
    }
    $layouts = $layouts['layouts'];

    // Go through all selectors and return the best match
    $best = 0;
    $best_layout = NULL;
    for ($index = 0 ; $index < count($layouts) ; $index++) {
        foreach ($layouts[$index]['select'] as $select) {
            $match = tpl_selector_matches($select);
            if ($match > $best) {
                $best = $match;
                $best_layout = &$layouts[$index];
            }
        }
    }

    if ($best > 0) {
        return $best_layout;
    }

    // Nothing found!
    return NULL;
}
/**
 * Check if $name refers to a build-in background and return the
 * corresponding CSS class name. If $name is unknown, NULL will be returned.
 * 
 * @param string $name Config-name of the background
 * @return string|NULL
 */
function tpl_get_background_class($name) {
    /* Add backgrounds here.
       For the class name '-' will be replaced with '_'! */
    $backgrounds = array('weave', 'upholstery', 'bricks', 'diagonal-stripes',
                         'tablecloth', 'waves', 'lined-paper',
                         'blueprint-grid', 'cicada-stripes', 'honey-comb',
                         'cross-dots', 'cross', 'tartan', 'japanese-cube');

    if (in_array($name, $backgrounds)) {
        return str_replace('-', '_', $name);
    }
    return NULL;
}
/**
 * Print the opening of the <body> element as specified by @$layout.
 */
function tpl_print_body(array $layout) {
    $body_class = tpl_get_background_class($layout['background']);
    if (!empty($body_class)) {
        print('<body class="'.$body_class.'">');
    } else {
        print('<body>');
    }
}
/**
 * Print/generate the 'title' section.
 */
function tpl_generate_title() {
    global $conf;

    // Get logo either out of the template images folder or data/media folder
    $logo      = tpl_getMediaFile(array(':wiki:logo.png', ':logo.png', 'images/logo.png'), false, $logoSize);
    $title     = $conf['title'];
    //$tagline   = ($conf['tagline']) ? '<span id="dw__tagline">'.$conf['tagline'].'</span>' : '';

    // Display logo and wiki title in a link to the home page
    $home = wl();
    tpl_link($home,
        '<img src="'.$logo.'" alt="'.$title.'" id="dw__logo" /><span id="dw__title">'.$title.'</span>',
        'accesskey="h" title="[H]"');
    tpl_flush();
}
/**
 * Print/generate the 'tagline' section.
 */
function tpl_generate_tagline() {
    global $conf;

    if ($conf['tagline']) {
        print('<span id="dw__tagline">'.$conf['tagline'].'</span>');
    }
    tpl_flush();
}
/**
 * Print/generate the 'trace' section.
 */
function tpl_generate_trace() {
    global $conf;

    if ($conf['breadcrumbs']) {
        $sep = tpl_getConf('BreadcrumbsSep', NULL);
        tpl_breadcrumbs($sep);
    }
    tpl_flush();
}
/**
 * Print/generate the 'youarehere' section.
 */
function tpl_generate_youarehere() {
    global $conf;

    if ($conf['youarehere']) {
        $sep = tpl_getConf('YouAreHereSep', NULL);
        tpl_youarehere($sep);
    }
    tpl_flush();
}
/**
 * Print/generate the 'search' section.
 */
function tpl_generate_search() {
    tpl_congrid_searchform(true, true, false);
    tpl_flush();
}
/**
 * Print/generate the 'sitetools' section.
 */
function tpl_generate_sitetools() {
    print('<h3 class="a11y">'.$lang['site_tools'].'</h3>');

    // ToDo: for mobile display as drop down
    //$mobile = (new \dokuwiki\Menu\MobileMenu())->getDropdown($lang['tools']);
    //print('<div class="mobileTools">'.$mobile.'</div>');

    $site = (new \dokuwiki\Menu\SiteMenu())->getListItems('action ', false);
    print ('<ul>'.$site.'</ul>');
    tpl_flush();
}
/**
 * Print/generate the 'usertools' section.
 */
function tpl_generate_usertools() {
    global $conf;

    if ($conf['useacl']) {
        print('<h3 class="a11y">'.$lang['user_tools'].'</h3>');
        print('<ul>');
        if (!empty($_SERVER['REMOTE_USER'])) {
            print('<li class="user">');

            /* 'Logged in as ...' */
            tpl_userinfo();

            print('</li>');
        }
        print((new \dokuwiki\Menu\UserMenu())->getListItems('action '));
        print('</ul>');
    }
    tpl_flush();
}
/**
 * Print/generate the 'pageid' section.
 * 
 * @param $inside true=render page ID inside of content section,
 *                false=render page ID in extra div/section,
 */
function tpl_generate_pageid($inside=true) {
    global $ID;

    if ($inside == true) {
        print('<div class="pageId inside"><span>'.hsc($ID).'</span></div>');
    } else {
        print('<div class="pageId"><span>'.hsc($ID).'</span></div>');
    }
    tpl_flush();
}
/**
 * Print/generate the 'docinfo' section.
 * 
 * @param boolean $inside true=render doc-info inside of content section,
 *                        false=render doc-info in extra div/section,
 */
function tpl_generate_docinfo($inside=true) {
    if ($inside == true) {
        print('<div class="docInfo inside">');
    } else {
        print('<div class="docInfo">');
    }
    tpl_pageinfo();
    print('</div>');
    tpl_flush();
}
/**
 * Places the TOC where the function is called
 *
 * If you use this you most probably want to call tpl_content with
 * a false argument
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 *
 * @param bool $return  Should the TOC be returned instead to be printed?
 * @param int  $columns Number of columns
 * @return string
 */
function tpl_congrid_toc($return = false, $columns=1) {
    global $TOC;
    global $ACT;
    global $ID;
    global $REV;
    global $INFO;
    global $conf;
    global $INPUT;
    $toc = array();

    if(is_array($TOC)) {
        // if a TOC was prepared in global scope, always use it
        $toc = $TOC;
    } elseif(($ACT == 'show' || substr($ACT, 0, 6) == 'export') && !$REV && $INFO['exists']) {
        // get TOC from metadata, render if neccessary
        $meta = p_get_metadata($ID, '', METADATA_RENDER_USING_CACHE);
        if(isset($meta['internal']['toc'])) {
            $tocok = $meta['internal']['toc'];
        } else {
            $tocok = true;
        }
        $toc = isset($meta['description']['tableofcontents']) ? $meta['description']['tableofcontents'] : null;
        if(!$tocok || !is_array($toc) || !$conf['tocminheads'] || count($toc) < $conf['tocminheads']) {
            $toc = array();
        }
    } elseif($ACT == 'admin') {
        // try to load admin plugin TOC
        /** @var $plugin DokuWiki_Admin_Plugin */
        if ($plugin = plugin_getRequestAdminPlugin()) {
            $toc = $plugin->getTOC();
            $TOC = $toc; // avoid later rebuild
        }
    }

    trigger_event('TPL_TOC_RENDER', $toc, null, false);

    $html = '';
    if (count($toc) > 0) {
        global $lang;
        $html  = '<!-- TOC START -->'.DOKU_LF;
        $html .= '<div id="dw__toc" class="dw__toc">'.DOKU_LF;
        $html .= '<h3 class="toggle">';
        $html .= $lang['toc'];
        $html .= '</h3>'.DOKU_LF;
        if ($columns > 1) {
            $html .= '<div style="column-count: '.$columns.'; column-fill: balance;">'.DOKU_LF;
        } else {
            $html .= '<div>'.DOKU_LF;
        }
        $html .= html_buildlist($toc,'toc','html_list_toc','html_li_default',true);
        $html .= '</div>'.DOKU_LF.'</div>'.DOKU_LF;
        $html .= '<!-- TOC END -->'.DOKU_LF;
    }


    /*$html = html_TOC($toc);*/
    if($return) return $html;
    echo $html;
    return '';
}
/**
 * Print/generate the 'toc' section.
 * 
 * If this is called then there is an extra div containing the toc.
 * Most likely 'tpl_generate_content()' was called with $toc == false.
 */
function tpl_generate_toc($columns=1) {
    tpl_congrid_toc(false, $columns);
    tpl_flush();
}
/**
 * Print/generate the 'content' section.
 * 
 * If this is called then there is an extra div containing the toc.
 * Most likely 'tpl_generate_content()' was called with $toc == false.
 * 
 * @param boolean $page_id  Render the page ID section
 * @param boolean $doc_info Render the doc-info section
 * @param boolean $toc      Render the toc section
 */
function tpl_generate_content($page_id=true, $doc_info=true, $toc=true) {

    // Output any messages created by 'msg(...)' calls
    html_msgarea();

    if ($page_id == true) {
        tpl_generate_pageid();
    }

    print('<div id="dokuwiki__page" class="page group">');
    tpl_flush();
    tpl_includeFile('pageheader.html');

    // Render the real content/the wiki page
    print('<div id="dokuwiki__top"></div>');
    tpl_content($toc);
    print('<div id="dokuwiki__bottom"></div>');

    tpl_includeFile('pagefooter.html');
    print('</div>');

    if ($doc_info == true) {
        tpl_generate_docinfo();
    }

    tpl_flush();
}
/**
 * Print/generate a 'page' section.
 * 
 * A page section includes a specific wiki page or HTML page and could e.g.
 * be used to genertae the sidebar.
 * 
 * @param array  $layout Layout to be used
 * @param string $page   Page name
 * @param array  $params Cell params to apply
 */
function tpl_generate_page(array $layout, $page, $params) {
    if (!empty($params['headline-string-name'])) {
        print('<h3>'.$lang[$params['headline-string-name']].'</h3>');
    }
    print ('<div class="content">');
    tpl_flush();
    if (strpos($page, '.html') !== false) {
        tpl_includeFile($page);
    } else {
        tpl_include_page($page, true, true);
    }
    print('</div>');
    tpl_flush();
}
/**
 * Print/generate the 'pagetools' section.
 */
function tpl_generate_pagetools() {
    print('<h3 class="a11y">'.$lang['page_tools'].'</h3>');
    print('<div class="tools">');
    print('    <ul>');
    print((new \dokuwiki\Menu\PageMenu())->getListItems());
    print('    </ul>');
    print('</div>');
    tpl_flush();
}
/**
 * Print/generate the 'footer' section.
 */
function tpl_generate_footer() {
    global $conf;

    /* Generate license text */
    tpl_license('');

    print('<div class="buttons">');
    /* license button, no wrapper */
    tpl_license('button', true, false, false);
    $target = ($conf['target']['extern']) ? 'target="'.$conf['target']['extern'].'"' : '';
    $basedir = tpl_basedir();
    print('<a href="https://www.dokuwiki.org/donate" title="Donate" '.$target.'>
               <img src="'.$basedir.'images/button-donate.gif" width="80" height="15" alt="Donate" /></a>
           <a href="https://php.net" title="Powered by PHP" '.$target.'>
               <img src="'.$basedir.'images/button-php.gif" width="80" height="15" alt="Powered by PHP" /></a>
           <a href="//validator.w3.org/check/referer" title="Valid HTML5" '.$target.'>
               <img src="'.$basedir.'images/button-html5.png" width="80" height="15" alt="Valid HTML5" /></a>
           <a href="//jigsaw.w3.org/css-validator/check/referer?profile=css3" title="Valid CSS" '.$target.'>
               <img src="'.$basedir.'images/button-css.png" width="80" height="15" alt="Valid CSS" /></a>
           <a href="https://dokuwiki.org/" title="Driven by DokuWiki" '.$target.'>
               <img src="'.$basedir.'images/button-dw.png" width="80" height="15" alt="Driven by DokuWiki" /></a>');
    print('</div>');

    tpl_includeFile('footer.html');
}
/**
 * Create the grid.
 * 
 * This function validates the 'grid' array contained in @$layout and
 * performs adjustment if necessary:
 * - guarantee an equal amount of columns in each row
 * - rename 'space' to 'empty'
 */
function tpl_create_grid(&$layout) {
    // The JSON decoding gives us the grid already as an array.
    // We just do some checks and adjust the array if necessary

    // Get number of max. columns
    $max_columns = 0;
    foreach ($layout['grid'] as $row) {
        $columns = count($row);
        if ($columns > 0) {
            if ($columns > $max_columns) {
                $max_columns = $columns;
            }
        }
    }

    // Rename 'space' to 'empty'
    $row = 0;
    for ($row = 0 ; $row < count($layout['grid']) ; $row++) {
        $columns = count($layout['grid'][$row]);
        for ($column = 0 ; $column < $columns ; $column++) {
            if ($layout['grid'][$row][$column] == 'space') {
                $layout['grid'][$row][$column] = 'empty';
            }
        }
    }

    // Validate grid: if there are any rows which do not have a number
    // of columns equal to $max_columns then fill the row up with 'empty'
    $index = 0;
    for ($index = 0 ; $index < count($layout['grid']) ; $index++) {
        $columns = count($layout['grid'][$index]);
        if ($columns < $max_columns) {
            for (;$columns < $max_columns ; $columns++) {
                $layout['grid'][$index][] = 'empty';
            }
        }
    }
}
/**
 * Returns the CSS params in @params as an CSS formated string.
 * 
 * @return string
 */
function tpl_get_css_props(array $params) {
    // Generate CSS props from cell parameters
    $css_props = '';
    if (is_array($params['css'])) {
        foreach ($params['css'] as $key => $value) {
            if ($value !== NULL) {
                $css_props .= '    '.$key.': '.$value.";\n";
            }
        }
    }
    return $css_props;
}
/**
 * Print the grid area and CSS params for @$item.
 * 
 * $item can be the name of a well-known build-in section like 'title'
 * or 'content'. Or it can be the name of a user-defined container
 * which then includes well-known items or pages.
 * 
 * @param array $layout Layout to use
 * @param string $item  Item type or name to render
 */
function tpl_print_grid_area($layout, $item) {
    $params = tpl_get_cell_params($layout, $item);

    // Generate CSS props from cell parameters
    $css_props = tpl_get_css_props($params);

    switch ($item) {
        case 'sitetools':
            print("#dokuwiki__site div.dokuwiki__sitetools {\n    grid-area: sitetools;\n");
        break;
        case 'usertools':
            print("#dokuwiki__site div.dokuwiki__usertools {\n    grid-area: usertools;\n");
        break;
        case 'pagetools':
            print("#dokuwiki__site div.dokuwiki__pagetools {\n    grid-area: pagetools;\n");
        break;
        case 'title':
            print("#dokuwiki__site div.dokuwiki__title {\n    grid-area: title;\n");
        break;
        case 'tagline':
            print("#dokuwiki__site div.dokuwiki__tagline {\n    grid-area: tagline;\n");
        break;
        case 'trace':
            print("#dokuwiki__site div.trace { grid-area:\n    trace;\n");
        break;
        case 'youarehere':
            print("#dokuwiki__site div.youarehere {\n    grid-area: youarehere;\n");
        break;
        case 'toc':
            print("#dokuwiki__site div.dokuwiki__toc {\n    grid-area: toc;\n");
        break;
        case 'content':
            print("#dokuwiki__site div.dokuwiki__content {\n    grid-area: content;\n");
        break;
        case 'empty':
            print("#dokuwiki__site div.grid-empty {\n    grid-area: empty;\n");
        break;
        case 'search':
            print("#dokuwiki__site div.search {\n    grid-area: search;\n");
        break;
        case 'footer':
            print("#dokuwiki__site #dokuwiki__footer {\n    grid-area: footer;\n");
        break;
        case 'scroll-up-area':
            print("#dokuwiki__site div.scroll_up_area {\n    grid-area: scroll-up-area;\n");
        break;
        case 'scroll-down-area':
            print("#dokuwiki__site div.scroll_down_area {\n    grid-area: scroll-down-area;\n");
        break;

        default:
            if ($params['id'] != 'default') {
                print("#dokuwiki__site div.".$item." {\n    grid-area: ".$item.";\n");
            } else {
                print('<!-- INVALID: div.'.$item.' { grid-area: '.$item.'; } -->');
            }
        break;
    }
    if (!empty($css_props)) {
        print($css_props);
    }
    print("}\n");

    // Also add css props for included items or pages
    $todo = array();
    if (is_array($params['items'])) {
        $todo = array_merge($todo, $params['items']);
    } else if (is_array($params['pages'])) {
        $todo = array_merge($todo, $params['pages']);
    }
    foreach ($todo as $name) {
        $params = tpl_get_cell_params($layout, $name);
        $css_props = tpl_get_css_props($params);
        if (!empty($css_props)) {
            print("#dokuwiki__site div.".$name." {\n".$css_props."}\n");
        }
    }
}
/**
 * Print the main grid definition.
 * 
 * @param array $layout Layout to use
 */
function tpl_print_grid(array $layout) {
    $max_rows = count($layout['grid']);
    $total_vert_space = $layout['grid-vert-space'];
    if ($max_rows > 1) {
        $vert_space = round($total_vert_space / ($max_rows - 1));
        $row_size = round((100 - $total_vert_space) / $max_rows);
    } else {
        $vert_space = 0;
        $row_size = 100;
    }

    $max_columns = count($layout['grid'][0]);
    $total_horiz_space = 10;
    if ($max_columns > 1) {
        $horiz_space = round($total_horiz_space / ($max_columns - 1));
        $column_size = round((100 - $total_horiz_space) / $max_columns);
    } else {
        $horiz_space = 0;
        $column_size = 100;
    }

    print("<style>\n");
    print("#dokuwiki__site {\n");
    print("    display: grid;\n");
    print("    height: ".$layout['height'].";\n");
    print("    position: relative;\n");
    print("    top: ".$layout['top'].";\n");
    print("    margin: 0 auto;\n");
    print("    grid-column-gap: ".$horiz_space."%;\n");
    print("    grid-template-columns:");
    for ($column = 0 ; $column < $max_columns ; $column++) {
        print(" ".$column_size."%");
    }
    print(";\n");
    print("    grid-template-rows:");
    for ($row = 0 ; $row < $max_rows ; $row++) {
        print(" ".$row_size."%");
    }
    print(";\n");
    print("    grid-row-gap: ".$vert_space."%;\n");
    print("    justify-content: center;\n");
    print("    grid-template-areas:\n");

    for ($row = 0 ; $row < count($layout['grid']) ; $row++) {
        print("        '");
        for ($column = 0 ; $column < count($layout['grid'][$row]) ; $column++) {
            $item = $layout['grid'][$row][$column];
            if ($item != 'empty') {
                print($item);
            } else {
                print('.');
            }
            if ($column+1 < count($layout['grid'][$row])) {
                print(' ');
            }
        }
        if ($row+1 < count($layout['grid'])) {
            print("'\n");
        } else {
            print("';\n");
        }
    }
    print("    }\n");

    $done = array();
    for ($row = 0 ; $row < count($layout['grid']) ; $row++) {
        foreach ($layout['grid'][$row] as $item) {
            if (array_search($item, $done) === false) {
                $done[] = $item;
                tpl_print_grid_area($layout, $item);
            }
        }
    }

    print("</style>\n");
    tpl_flush();
}
/**
 * Print the search form
 *
 * If the first parameter is given a div with the ID 'qsearch_out' will
 * be added which instructs the ajax pagequicksearch to kick in and place
 * its output into this div. The second parameter controls the propritary
 * attribute autocomplete. If set to false this attribute will be set with an
 * value of "off" to instruct the browser to disable it's own built in
 * autocompletion feature (MSIE and Firefox)
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 *
 * @param bool $ajax
 * @param bool $autocomplete
 * @param bool $button
 * @return bool
 */
function tpl_congrid_searchform($ajax = true, $autocomplete = true, $button = true) {
    global $lang;
    global $ACT;
    global $QUERY;
    global $ID;

    // don't print the search form if search action has been disabled
    if(!actionOK('search')) return false;

    $searchForm = new dokuwiki\Form\Form([
        'action' => wl(),
        'method' => 'get',
        'role' => 'search',
        'class' => 'search',
        'id' => 'dw__search',
    ], true);
    $searchForm->addTagOpen('div')->addClass('no');
    $searchForm->setHiddenField('do', 'search');
    $searchForm->setHiddenField('id', $ID);
    $searchForm->addTextInput('q')
        ->addClass('edit')
        ->attrs([
            'title' => '[F]',
            'accesskey' => 'f',
            'placeholder' => $lang['btn_search'],
            'autocomplete' => $autocomplete ? 'on' : 'off',
        ])
        ->id('qsearch__in')
        ->val($ACT === 'search' ? $QUERY : '')
        ->useInput(false)
    ;
    if ($button) {
        $searchForm->addButton('', $lang['btn_search'])->attrs([
            'type' => 'submit',
            'title' => $lang['btn_search'],
        ]);
    }
    if ($ajax) {
        $searchForm->addTagOpen('div')->id('qsearch__out')->addClass('ajax_qsearch JSpopup');
        $searchForm->addTagClose('div');
    }
    $searchForm->addTagClose('div');
    trigger_event('FORM_QUICKSEARCH_OUTPUT', $searchForm);

    echo $searchForm->toHTML();

    return true;
}
/**
 * Print a div/cell's content of the grid.
 * 
 * This prints the surrounding div and then calls the appropriate
 * function to generate the content.
 * 
 * @param array  $layout Layout to use
 * @param string $type   Type or name to render
 * @param string $params Cell params to use
 * @param $level FIXME: unused for now? Remove it?
 */
function tpl_generate_div(array &$layout, $type, array $params, $level=1) {
    $divclass = '';

    if ($params['flex']['direction'] == 'column') {
        $divclass .= 'flex-column ';
        if ($params['flex']['mode'] == 'same-size') {
            $divclass .= ' same_height ';
        }
    } else if ($params['flex']['direction'] == 'row') {
        $divclass .= ' flex-row ';
        if ($params['flex']['mode'] == 'same-size') {
            $divclass .= ' same_width ';
        }
    }

    // Assign class for item
    $scroll = '';
    $invalid = false;
    $item_type = TEMPLATE_KNOWN_TYPE;
    switch ($type)
    {
        case 'title':
            $divclass .= 'dokuwiki__title';
        break;

        case 'tagline':
            $divclass .= 'dokuwiki__tagline';
        break;

        case 'toc':
            $divclass .= 'dokuwiki__toc';
        break;

        case 'content':
            $divclass .= 'dokuwiki__content';
        break;

        case 'space':
            $divclass .= 'grid-empty';
        break;

        case 'trace':
            $divclass .= 'trace';
        break;

        case 'youarehere':
            $divclass .= 'youarehere';
        break;

        case 'sitetools':
            $divclass .= 'dokuwiki__sitetools toolslist';
        break;

        case 'usertools':
            $divclass .= 'dokuwiki__usertools toolslist';
        break;

        case 'pagetools':
            $divclass .= 'dokuwiki__pagetools toolslist';
        break;

        case 'scroll-up-area':
            $divclass .= 'scroll_up_area';
            $scroll = ' onmouseover="scroll_up();" onmouseout="stop_scroll();"';
        break;

        case 'scroll-down-area':
            $divclass .= 'scroll_down_area';
            $scroll = ' onmouseover="scroll_down();" onmouseout="stop_scroll();"';
        break;

        case 'search':
            $divclass .= 'search';
        break;

        default:
            if ($params['id'] == 'default' && empty($params['items']) &&
                empty($params['pages'])) {
                /* The type is not known and points to the default cell or
                   it points do a different cell but it does not define
                   items or pages to include. */
                $item_type = TEMPLATE_INVALID_TYPE;
                $divclass .= 'grid-invalid';
            } else {
                if (!empty($params['items']) && is_array($params['items'])) {
                    $childs = count($params['items']);
                    $item_type = TEMPLATE_CONTAINER_ITEMS;
                    $divclass .= 'container_items '.$params['id'];
                    $divclass .= ' childs'.$childs;
                } else if (!empty($params['pages']) && is_array($params['pages'])) {
                    $childs = count($params['pages']);
                    $item_type = TEMPLATE_CONTAINER_PAGES;
                    $divclass .= 'container_pages '.$params['id'];
                    $divclass .= ' childs'.$childs;
                }
            }
        break;
    }

    // Assign class for border
    switch ($params['border']) {
        case 'user':
            $divclass .= ' border_TSS';
        break;
        case 'semi-transparent';
            $divclass .= ' border_semi_transparent';
        break;
    }

    // Assign class for corners
    switch ($params['corners']) {
        case 'round':
            $divclass .= ' corners_round';
        break;
    }

    // Remove text or icons (e.g. for pagetools)
    switch ($params['list-type']) {
        case 'no-text':
            $divclass .= ' no_text';
        break;
        case 'no-icons':
            $divclass .= ' no_icons';
        break;
    }

    // Add known background class if set
    if (!empty($params['background'])) {
        $background = tpl_get_background_class($params['background']);
        $divclass .= ' '.$background;
    }

    if ($type == 'content') {
        print('<div id="dokuwiki__content" class="'.$divclass.'">'."\n");
    } else if ($type == 'footer') {
        print('<div id="dokuwiki__footer" class="'.$divclass.'">'."\n");
    } else {
        print('<div class="'.$divclass.'"'.$scroll.'>'."\n");
    }

    switch ($type)
    {
        case 'toc':
            tpl_generate_toc($params['toc-columns']);
        break;

        case 'content':
            $toc = false;
            if (empty($layout['toc']) || $layout['toc'] == 'on-page') {
                $toc = true;
            }
            tpl_generate_content(true, true, $toc);
        break;

        case 'title':
            tpl_generate_title();
        break;

        case 'youarehere':
            tpl_generate_youarehere();
        break;

        case 'trace':
            tpl_generate_trace();
        break;

        case 'tagline':
            tpl_generate_tagline();
        break;

        case 'search':
            tpl_generate_search();
        break;

        case 'sitetools':
            tpl_generate_sitetools();
        break;

        case 'usertools':
            tpl_generate_usertools();
        break;

        case 'pagetools':
            tpl_generate_pagetools();
        break;

        case 'footer':
            tpl_generate_footer();
        break;

        case 'scroll-up-area':
            print('<img src="'.tpl_basedir().'/images/baseline-arrow_upward-24px.svg" alt="Up" />');
        break;

        case 'scroll-down-area':
            print('<img src="'.tpl_basedir().'/images/baseline-arrow_downward-24px.svg" alt="Down" />');
        break;

        case 'space':
        case 'empty':
        case '.':
            print('empty');
        break;

        default:
            switch ($item_type) {
                case TEMPLATE_INVALID_TYPE:
                    print('<div>Invalid cell type ("'.$type.'")</div>');
                    print('<div>Cell id ("'.$params['id'].'")</div>');
                break;
                case TEMPLATE_CONTAINER_ITEMS:
                    foreach ($params['items'] as $item) {
                        $params = tpl_get_cell_params($layout, $item);
                        tpl_generate_div($layout, $item, $params, $level+1);
                    }
                break;
                case TEMPLATE_CONTAINER_PAGES:
                    foreach ($params['pages'] as $page) {
                        $params = tpl_get_cell_params($layout, $page);
                        tpl_generate_page($layout, $page, $params);
                    }
                break;
            }
        break;
    }

    print('</div>'."\n");
}
/**
 * Return params set for @$cell in @$layout.
 * 
 * If no specific params are found for $cell the nthe default params
 * are returned (from the cell with ID 'default').
 * 
 * @param array  $layout Layout to use
 * @param string $cell   Type or name to render
 */
function tpl_get_cell_params(array $layout, $cell) {
    $default = NULL;
    foreach ($layout['cells'] as $params) {
        if ($params['id'] == $cell) {
            // Found params/match
            return $params;
        } else if ($params['id'] == 'default') {
            // Remember default
            $default = $params;
        }
    }

    // No match, return default
    return $default;
}
/**
 * Generate all cells/divs.
 * 
 * @param array  $layout Layout to use
 */
function tpl_generate_grid_cells(array $layout) {
    $done = array();
    for ($row = 0 ; $row < count($layout['grid']) ; $row++) {
        foreach ($layout['grid'][$row] as $item) {
            if (array_search($item, $done) === false) {
                $done[] = $item;
                if ($item != 'empty' && $item != 'space') {
                    $params = tpl_get_cell_params($layout, $item);
                    tpl_generate_div($layout, $item, $params);
                } else {
                    print('<div class="grid-empty"></div>');
                }
            }
        }
    }
}
/**
 * Print class attribute for 'dokuwiki__site'.
 * 
 * @param array $layout Layout to use
 */
function tpl_print_site_class($layout)
{
    $classes = 'dokuwiki';
    if (empty($layout['theme'])) {
        $classes .= ' white';
    } else {
        switch ($layout['theme']) {
            case 'template-style-settings':
                $classes .= ' TSS';
            break;

            case 'white':
                $classes .= ' '.$layout['theme'];
            break;

            default:
                $classes .= ' white';
            break;
        }
    }
    print('class="'.$classes.'"');
}

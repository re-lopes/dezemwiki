<?php
/**
 * DokuWiki Congrid Template
 *
 * @author  LarsDW223
 * @license GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

global $TPL_DEBUG;
$TPL_DEBUG = 'Main: ';

if (!defined('DOKU_INC')) die(); /* must be run from within DokuWiki */

// Include template functions
require_once(dirname(__FILE__).'/tpl_functions.php');

// Include template init code
include_once(dirname(__FILE__).'/tpl_init.php');

header('X-UA-Compatible: IE=edge,chrome=1');

// Get the layout to be used
$layout = tpl_get_layout();
if ($layout === NULL) {
    $TPL_DEBUG .= 'kein layout';
}
tpl_create_grid($layout);
?>
<!DOCTYPE html>
<html lang="<?php echo $conf['lang'] ?>" dir="<?php echo $lang['direction'] ?>" class="no-js">
<head>
    <meta charset="utf-8" />
    <title><?php tpl_pagetitle() ?> [<?php echo strip_tags($conf['title']) ?>]</title>
    <script>(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)</script>
    <?php tpl_metaheaders() ?>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <?php echo tpl_favicon(array('favicon', 'mobile')) ?>
    <?php tpl_includeFile('meta.html') ?>
    <?php tpl_print_grid($layout) ?>
</head>

<?php tpl_print_body($layout) ?>
    <div id="dokuwiki__site" <?php tpl_print_site_class($layout) ?>>
        <?php tpl_generate_grid_cells($layout) ?>
    </div>

    <div class="no"><?php tpl_indexerWebBug() /* provide DokuWiki housekeeping, required in all templates */ ?></div>
    <div id="screen__mode" class="no"></div><?php /* helper to detect CSS media query in script.js */ ?>
</body>
</html>

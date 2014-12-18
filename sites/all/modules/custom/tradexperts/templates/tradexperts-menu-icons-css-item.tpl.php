<?php

/**
 * @file
 *
 * Template file for generating the CSS file used for the menu-items
 */

/**
 * Variables:
 * $mlid
 * $path
 */
?>
li.menu-<?php print $mlid ?> a:before,
a.menu-<?php print $mlid ?>:before {
  background-image: url(<?php print $path ?>);
}

<?php
  if (!empty($path_hover)):
?>
li.menu-<?php print $mlid ?> a:hover:before,
li.menu-<?php print $mlid ?> a.active:before,
a.menu-<?php print $mlid ?>:hover:before,
a.menu-<?php print $mlid ?>.active:before {
  background-image: url(<?php print $path_hover ?>);
}
<?php
  endif;
?>
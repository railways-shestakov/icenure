<?php
// FUNCTIONS

include_once 'php/functions/frontend.php';

// TIMBER

Timber::$dirname = 'templates';

// CONSTANTS

define("DARKMODE", isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] == 'true');
define("ROOT", get_bloginfo('template_url'));
define("THEME", get_template_directory());

define("LANGUAGES", array(
  'ua' => 'ua',
  'en' => 'en'
));
define("LANGUAGE", set_language());

define("DEVICE", get_device());
define("IS_MOBILE", is_mobile(DEVICE));
define("POSTS_NUMBER", IS_MOBILE ? 4 : 10);
?>
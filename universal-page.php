<?php
  /*
    Template Name: Універсальна сторінка
  */

  // GLOBAL VARIABLES
  
  include_once 'variables.php';

  // LOCAL VARIABLES
  
  $context = create_context();
  $template = DEVICE . '/page.twig';
  $sidebar = get_field('sidebar');
  $sidebar_defs = function_exists('get_sidebar_definitions') ? get_sidebar_definitions() : array();

  // Backward compatibility: map old Ukrainian labels to keys
  $label_to_key = array_flip($sidebar_defs);
  if (isset($label_to_key[$sidebar])) {
      $sidebar = $label_to_key[$sidebar];
  }

  // Validate sidebar key
  if (!isset($sidebar_defs[$sidebar])) {
      $sidebar = '';
  }

  // HEADER

  get_header();
?>

<main class='main main-sidebar'>
  <?php
    // PAGE CONTENT
    
    Timber::render($template, $context);

    // SIDEBAR
    
    if(!IS_MOBILE && $sidebar)
      get_sidebar($sidebar);
  ?>
</main>

<?php
  // FOOTER

  get_footer();
?>
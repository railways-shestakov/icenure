<?php
  /*
    Template Name: Методичні праці
  */

  // GLOBAL VARIABLES
  
  include_once 'variables.php';

  // LOCAL VARIABLES
  
  $args = array(
    'numberposts'      => -1,
    'post_type'        => 'methodical_works',
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
    'meta_key'  => 'year',
    'orderby'   => 'meta_value_num',
    'order'     => 'DESC',
  );
  
  $context = create_context($args);
  $context['pagetitle'] = get_translation('Методичні праці', 'Methodical works');
  $template = 'common/methodical-works.twig';

  // HEADER

  get_header();
?>

<main class='main main-sidebar'>
  <?php
    // PAGE CONTENT
  
    Timber::render($template, $context);

    // SIDEBAR

    if(!IS_MOBILE)
      get_sidebar('learning');
  ?>
</main>

<?php
  // FOOTER

  get_footer();
?>
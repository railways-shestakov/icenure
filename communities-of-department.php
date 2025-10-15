<?php
  /*
    Template Name: Спільноти кафедри
  */

  // GLOBAL VARIABLES
  
  include_once 'variables.php';

  // LOCAL VARIABLES
  
  $args = array(
    'numberposts'      => -1,
    'post_type'        => 'communities',
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
  );
  
  $context = create_context($args);
  $context['pagetitle'] = get_translation('Спільноти кафедри', 'Communities of department');
  $template = 'common/communities-of-department.twig';

  // HEADER

  get_header();
?>

<main class='main main-sidebar'>
  <?php
    // PAGE CONTENT
  
    Timber::render($template, $context);

    // SIDEBAR

    if(!IS_MOBILE)
      get_sidebar('about');
  ?>
</main>

<?php
  // FOOTER

  get_footer();
?>
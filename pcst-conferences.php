<?php
  /*
    Template Name: Наукові конференції - PCST
  */

  // GLOBAL VARIABLES
  
  include_once 'variables.php';

  // LOCAL VARIABLES
  
  $args = array(
    'numberposts'      => -1,
    'post_type'        => 'picst_conferences',
    'orderby'   => 'meta_value_num',
    'meta_key' => 'year',
    'order' => 'DESC',
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false
  );
  $context = create_context($args);
  $context['title'] = get_translation('Проведення наукових конференцій', 'Holding scientific conferences');
  $template = 'common/pcst-conferences.twig';

  // HEADER

  get_header();
?>

<main class='main main-sidebar patents'>
  <?php
    // PAGE CONTENT
  
    Timber::render($template, $context);

    // SIDEBAR

    if(!IS_MOBILE)
      get_sidebar('science');
  ?>
</main>

<?php
  // FOOTER

  get_footer();
?>
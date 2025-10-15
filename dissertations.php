<?php
  /*
    Template Name: Захист дисертацій
  */

  // GLOBAL VARIABLES
  
  include_once 'variables.php';

  // LOCAL VARIABLES
  
  $args = array(
    'numberposts'      => -1,
    'post_type'        => 'dissertations',
    'orderby'   => 'meta_value_num',
    'meta_key' => 'year',
    'order' => 'DESC',
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false
  );
  
  $context = create_context($args);
  $context['title'] = get_translation('Захист дисертацій', 'Захист дисертацій');
  $template = 'common/dissertations.twig';

  // HEADER

  get_header();
?>
<script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js'></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css">

<main class='main main-sidebar dissertations'>
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
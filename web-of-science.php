<?php
  /*
    Template Name: Web of Science
  */

  // GLOBAL VARIABLES
  
  include_once 'variables.php';

  // LOCAL VARIABLES

  $sortBy = $_GET['sort-by'];

  switch($sortBy){
    case 'total-publications':
      $sortBy = 'publons_data_publications';
    break;
    case 'total-citations':
      $sortBy = 'publons_data_total_citations';
    break;
    default:
      $sortBy = 'publons_data_h-index';
    break;
  }
  
  $args = array(
    'numberposts'      => -1,
    'post_type'        => ['teachers', 'postgraduates'],
    'meta_key'         => $sortBy,
    'orderby'          => array( 'meta_value_num' => 'DESC' ),
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false
  );
  
  $context = create_context($args);
  $context['title'] = get_translation('Наукометричні показники в НМБ Web of Science', 'Наукометричні показники в НМБ Web of Science');
  $template = 'common/web-of-science.twig';

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
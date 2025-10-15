<?php
  /*
    Template Name: Google scholar
  */

  // GLOBAL VARIABLES
  
  include_once 'variables.php';

  // LOCAL VARIABLES

  $sortBy = $_GET['sort-by'];

  switch($sortBy){
    case 'i10-index':
      $sortBy = 'scholar_data_i10-index';
    break;
    case 'total-citations':
      $sortBy = 'scholar_data_total_citations';
    break;
    default:
      $sortBy = 'scholar_data_h-index';
    break;
  }
  
  $args = array(
    'numberposts'      => -1,
    'post_type'        => ['teachers', 'postgraduates'],
    'meta_key'         => $sortBy,
    'orderby'          => array( 'meta_value_num' => 'DESC' ),
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
    'exclude'      => array(4209, 4217),
    'post__not_in'     => array(4209, 4217),
  );
  
  $context = create_context($args);
  $context['title'] = get_translation('Наукометричні показники в НМБ Google Scholar', 'Наукометричні показники в НМБ Google Scholar');
  $template = 'common/scholar.twig';

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
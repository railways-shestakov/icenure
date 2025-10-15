<?php
  /*
    Template Name: Scopus ХНУРЕ
  */

  // GLOBAL VARIABLES
  
  include_once 'variables.php';

  // LOCAL VARIABLES

  $sortBy = $_GET['sort-by'];

  switch($sortBy){
    case 'total-publications':
      $sortBy = 'scopus_data_total_publications';
    break;
    case 'total-citations':
      $sortBy = 'scopus_data_total_citations';
    break;
    default:
      $sortBy = 'scopus_data_h_index';
    break;
  }
  
  $args = array(
    'numberposts'      => -1,
    'post_type'        => ['nure'],
    'meta_key'         => $sortBy,
    'orderby'          => array( 'meta_value_num' => 'DESC' ),
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false
  );
  
  $context = create_context($args);
  $context['hide_links'] = 1;
  $context['title'] = get_translation('Наукометричні показники ХНУРЕ в НМБ Scopus', 'Наукометричні показники ХНУРЕ в НМБ Scopus');
  $template = 'common/scopus.twig';

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
<?php
  /*
    Template Name: Майстер-класи та зустрічі зі школярами
  */

  // GLOBAL VARIABLES
  
  include_once 'variables.php';

  // LOCAL VARIABLES
  
  $args = array(
    'numberposts'      => POSTS_NUMBER,
    'post_type'        => 'post',
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
    'meta_key'  => 'date',
    'orderby'   => 'meta_value_num',
    'order'     => 'DESC',
    'meta_query' => array(
      array(
        'key'     => 'category',
        'value'   => '"Майстер-класи та зустрічі зі школярами"',
        'compare' => 'LIKE'
      )
    )
  );
  
  $context = create_context($args);
  $context['pagetitle'] = get_translation('Майстер-класи та зустрічі зі школярами', 'Workshops and meetings with students');
  $context['slider'] = true;
  $template = DEVICE . '/news.twig';

  // HEADER

  get_header();
?>

<main class='main main-sidebar main-sidebar--news'>
  <?php
    // PAGE CONTENT
  
    Timber::render($template, $context);

    // SIDEBAR

    if(!IS_MOBILE)
      get_sidebar('news');
  ?>
</main>

<?php
  // FOOTER

  get_footer();
?>
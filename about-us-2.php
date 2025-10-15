<?php
  /*
    Template Name: Про нас пишуть - Дубль
  */

  // GLOBAL VARIABLES
  
  include_once 'variables.php';

  // LOCAL VARIABLES
  
  $args = array(
    'numberposts'      => POSTS_NUMBER,
    'post_type'        => 'post',
    'meta_query' => array(
      array(
        'key'     => 'category',
        'value'   => '"Про нас пишуть"',
        'compare' => 'LIKE'
      )
    ),
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
    'meta_key'  => 'date',
    'orderby'   => 'meta_value_num',
    'order'     => 'DESC',
  );
  
  $context = create_context($args);
  $context['pagetitle'] = get_translation('Про нас пишуть', 'They write about us');
  $template = DEVICE . '/news.twig';

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
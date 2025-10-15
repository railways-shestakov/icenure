<?php
  // GLOBAL VARIABLES
  
  include_once 'variables.php';

  // LOCAL VARIABLES

  $context = create_context();
  $template = 'common/scientific-conference-post.twig';

  $emc_conference_id = get_the_ID();

  $args = array(
    'numberposts'      => -1,
    'post_type'        => 'post',
    'meta_query' => array(
      'relation' => 'AND',
      array(
        'key'     => 'category',
        'value'   => '"МНТК конференції (EMC)"',
        'compare' => 'LIKE'
      ),
      array(
        'key'     => 'mntk_conference',
        'value'   => $emc_conference_id,
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

  $context = create_context();
  $context['news_posts'] = Timber::get_posts($args);

  // HEADER

  get_header();
?>

<main class='main main-sidebar'>
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
<?php
  // GLOBAL VARIABLES
  
  include_once 'variables.php';

  // LOCAL VARIABLES

  $project_id = get_the_ID();

  $args = array(
    'numberposts'      => POSTS_NUMBER,
    'post_type'        => 'post',
    'meta_query' => array(
      'relation' => 'AND',
      array(
        'key'     => 'category',
        'value'   => '"Міжнародне співробітництво"',
        'compare' => 'LIKE'
      ),
      array(
        'key'     => 'project',
        'value'   => $project_id,
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
  $context['project_id'] = $project_id;
  $context['news_posts'] = Timber::get_posts($args);
  $template = 'common/cooperation-news.twig';

  // HEADER

  get_header();
?>

<main class='main'>
  <?php
    // PAGE CONTENT
  
    Timber::render($template, $context);
  ?>
</main>

<?php
  // FOOTER

  get_footer();
?>
<?php
  // GLOBAL VARIABLES
  
  include_once 'variables.php';

  $community_id = get_the_ID();

  $args = array(
    'numberposts'      => POSTS_NUMBER,
    'post_type'        => 'post',
    'meta_query' => array(
      array(
        'key'     => 'community',
        'value'   => $community_id,
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

  // LOCAL VARIABLES

  $context = create_context();
  $context['community_id'] = $community_id;
  $context['news_posts'] = Timber::get_posts($args);
  $template = 'common/communities-post.twig';

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
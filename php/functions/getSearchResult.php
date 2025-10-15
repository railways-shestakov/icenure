<?php
$dir = $_SERVER['DOCUMENT_ROOT'];
require_once( $dir . '/wp-content/plugins/timber-library/vendor/autoload.php' );
require_once( $dir . '/wp-load.php' );

include_once '../../variables.php';

$search_query = esc_sql($_POST['value']);
$search_query = strip_tags($search_query);
$search_query = htmlentities($search_query, ENT_QUOTES, "UTF-8");
$search_query = htmlspecialchars($search_query, ENT_QUOTES);

$args = array(
  'numberposts'      => POSTS_NUMBER,
  'post_type'        => 'post',
  'meta_query' => array(
    'relation' => 'OR',
    array(
      'key'     => 'title_ua',
      'value'   => $search_query,
      'compare' => 'LIKE'
    ),
    array(
      'key'     => 'title_en',
      'value'   => $search_query,
      'compare' => 'LIKE'
    ),
    array(
      'key'     => 'text_ua',
      'value'   => $search_query,
      'compare' => 'LIKE'
    ),
    array(
      'key'     => 'text_en',
      'value'   => $search_query,
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
$context['query'] = $search_query;
$template = 'common/search-result.twig';

Timber::render($template, $context);
?>
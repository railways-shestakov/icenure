<?php
  /*
    Template Name: Новини
  */

  // GLOBAL VARIABLES
  
  include_once 'variables.php';

  // LOCAL VARIABLES

  $mainNewsArgs = array(
    'numberposts'      => 1,
    'post_type'        => 'post',
    'meta_query' => array(
      array(
        'key'     => 'category',
        'value'   => '"Головна новина"',
        'compare' => 'LIKE'
      )
    ),
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
  );
  
  $args = array(
    'numberposts'      => POSTS_NUMBER,
    'post_type'        => 'post',
    'meta_query' => array(
      'relation' => 'AND',
      array(
        'key'     => 'category',
        'value'   => '"Новини"',
        'compare' => 'LIKE'
      ),
      array(
        'key'     => 'category',
        'value'   => '"Головна новина"',
        'compare' => 'NOT LIKE'
      ),
      array(
        'key'     => 'category',
        'value'   => '"Про нас пишуть"',
        'compare' => 'NOT LIKE'
      )
    ),
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
    'meta_key'  => 'date',
    'orderby'  => array( 'meta_value_num' => 'DESC', 'title' => 'ASC' )
  );
  
  $booksArgs = array(
    'numberposts'      => -1,
    'post_type'        => ['books'],
    'order'			   => 'ASC',
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false
  );

  $monographsArray = array(
    'numberposts'      => -1,
    'post_type'        => ['monographs'],
    'order'			   => 'ASC',
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false
  );

  $books = Timber::get_posts($booksArgs);
  $monographs = Timber::get_posts($monographsArray);

  foreach ($books as &$value) {
    $value->isBook = true;
  }

  foreach ($monographs as &$value) {
    $value->isBook = false;
  }

  $array = array_merge($books, $monographs);

  usort($array, function($first, $second){
    return $first->year < $second->year;
  });
  
  $context = create_context($args);
  $context['main_news'] = Timber::get_posts($mainNewsArgs);
  $context['books'] = $array;
  $context['pagetitle'] = get_translation('Новини', 'News');
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
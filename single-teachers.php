<?php
  // GLOBAL VARIABLES
  
  include_once 'variables.php';

  // LOCAL VARIABLES

  $id = get_the_id();

  $context = create_context();
  $publications = array(
    'numberposts'      => -1,
    'post_type'        => 'articles_and_reports',
    'orderby'   => 'meta_value_num',
    'meta_query' => array(
      array(
        'key'     => 'authors-relationship',
        'value'   => '"' . $id . '"',
        'compare' => 'LIKE'
      )
    ),
    'meta_key' => 'year',
    'order' => 'DESC',
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false
  );
  $context['publications'] = Timber::get_posts($publications);
  $books = array(
    'numberposts'      => -1,
    'post_type'        => 'books',
    'meta_query' => array(
      array(
        'key'     => 'authors-relationship',
        'value'   => '"' . $id . '"',
        'compare' => 'LIKE'
      )
    ),
    'order' => 'DESC',
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false
  );
  $context['books'] = Timber::get_posts($books);
  $context['monographs'] = get_field("monographs", 2128);
  $context['research'] = get_field("works", 52);
  $patents = array(
    'numberposts'      => -1,
    'post_type'        => 'patents_content',
    'meta_query' => array(
      array(
        'key'     => 'authors-relationship',
        'value'   => '"' . $id . '"',
        'compare' => 'LIKE'
      )
    ),
    'orderby'   => 'meta_value_num',
    'meta_key' => 'year',
    'order' => 'DESC',
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false
  );
  $context['patents'] = Timber::get_posts($patents);
  $methodical_works = array(
    'numberposts'      => -1,
    'post_type'        => 'methodical_works',
    'meta_query' => array(
      array(
        'key'     => 'authors-relationship',
        'value'   => '"' . $id . '"',
        'compare' => 'LIKE'
      )
    ),
    'orderby'   => 'meta_value_num',
    'meta_key' => 'year',
    'order' => 'DESC',
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false
  );
  $context['methodical_works'] = Timber::get_posts($methodical_works);
/*   $bases = array(
    'numberposts'      => -1,
    'post_type'        => 'bases',
    'meta_query' => array(
      array(
        'key'     => 'staff_$_id',
        'value'   => $id,
        'compare' => 'LIKE'
      )
    ),
    'order' => 'DESC',
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false
  );
  $context['bases'] = Timber::get_posts($bases); */
  $template = 'common/teachers-post.twig';

  // HEADER

  get_header();
?>

<!-- <script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js'></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css"> -->

<main class='main'>
  <?php
    // SIDEBAR

    // PAGE CONTENT
  
    Timber::render($template, $context);
  ?>
</main>

<?php
  // FOOTER

  get_footer();
?>
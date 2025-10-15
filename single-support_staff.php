<?php
  // GLOBAL VARIABLES
  
  include_once 'variables.php';

  // LOCAL VARIABLES

  $id = get_the_id();

  $context = create_context();
  /* $bases = array(
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
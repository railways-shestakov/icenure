<?php
  /*
    Template Name: Відмінники кафедри
  */

  // GLOBAL VARIABLES
  
  include_once 'variables.php';

  // LOCAL VARIABLES

  $sortBy = $_GET['sort-by'];

  switch($sortBy){
    case 'min-mark':
      $sortBy = 'min_mark';
    break;
    case 'max-mark':
      $sortBy = 'max_mark';
    break;
    case 'articles':
      $sortBy = 'articles';
    break;
    case 'reports':
      $sortBy = 'reports';
    break;
    default:
      $sortBy = 'average_mark';
    break;
  }

  $args = array(
    'numberposts'      => -1,
    'post_type'        => ['best_students'],
    'meta_key'         => $sortBy,
    'orderby'          => array( 'meta_value_num' => 'DESC' ),
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
  );

  $sessionsArgs = array(
    'numberposts'      => -1,
    'post_type'        => 'session',
    'order'			   => 'ASC',
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false
  );
  
  $context = create_context($args);
  $context['sessions'] = Timber::get_posts($sessionsArgs);
  $context['session_id'] = $_GET['session_id'];
  $context['sort_by'] = $sortBy;
  $context['title'] = get_translation('Відмінники кафедри*', 'Distinguished students*');
  $template = 'common/best-students.twig';

  // HEADER

  get_header();
?>

<script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js'></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css">
<script src='https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js'></script>

<main class='main main-sidebar'>
  <?php
    // PAGE CONTENT
  
    Timber::render($template, $context);

    // SIDEBAR

    if(!IS_MOBILE)
      get_sidebar('students');
  ?>
</main>

<?php
  // FOOTER

  get_footer();
?>
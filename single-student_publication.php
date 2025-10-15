<?php
  // GLOBAL VARIABLES
  
  include_once 'variables.php';

  // LOCAL VARIABLES

  $context = create_context();
  $template = 'common/student-publication-post.twig';

  // HEADER

  get_header();
?>

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
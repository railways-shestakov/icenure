<?php
  /*
    Template Name: Тематика кваліфікаційних робіт
  */

  // GLOBAL VARIABLES
  
  include_once 'variables.php';

  // LOCAL VARIABLES

  $args = array(
    'numberposts'      => -1,
    'post_type'        => ['subjects_of_works'],
    'orderby'          => array( 'meta_value_num' => 'DESC' ),
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
  );

  $context = create_context($args);
  $context['title'] = get_translation('Тематика кваліфікаційних робіт', 'Subjects of qualification works');
  $template = 'common/subject-of-works.twig';

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
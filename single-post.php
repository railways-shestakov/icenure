<?php
  // GLOBAL VARIABLES
  
  include_once 'variables.php';

  // LOCAL VARIABLES

  $context = create_context();
  $template = DEVICE . '/news-post.twig';
  $eng_title = $context['posts'][0]->custom["title_en"];
  if(LANGUAGE == 'en') $_POST['pagetitle'] = $eng_title;

  // HEADER

  get_header();
?>

<main class='main'>
  <?php
    // SIDEBAR

    //if(!MOBILE)
    //  get_sidebar();

    // PAGE CONTENT
  
    Timber::render($template, $context);
  ?>
</main>

<?php
  // FOOTER

  get_footer();
?>
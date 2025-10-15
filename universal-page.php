<?php
  /*
    Template Name: Універсальна сторінка
  */

  // GLOBAL VARIABLES
  
  include_once 'variables.php';

  // LOCAL VARIABLES
  
  $context = create_context();
  $template = DEVICE . '/page.twig';
  $sidebar = get_field('sidebar');

  switch($sidebar){
    case 'Про кафедру';
      $sidebar = 'about';  
    break;
    case 'Освітній процес';
      $sidebar = 'learning';  
    break;
    case 'Наукова робота';
      $sidebar = 'science';  
    break;
    case 'Студентська робота';
      $sidebar = 'students';  
    break;
    case 'Абітурієнту';
      $sidebar = 'entrants';  
    break;
    default:
      $sidebar = '';  
    break;
  }

  // HEADER

  get_header();
?>

<main class='main main-sidebar'>
  <?php
    // PAGE CONTENT
    
    Timber::render($template, $context);

    // SIDEBAR
    
    if(!IS_MOBILE && $sidebar)
      get_sidebar($sidebar);
  ?>
</main>

<?php
  // FOOTER

  get_footer();
?>
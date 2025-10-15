<?php
  // GLOBAL VARIABLES

  include_once 'variables.php';
  
  // LOCAL VARIABLES

  //$darkMode = DARKMODE ? ' class="dark"' : '';
  $darkMode = '';
  $pageTitle = $_POST['pagetitle'] ? $_POST['pagetitle'] : wp_get_document_title('');
  $favicon = ROOT . '/images/logo_new.svg';
  $css = ROOT . '/css/' . DEVICE . '.css?h=' . rand(0,9) . rand(0,9) . rand(0,9);
  $canonical = $_POST['canonical'];

  // RENDER

  $context = create_context();
  $template = DEVICE . '/header.twig';
?>
<!doctype html>
<html lang="<?=LANGUAGE?>">
<head>
  <link rel="dns-prefetch" href="//fonts.googleapis.com">
  <link rel="dns-prefetch" href="//www.google-analytics.com">
  <?=$canonical ? '<link rel="canonical" href="' . $canonical . '">' : ''?>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap&subset=cyrillic,cyrillic-ext" rel="stylesheet">
  <title>
    <?=$pageTitle?>
  </title>
  <meta charset="UTF-8">
  <meta property="og:image" content="">
	<meta property="og:title" content="Кафедра інфокомунікаційної інженерії імені В.В. Поповського — ХНУРЕ">
	<meta property="og:description" content="Кібербезпека та телекомунікаційна інженерія">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="<?=$favicon?>">
  <link rel='stylesheet' href='<?=$css?>'>
  <link rel='stylesheet' href='<?=ROOT?>/css/gallery.css'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css">

  <?php
    if( is_user_logged_in() ) wp_head();
  ?>

  <!-- Global site tag (gtag.js) - Google Analytics 
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-129319818-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-129319818-1');
    OLD
  </script>-->

  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-L3BSE8YZFS"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-L3BSE8YZFS');
  </script>
  <?php //do_action( 'wp_head' ) ?>
</head>
<body <?=$darkMode?> data-mobile='<?= DEVICE == "mobile" ? 1 : 0 ?>' <?php body_class(); ?>>
<div class="scrollable-logo"></div>
<?php
  Timber::render($template, $context);
?>
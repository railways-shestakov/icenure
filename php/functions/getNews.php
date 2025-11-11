<?php
$dir = $_SERVER['DOCUMENT_ROOT'];
require_once( $dir . '/wp-content/plugins/timber-library/vendor/autoload.php' );
require_once( $dir . '/wp-load.php' );

$isEnglish = (int) $_POST['isEnglish'];
$_POST['language'] = $isEnglish ? "en" : "ua";

include_once '../../variables.php';

$offset = (int) $_POST['offset'];
$mobile = (int) $_POST['mobile'];
$postsNumber = (int) $_POST['postsNumber'];
$category = '';
$categoryInt = (int) $_POST['category'];
$project_id = (int) $_POST['project_id'];
$community_id = (int) $_POST['community_id'];

switch($categoryInt){
  case 0:
  default:
    $category = "Новини";
  break;
  case 1:
    $category = "Участь у конференціях";
  break;
  case 2:
    $category = "Міжнародне співробітництво";
  break;
  case 3:
    $category = "Майстер-класи та зустрічі зі школярами";
  break;
  case 4:
    $category = "Спортивне життя кафедри";
  break;
  case 5:
    $category = "Розвиток навчальних дисциплін";
  break;
  case 6:
    $category = "Монографії";
  break;
  case 7:
    $category = "Навчальні та наукові досягнення";
  break;
  case 8:
    $category = "Спортивні та мистецькі досягнення";
  break;
  case 9:
    $category = "Здобутки кафедри";
  break;
  case 10:
    $category = "Міжнародне співробітництво";
  break;
  case 11:
    $category = "Міжнародне співробітництво";
  break;
  case 12:
    $category = "Про нас пишуть";
  break;
  case 13:
    $category = "Програми обміну та подвійних дипломів";
  break;
  case 14:
    $category = "Екскурсії та подорожі студентів";
  break;
  case 15:
    $category = "Зустріч з випускниками";
  break;
  case 16:
    $category = "Спільноти";
  break;
  case 17:
    $category = "МНКТ конференція (EMC)";
  break;
};

$args = array(
  'numberposts'	      => $postsNumber,
  'offset'            => $offset,
  'post_type'         => 'post',
  'meta_query' => array(
    'relation' => 'AND',
    array(
      'key'     => 'category',
      'value'   => '"' . $category . '"',
      'compare' => 'LIKE'
    )
  ),
  'no_found_rows'          => true,
  'update_post_meta_cache' => false,
  'update_post_term_cache' => false,
  'meta_key'  => 'date',
  'orderby'   => 'meta_value_num',
  'order'     => 'DESC',
);

if($category == "Новини"){
  $args = array(
    'numberposts'	      => $postsNumber,
    'offset'            => $offset,
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
      )
    ),
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
    'meta_key'  => 'date',
    'orderby'  => array( 'meta_value_num' => 'DESC', 'title' => 'ASC' )
  );
}

if($categoryInt == 10){ // /about/international-cooperation
  $args = array(
    'numberposts'	      => $postsNumber,
    'offset'            => $offset,
    'post_type'         => 'post',
    'meta_query' => array(
      'relation' => 'AND',
      array(
        'key'     => 'category',
        'value'   => '"' . $category . '"',
        'compare' => 'LIKE'
      ),
      array(
        'key'     => 'project',
        'compare' => 'NOT EXISTS'
      )
    ),
    'no_found_rows'          => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
    'meta_key'  => 'date',
    'orderby'   => 'meta_value_num',
    'order'     => 'DESC',
  );
}

if($categoryInt == 11){ // /international-cooperation page
  $args = array(
    'numberposts'	      => $postsNumber,
    'offset'            => $offset,
    'post_type'         => 'post',
    'meta_query' => array(
      'relation' => 'AND',
      array(
        'key'     => 'category',
        'value'   => '"' . $category . '"',
        'compare' => 'LIKE'
      ),
      array(
        'key'     => 'project',
        'value'   => $project_id,
        'compare' => 'LIKE'
      )
    ),
    'no_found_rows'          => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
    'meta_key'  => 'date',
    'orderby'   => 'meta_value_num',
    'order'     => 'DESC',
  );
}

if($categoryInt == 16){
  $args = array(
    'numberposts'	      => $postsNumber,
    'offset'            => $offset,
    'post_type'         => 'post',
    'meta_query' => array(
      array(
        'key'     => 'community',
        'value'   => $community_id,
        'compare' => 'LIKE'
      )
    ),
    'no_found_rows'          => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
    'meta_key'  => 'date',
    'orderby'   => 'meta_value_num',
    'order'     => 'DESC',
  );
}

$context = create_context($args);
$template = ($mobile ? 'mobile/' : 'desktop/') . 'news-load.twig';
Timber::render($template, $context);
?>
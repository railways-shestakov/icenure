<?php
  /*
    Template Name: Міжнародні проєкти кафедри - Дубль
  */

  // GLOBAL VARIABLES
  
  include_once 'variables.php';

  // LOCAL VARIABLES
  
  $args = array(
    'numberposts'      => POSTS_NUMBER,
    'post_type'        => 'post',
    'meta_query' => array(
      'relation' => 'AND',
      array(
        'key'     => 'category',
        'value'   => '"Міжнародне співробітництво"',
        'compare' => 'LIKE'
      ),
      array(
            'relation' => 'OR',
            array(
                'key'     => 'project',
                'value'   => '',
                'compare' => '='
            ),
            array(
                'key'     => 'project',
                'compare' => 'NOT EXISTS'
            )
      )
    ),
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
    'meta_key'  => 'date',
    'orderby'   => 'meta_value_num',
    'order'     => 'DESC',
  );
  
  $cooperation = array(
    'numberposts'      => -1,
    'post_type'        => 'cooperation',
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false
  );
  
  $context = create_context($args);
  $cooperation = get_posts($cooperation);
  
  $ids = array();
  $dates = array();
  global $post;

  foreach ( $cooperation as $post ){
    setup_postdata($post);
    $project_id = get_the_ID();
    $ids[] = $project_id;
  }

  foreach ( $ids as $id ){
    $get_date = array(
      'numberposts'      => 1,
      'post_type'        => 'post',
      'meta_query' => array(
        array(
          'key'     => 'project',
          'value'   => $id,
          'compare' => 'LIKE'
        )
      ),
      'no_found_rows' => true,
      'update_post_meta_cache' => false,
      'update_post_term_cache' => false,
      'meta_key'  => 'date',
      'orderby'   => 'meta_value_num',
      'order'     => 'DESC',
    );
    $posts = get_posts($get_date);

    if(!empty($posts)){
      foreach($posts as $post){
        setup_postdata($post);
        $post_id = get_the_ID();
        $date = get_field("date", $post_id);
        $dates[] = $date;
      }
    }else{
      $dates[] = '';
    }
  }

  $l = count($cooperation);

  for($i = 0; $i < $l; $i++){
    for($j = 0; $j < $l; $j++){
      $date1 = $dates[$i];
      $date2 = $dates[$j];

      $dateTimestamp1 = $date1 ? strtotime($date1) : 0; 
      $dateTimestamp2 = $date2 ? strtotime($date2) : 0; 

      if($dateTimestamp1 > $dateTimestamp2){
        $c = $cooperation[$j];
        $cooperation[$j] = $cooperation[$i];
        $cooperation[$i] = $c;
        $c = $dates[$j];
        $dates[$j] = $dates[$i];
        $dates[$i] = $c;
      }
    }
  }

  $context['cooperation'] = $cooperation;

  $context['IS_MOBILE'] = IS_MOBILE;
  $context['pagetitle'] = get_translation('Міжнародні проєкти кафедри', 'International projects of the department');
  $template = 'common/cooperation.twig';

  // HEADER

  $_POST['canonical'] = "http://ice.nure.ua/ua/international-cooperation/";

  get_header();
?>

<main class='main main-sidebar'>
  <?php
    // PAGE CONTENT
  
    Timber::render($template, $context);
  ?>
</main>

<?php
  // FOOTER

  get_footer();
?>
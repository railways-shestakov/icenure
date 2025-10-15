<?php
ini_set('max_execution_time', 0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
global $wpdb;
function delete_custom_posts($post_type = 'post'){
    global $wpdb;
    $result = $wpdb->query( 
        $wpdb->prepare("
            DELETE posts,pt,pm
            FROM nure_posts posts
            LEFT JOIN nure_term_relationships pt ON pt.object_id = posts.ID
            LEFT JOIN nure_postmeta pm ON pm.post_id = posts.ID
            WHERE posts.post_type = %s
            ", 
            $post_type
        ) 
    );
    return $result!==false;
}
exit;
//delete_custom_posts('articles_and_reports');
//exit;

$args = array(
  'numberposts'	=> -1,
  'posts_per_page' => -1,
  'post_type'   => ['publication'],
  'no_found_rows' => true,
  'update_post_meta_cache' => false,
  'update_post_term_cache' => false
);
  
$query = new WP_Query;
$publications = $query->query($args);
date_default_timezone_set('Europe/Kiev');
global $post;

function add_post($array){
    $post_information = array(
        'post_title' => $array['text'],
        'post_content' => '',
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'articles_and_reports',
    );

    $post_id = wp_insert_post( $post_information );
    update_field('authors', $array['authors'], $post_id);
    update_field('authors-relationship', $array['authors-relationship'], $post_id);
    update_field('text', $array['text'], $post_id);
    update_field('scopus', $array['scopus'], $post_id);
    update_field('wos', $array['wos'], $post_id);
    update_field('doi', $array['doi'], $post_id);
    update_field('quartile', $array['quartile'], $post_id);
    update_field('year', $array['year'], $post_id);
    update_field('type', $array['type'], $post_id);
}

foreach($publications as $post){
    setup_postdata($post);

    $year = get_field('year');

    while( have_rows('articles') ) : the_row();
        $array = array();
        $array['authors'] = get_sub_field('authors');
        $array['authors-relationship'] = get_sub_field('authors-relationship');
        $array['text'] = get_sub_field('text');
        $array['scopus'] = get_sub_field('scopus');
        $array['wos'] = get_sub_field('wos');
        $array['doi'] = get_sub_field('doi');
        $array['quartile'] = get_sub_field('quartile');
        $array['year'] = $year;
        $array['type'] = 'Стаття';
        add_post($array);
    endwhile;

    while( have_rows('reports') ) : the_row();
        $array = array();
        $array['authors'] = get_sub_field('authors');
        $array['authors-relationship'] = get_sub_field('authors-relationship');
        $array['text'] = get_sub_field('text');
        $array['scopus'] = get_sub_field('scopus');
        $array['wos'] = get_sub_field('wos');
        $array['doi'] = get_sub_field('doi');
        $array['quartile'] = get_sub_field('quartile');
        $array['year'] = $year;
        $array['type'] = 'Доповідь';
        add_post($array);
    endwhile;

    wp_reset_postdata();
}
?>
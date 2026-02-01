<?php
function admin_bar(){
  if(is_user_logged_in()){
    show_admin_bar(true);
    add_filter( 'show_admin_bar', '__return_true' , 1000 );
  }
}
add_action('init', 'admin_bar' );

// ACF Slider Options Page
include_once get_template_directory() . '/php/functions/acf-slider.php';

add_action( 'wp_before_admin_bar_render', function() {
  global $wp_admin_bar;
  $wp_admin_bar->remove_menu('trp_edit_translation');
  $wp_admin_bar->remove_menu('customize');
  $wp_admin_bar->remove_menu('updates');
} );

add_action('admin_menu', 'remove_menus', 999);
add_action( 'send_headers', 'send_frame_options_header', 10, 0 );
add_action('wp', 'jltwp_adminify_remove_powered');
function jltwp_adminify_remove_powered()
{
    if (function_exists('header_remove')) {
        header_remove('x-powered-by');
    }
}

function remove_menus(){
  global $menu;
  
	$restricted = array(
	  //__('Settings'),
		__('Comments'),
		//__('Plugins')
  );

  $minus_menu = array(
    //'index.php',
    'users.php',
    //'tools.php',
    //'themes.php',
    //'edit.php?post_type=acf-field-group',
    //'wck-page',
    //'Wordfence',
    //'kvcodes'
  );

  foreach($minus_menu as $str){
    remove_menu_page($str);
  }
  
  end($menu);
  
	while (prev($menu)){
		$value = explode(' ', $menu[key($menu)][0]);
		if( in_array( ($value[0] != NULL ? $value[0] : "") , $restricted ) ){
			unset($menu[key($menu)]);
		}
	}
};

register_nav_menus(array(
  'primary' => __('Primary Menu', 'shotutanado')
));
add_theme_support('post-thumbnails');
set_post_thumbnail_size(500, 500, false);

add_filter('excerpt_more', function($more) {
	return '';
});

function change_post_menu_label() {
  global $menu, $submenu;
  $menu[5][0] = 'Новини';
  $submenu['edit.php'][5][0] = 'Новини';
  $submenu['edit.php'][10][0] = 'Додати новину';
  $submenu['edit.php'][16][0] = 'Новостні мітки';
  echo '';
}

add_action( 'admin_menu', 'change_post_menu_label' );

function change_post_object_label() {
  global $wp_post_types;
  $labels = &$wp_post_types['post']->labels;
  $labels->name = 'Новини';
  $labels->singular_name = 'Новини';
  $labels->add_new = 'Додати новину';
  $labels->add_new_item = 'Додати новину';
  $labels->edit_item = 'Редагувати новину';
  $labels->new_item = 'Додати новину';
  $labels->view_item = 'Переглянути новину';
  $labels->search_items = 'Знайти новину';
  $labels->not_found = 'Не знайдено';
  $labels->not_found_in_trash = 'Кошик порожній';
}

add_action( 'init', 'change_post_object_label' );

function wph_new_toolbar() {
  global $wp_admin_bar;

  $wp_admin_bar->remove_menu('comments');
  $wp_admin_bar->remove_menu('new-content');
  $wp_admin_bar->remove_menu('updates');
  $wp_admin_bar->remove_menu('wp-logo');
  $wp_admin_bar->remove_menu('archive');
  $wp_admin_bar->remove_menu('view');
}

add_action('wp_before_admin_bar_render', 'wph_new_toolbar');
add_theme_support('title-tag');

remove_action( 'load-update-core.php', 'wp_update_plugins' );
wp_clear_scheduled_hook( 'wp_update_plugins' );

function wpb_filter_query( $query, $error = true ) {
  if ( is_search() ) {
    $query->is_search = false;
    if ( $error == true )
      $query->is_404 = true;
  }
}

add_action( 'parse_query', 'wpb_filter_query' );
//add_filter( 'get_search_form', create_function( '$a', "return null;" ) );
add_action('init', 'do_rewrite');

function do_rewrite(){
  flush_rewrite_rules();
  add_rewrite_rule( 'search/([^/]*)/?', 'index.php?page_id=1970&query=$matches[1]', 'top' ); 
  add_rewrite_rule( 'search/?', 'index.php?page_id=1970', 'top' ); 

	add_filter( 'query_vars', function($vars){
    $vars[] = 'query';
		return $vars;
	});
}

function my_posts_where($where){
  $where = str_replace("meta_key = 'staff_$", "meta_key LIKE 'staff_%", $where);

	return $where;
}

add_filter('posts_where', 'my_posts_where');
add_filter('intermediate_image_sizes', 'true_reduce_image_sizes');
 
function true_reduce_image_sizes( $sizes ){
	$type = get_post_type($_REQUEST['post_id']);
 
	foreach( $sizes as $key => $value ){
 
		if( $type == 'page' ) {
			//if( !in_array( $value, array('rta_thumb_no_cropped_x107') ) ){
    	//	unset( $sizes[$key] );
    	//}
		} else if ( $type == 'issues' ) {
			if( !in_array( $value, array('rta_thumb_no_cropped_x600') ) ){
    		unset( $sizes[$key] );
    	}
		} else if($type == 'articles'){
      if( !in_array( $value, array('rta_thumb_no_cropped_120x') ) ){
    		unset( $sizes[$key] );
    	}
    }else if($type == 'authors'){
			if( !in_array( $value, array('rta_thumb_no_cropped_280x', 'rta_thumb_no_cropped_x280') ) ){
    		unset( $sizes[$key] );
    	}
    }else if($type == 'news'){
			//if( !in_array( $value, array('regionfeatured','misha335') ) ){
    	//	unset( $sizes[$key] );
    	//}
		}
	}
	return $sizes;
}

//Define static content version
define('STATIC_VERSION', '1.0.1');

// Remove meta tag in head
remove_action('wp_head', 'wp_generator');
 
/**
 * Change the WP version number for scripts and styles
 *
 * @param string $src
 * @return string
 */
function remove_version_from_style_js( $src ) {
    if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) ) {
        $src = remove_query_arg( 'ver', $src );
        $src = add_query_arg( ['ver' => STATIC_VERSION], $src );
    }
    return $src;
}

add_filter('style_loader_src', 'remove_version_from_style_js', 10000);
add_filter('script_loader_src', 'remove_version_from_style_js', 10000);

function remove_default_image_sizes( $sizes) {
  unset( $sizes['large']);
  unset( $sizes['post-thumbnail']);
  unset( $sizes['thumbnail']);
  unset( $sizes['medium']);
  unset( $sizes['medium_large']);
  unset( $sizes['500x500']);
  unset( $sizes['1536x1536']);
  unset( $sizes['2048x2048']);

  return $sizes;
}

add_filter('manage_'.'articles_and_reports'.'_posts_columns', 'add_art_columns', 4);

function add_art_columns($columns){
	$num = 2;

	$new_columns = array(
    'year' => 'Рік',
    'type' => 'Тип'
	);

	return array_slice( $columns, 0, $num ) + $new_columns + array_slice( $columns, $num );
}

add_action('manage_'.'articles_and_reports'.'_posts_custom_column', 'fill_year_column', 5, 2);

function fill_year_column($colname, $post_id){
  global $post;
  if($colname !== 'year') return;
  
  $year = get_field('year', $post_id);

  echo $year;
}

add_action('manage_'.'articles_and_reports'.'_posts_custom_column', 'fill_type_column', 5, 2);

function fill_type_column($colname, $post_id){
  global $post;
  if($colname !== 'type') return;
  
  $type = get_field('type', $post_id);

  echo $type;
}

add_filter('manage_'.'post'.'_posts_columns', 'add_columns', 4);

function add_columns($columns){
	$num = 2;

	$new_columns = array(
    'category' => 'Категорії новини'
	);

	return array_slice( $columns, 0, $num ) + $new_columns + array_slice( $columns, $num );
}

add_action('manage_'.'post'.'_posts_custom_column', 'fill_category_column', 5, 2);

function fill_category_column($colname, $post_id){
  global $post;
  if($colname !== 'category') return;
  
  $categories = get_field('category', $post_id);

  echo implode(", ", $categories);
}

add_action('restrict_manage_posts', 'custom_filter_for_posts_html');

function custom_filter_for_posts_html($post_type){
  if('post' !== $post_type) return;

  global $post;
  
  $categories = array();
  array_push($categories, '<option value="">Усі категорії</option>');
  $category_filter = isset($_GET['category_filter']) ? $_GET['category_filter'] : null;

  $field = get_field_object('field_5eac5d4cfac78');
  $cats = $field['choices'];

  foreach($cats as $cat){
    array_push($categories, '<option value="' . $cat . '" ' . ($cat == $category_filter ? "selected" : "") . '>' . $cat . '</option>');
  }
  
  echo '<select name="category_filter">' . implode('', $categories) . '</select>';
}

add_filter('request', 'custom_filter_for_posts');
    
function custom_filter_for_posts($vars){
  global $pagenow;
  global $post_type;
  
  $start_in_post_types = array('articles_and_reports');

  if( empty($pagenow) || $pagenow != 'edit.php' || !in_array($post_type, $start_in_post_types) ) return $vars;

  $type_filter = isset($_GET['type_filter']) ? $_GET['type_filter'] : null;

  $queries = array();

  if( !empty($type_filter) ){
    switch($type_filter){
      case "report":
        $type = "Доповідь";
          break;
      case "article":
        $type = "Стаття";
          break;
    }

    array_push($queries, array(
      'key'     => 'type',
      'value'   => $type,
      'compare' => 'LIKE'
    ));

    $vars['meta_query'] = $queries;
  }

  $year_filter = isset($_GET['year_filter']) ? $_GET['year_filter'] : null;

  if( !empty($year_filter) ){
    $year = (int) $year_filter;

    array_push($queries, array(
      'key'     => 'year',
      'value'   => $year,
      'compare' => 'LIKE'
    ));

    $vars['meta_query'] = $queries;
  }
  
  return $vars;
}

add_action('restrict_manage_posts', 'custom_filter_for_posts_html_art_year');

function custom_filter_for_posts_html_art_year($post_type){
  if('articles_and_reports' !== $post_type) return;

  global $post;
  
  $options = array();
  array_push($options, '<option value="">Рік</option>');
  $year_filter = isset($_GET['year_filter']) ? $_GET['year_filter'] : null;

  for($i = 2008; $i <= date('Y'); $i++){
    array_push($options, '<option value="' . $i . '" ' . ($i == $year_filter ? "selected" : "") . '>' . $i . '</option>');
  }
  
  echo '<select name="year_filter">' . implode('', $options) . '</select>';
}

add_action('restrict_manage_posts', 'custom_filter_for_posts_html_art_type');

function custom_filter_for_posts_html_art_type($post_type){
  if('articles_and_reports' !== $post_type) return;

  global $post;
  
  $options = array();
  array_push($options, '<option value="">Тіп</option>');
  $type_filter = isset($_GET['type_filter']) ? $_GET['type_filter'] : null;
  
  echo '<select name="type_filter">
    <option value="">Тип запису</option>  
    <option value="article" ' . ('article' == $type_filter ? "selected" : "") . '>Стаття</option>
    <option value="report" ' . ('report' == $type_filter ? "selected" : "") . '>Доповідь</option>
  </select>';
}
?>
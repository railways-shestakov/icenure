<?php
function create_context(
  $args = null
){
  $context = Timber::context();

  $context['posts'] = $args ? Timber::get_posts($args) : Timber::get_posts();
  $context['dark_mode'] = DARKMODE;
  $context['theme_dir'] = get_root();
  $context['language'] = get_language();

  return $context;
}

function get_root(){
  return ROOT;
}

function set_language(){
  $url = $_SERVER['REQUEST_URI'];

  $language = substr($url, 1, 3) == "en/" ? "en" : "ua"; // DIRECT
  $language = isset($_POST['language']) ? $_POST['language'] : $language; // AJAX

  return $language;
}

function get_language(){
  return LANGUAGE;
}

function get_translation(
  $ua,
  $en
){
  $translation = LANGUAGES['ua'] == LANGUAGE ? $ua : $en;
  $translation = LANGUAGES['ua'] != LANGUAGE && trim($en) == '' ? $ua : $translation;

  return $translation;
}

function cmp($a, $b)
{
    return $a <=> $b;
}

function sortStudents($sortBy, $arr, $val){
  foreach($arr as $key => $value){
    $value->sort = $val["_" . $value->id];
  }

  usort($arr, function($x, $y){
    if($x->sort == $y->sort) return 0;

    return $x->sort < $y->sort ? 1 : -1;
  });

  return $arr;
}

function get_device(){
  include_once THEME . '/php/device/detect.php';
  
  $detect = new Mobile_Detect;
  $device = $detect->isMobile() ? 'mobile' : 'desktop';

  return $device;
}

function is_mobile($device){
  return $device == 'mobile';
}

function get_wordform(
  $num,
  $form_for_1,
  $form_for_2,
  $form_for_5
){
  $num = abs($num) % 100;
  $num_x = $num % 10;

  if ($num > 10 && $num < 20) 
    return $form_for_5;
  if ($num_x > 1 && $num_x < 5)
    return $form_for_2;
  if ($num_x == 1)
    return $form_for_1;

  return $form_for_5;
}

function get_menu(){
  wp_nav_menu(array(
    'menu' => get_translation('menu-ua', 'menu-en'),
    'walker' => new True_Walker_Nav_Menu(),
    'container' => false
  ));
}

/*
* Некоторые из параметров объекта $item
* ID - ID самого элемента меню, а не объекта на который он ссылается
* menu_item_parent - ID родительского элемента меню
* classes - массив классов элемента меню
* post_date - дата добавления
* post_modified - дата последнего изменения
* post_author - ID пользователя, добавившего этот элемент меню
* title - заголовок элемента меню
* url - ссылка
* attr_title - HTML-атрибут title ссылки
* xfn - атрибут rel
* target - атрибут target
* current - равен 1, если является текущим элементом
* current_item_ancestor - равен 1, если текущим (открытым на сайте) является вложенный элемент данного
* current_item_parent - равен 1, если текущим (открытым на сайте) является родительский элемент данного
* menu_order - порядок в меню
* object_id - ID объекта меню
* type - тип объекта меню (таксономия, пост, произвольно)
* object - какая это таксономия / какой тип поста (page /category / post_tag и т д)
* type_label - название данного типа с локализацией (Рубрика, Страница)
* post_parent - ID родительского поста / категории
* post_title - заголовок, который был у поста, когда он был добавлен в меню
* post_name - ярлык, который был у поста при его добавлении в меню
*/

class True_Walker_Nav_Menu extends Walker_Nav_Menu{
  function start_lvl(&$output, $depth = 0, $args = NULL){
  	$output .= '<ul class="menu menu-sublist">';
  }
    
  function start_el( &$output, $item, $depth = 0, $args = NULL, $id = 0 ){
  	global $wp_query;           
  	
  	$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
  
  	$class_names = $value = '';
  	$classes = empty( $item->classes ) ? array() : (array) $item->classes;
  	$classes[] = 'menu-item-' . $item->ID;
  
    $class_names = 'menu__item';
    //echo $item->title; var_dump($item->classes);echo '----';
    $class_names .= in_array("current_page_item", $item->classes)
    || in_array("current-menu-parent", $item->classes)
    || in_array("current-page-ancestor", $item->classes) ? ' menu__item-active' : '';
    $class_names = ' class="' . esc_attr( $class_names ) . '"';
  
  	$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
  	$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

  	$output .= $indent . '<li' . $id . $value . $class_names .'>';
  
  	$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
  	$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
    $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
    $item_url = $item->url;
    $item_url = preg_replace("/learning-process\/$/i", "learning-process/teaching-staff", $item_url);
    $item_url = preg_replace("/scientific-work\/$/i", "scientific-work/scientific-activity", $item_url);
    $item_url = str_replace("students", "educational-and-scientific-achievements", $item_url);
    $item_url = str_replace("entrants", "general-information", $item_url);
    $item_url = str_replace("best-educational-and-scientific-achievements", "best-students", $item_url);
  	$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item_url        ) .'"' : '';
  
  	$item_output = $args->before;
    $item_output .= '<span><a'. $attributes .' class="menu__link"></a>';
    $item_title = str_replace("є", "<span>є</span>", $item->title);
    $item_output .= $args->link_before . apply_filters( 'the_title', $item_title, $item->ID ) . $args->link_after;
    $item_output .= '</span>';
  	$item_output .= $args->after;
  
  	$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
  }
}

function get_more($text){
  $str = strpos($text, "<!--more-->");
  $text = substr($text, 0, $str);
  
  return $text;
}

function get_age($date){
  $date = str_replace(".", "-", $date);

  $d1 = new DateTime('now');
  $d2 = new DateTime($date);

  $diff = $d2->diff($d1);

  return $diff->y;
}
?>
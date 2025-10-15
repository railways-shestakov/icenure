<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('output_buffering', 0);
ini_set('implicit_flush', 1);
ob_implicit_flush(1);

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );

include_once(get_template_directory() . '/php/advanced_html_dom.php');

$args = array(
  'numberposts'	=> -1,
  'posts_per_page' => -1,
  'post_type'   => ['teachers', 'postgraduates', 'nure', 'support_staff', 'department'],
  'no_found_rows' => true,
  'update_post_meta_cache' => false,
  'update_post_term_cache' => false
);

$query = new WP_Query;
$teachers = $query->query($args);
date_default_timezone_set('Europe/Kiev');
$today = date('d.m.Y');
global $post;

echo count($teachers);

//$html = curl_get_contents("https://ieeexplore.ieee.org/rest/author/37089309534", "https://ieeexplore.ieee.org/author/37089309534");
//$json_data = json_decode($html['html']);
//echo var_dump($json_data);

$argsDay = array(
  'numberposts'	=> 1,
  'post_type'   => 'bases',
  'title' => $today,
  'no_found_rows' => true,
  'update_post_meta_cache' => false,
  'update_post_term_cache' => false
);

$query = new WP_Query($argsDay);
$post_id = 0;

if(!$query->have_posts()){
  $post_id = wp_insert_post(array(
    'post_type' => 'bases',
    'post_title' => $today,
    'post_content' => '',
    'post_status' => 'publish',
    'comment_status' => 'closed',
    'ping_status' => 'closed'
  ));
}else{
  while ( $query->have_posts() ) {
    $query->the_post();
    $post_id = get_the_ID();
  }
}

if($post_id == 0) return;

$todayWithHour = date('d.m.Y:H');

$key = "webofscience_sid" . $todayWithHour . "_upd1";

if ( $sid = get_user_meta( 1, $key, true ) ) {
  echo "SID from db:" . $sid . "<br>";
}else{
  $link = "https://api.scrapfly.io/scrape?key=57990a6566b14b19981d12e577ccdef3&url=https%3A%2F%2Fwww.webofscience.com%2Fwos%2Fauthor%2Frecord%2FC-6981-2015&retry=false&tags=player%2Cproject%3Adefault&timeout=90000&render_js=true&js=Y29uc3QgZGl2ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgiZGl2Iik7DQpkaXYuaW5uZXJIVE1MID0gYFNJRD0ke2xvY2FsU3RvcmFnZS5nZXRJdGVtKCJ3b3Nfc2lkIil9YDsNCmRvY3VtZW50LmJvZHkuYXBwZW5kQ2hpbGQoZGl2KTs";
  $html = curl_get_contents($link);
  $html = $html['html'];
  $start = strpos($html, 'SID=');
  $startReal = strpos($html, 'SID=', $start + 4);
  $sid = substr($html, $startReal + 4, 29);
  echo "SID:" . $sid . "<br>";

  add_metadata( 'user', 1, $key, $sid );
}

$link = "https://scopus.com/api/authors/24479782800";
curl_get_contents($link, "https://www.scopus.com/home.uri", true);

foreach($teachers as $post){
  setup_postdata($post);

  $id = get_the_id();

  $scopusID = get_field('scopus');
  $scholarID = get_field('google_scholar');
  $publonsID = get_field('publons_id');
  $ieeeID = get_field('ieee_id');
  $researcherID = get_field('researcher_id');
  $dblp = get_field('dblp_link');
  $lastModified = get_field('last_modified');
  $scopus_data = array();
  $scholar_data = array();
  $publons_data = array();

  if($lastModified == $today) continue;
  echo "Processing " . get_the_title() . "<br>";
  echo "Scopus: " . $scopusID . "<br>";
    echo "Scholar: " . $scholarID . "<br>";
    echo "Publons: " . $publonsID . "<br>";
    echo "IEEE: " . $ieeeID . "<br>";
    echo "Researcher: " . $researcherID . "<br>";
    echo "DBLP: " . $dblp . "<br>";
    echo "Last modified: " . $lastModified . "<br>";
  $scopus_data = null;

  if($scopusID){
    $scopus_data = getScopusAuthor($scopusID);
    print_r($scopus_data);echo "<br>";
    if($scopus_data !== null) update_field('scopus_data', $scopus_data, $id);
  }

  if($scholarID){
    $scholar_data = parseScholar($scholarID);
    print_r($scholar_data);echo "<br>";
    update_field('scholar_data', $scholar_data, $id);
  }

  if($researcherID){
    $publons_data = parsePublons($researcherID);
    print_r($publons_data);echo "<br>";
    update_field('publons_data', $publons_data, $id);
  }

  if($ieeeID){
    $ieee_data = parseIEEE($ieeeID);
    print_r($ieee_data);echo "<br>";
    update_field('ieee_data', $ieee_data, $id);
  }

  if($dblp){
    $dblp_data = parseDBLP($dblp);
    print_r($dblp_data);echo "<br>";
    update_field('dblp_data', $dblp_data, $id);
  }

  $row = array(
    'id' => $id,
    'scopus'   => $scopus_data,
    'web_of_science'  => $publons_data,
    'scholar'  => $scholar_data
  );

  add_row('staff', $row, $post_id);

    update_field('last_modified', $today);
    echo $scopusID . "<br>"; var_dump($scopus_data);

  wp_reset_postdata();

  $scopusID = 0;
  $scholarID = 0;
  $publonsID = 0;
   flush();
    sleep(1);
}

function parseScholar($scholarID){
	$link = "https://scholar.google.com.ua/citations?user={$scholarID}&hl=ru";
  $html = curl_get_contents($link);
	$html = str_get_html($html['html']);

	$q1 = (int) $html->find('.gsc_rsb_std', 0)->plaintext;
	$q2 = (int) $html->find('.gsc_rsb_std', 1)->plaintext;
	$h1 = (int) $html->find('.gsc_rsb_std', 2)->plaintext;
	$h2 = (int) $html->find('.gsc_rsb_std', 3)->plaintext;
	$i1 = (int) $html->find('.gsc_rsb_std', 4)->plaintext;
	$i2 = (int) $html->find('.gsc_rsb_std', 5)->plaintext;

	$data['total_citations'] = $q1;
	$data['total_citations_last_5'] = $q2;
	$data['h-index'] = $h1;
	$data['h-index_last_5'] = $h2;
	$data['i10-index'] = $i1;
  $data['i10-index_last_5'] = $i2;
  
	return $data;
}

function parsePublons($researcherID){
  $link = "https://www.webofscience.com/wos-researcher/stats/individual/" . $researcherID . "/";
  echo "accessing " . $link . "<br>";
  $html = curl_get_contents($link);
  $json = $html['html'];
  $json_data = json_decode($json);

	$data['publications'] = $json_data->numPublicationsInWosCc;
	$data['total_citations'] = $json_data->timesCited;
  $data['h-index'] = $json_data->hIndex;

  $link = "https://www.webofscience.com/api/wosnx/rrc/bridge/mws/v2/author/"  . $researcherID . "?coAuthor=true&lightWeight=false";
  echo "accessing " . $link . "<br>";$html = curl_get_contents($link);
  $json = $html['html'];
  $json_data = json_decode($json);

  $data['citing_articles'] = $json_data->totalCitingPublications;
  
	return $data;
}

function parseIEEE($ieeeID){
  $link = "https://ieeexplore.ieee.org/rest/author/" . $ieeeID;
  $html = curl_get_contents($link, "https://ieeexplore.ieee.org/author/" . $ieeeID);
  $json = $html['html'];
  $json_data = json_decode($json);

	$data['publications'] = $json_data[0]->articleCount;
	$data['citations'] = $json_data[0]->citations;
  
	return $data;
}

function parseDBLP($link){
  $link = str_replace('html', 'json?view=coauthors', $link);
  $html = curl_get_contents($link);
  $json = str_replace(PHP_EOL, ' ', $html['html']);
  $json_data = json_decode($json);

  $nodes = $json_data->nodes;
  $data['publications'] = $nodes[count($nodes) - 1]->publs;
  
	return $data;
}

function curl_get_contents($page_url, $base_url = 'https://www.google.com/', $get_cookies = false, $set_cookies = true) {
  global $sid;

  $error_page = array();
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36");   
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
  curl_setopt($ch, CURLOPT_URL, $page_url);
  curl_setopt($ch, CURLOPT_REFERER, $base_url);
  curl_setopt($ch, CURLOPT_HEADER, 0); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_COOKIESESSION, 0);
  if($sid) curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "x-1p-wos-sid: " . $sid,
  ));
  $tmpfname = dirname(__FILE__) . '/cookie.txt';
  if($get_cookies) curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
  if($set_cookies) curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
  
  $response['html'] = curl_exec($ch);
  $info = curl_getinfo($ch);
  if($info['http_code'] != 200 && $info['http_code'] != 404) {
    $error_page[] = array(1, $page_url, $info['http_code']);
  }
  $response['code'] = $info['http_code'];
  $response['errors'] = $error_page;
  curl_close($ch);
  return $response;
}

function getScopusAuthor($authorId) {
    $apiKey    = "9875043850dcdaaa0bd4a3b362a430b1";
    $insttoken = "71d3fc9674224762d8fbb10d207dab19";

    $url = "https://api.elsevier.com/content/author/author_id/" . $authorId . "?view=metrics";

    $headers = [
        "X-ELS-APIKey: $apiKey",
        "X-ELS-Insttoken: $insttoken",
        "Accept: application/json"
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        return ["error" => $error];
    }

    $data = json_decode($response, true);

    curl_close($ch);

    $h_index = $data["author-retrieval-response"][0]["h-index"] ?? 0;
    $total_publications = $data["author-retrieval-response"][0]["coredata"]["document-count"] ?? 0;
    $total_citations = $data["author-retrieval-response"][0]["coredata"]["citation-count"] ?? 0;

    $result = [
        "h_index" => $h_index,
        "total_publications" => $total_publications,
        "total_citations" => $total_citations,
    ];

    return $result;
}

?>
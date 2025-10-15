<?php
include_once('parser/simple_html_dom.php');
$link = "https://scholar.google.com.ua/citations?user=-r6abgQAAAAJ&hl=ru";
$data = array();
$i = 0;
 
function curl_get_contents($url){
    //The IP address of the proxy you want to send
    //your request through.
    $proxyIP = '91.217.42.2';
    
    //The port that the proxy is listening on.
    $proxyPort = '8080';
    
    //The username for authenticating with the proxy.
    $proxyUsername = 'myusername';
    
    //The password for authenticating with the proxy.
    $proxyPassword = 'mypassword';
    
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL , 1);

    curl_setopt($ch, CURLOPT_ENCODING, "");
    
    //Set the proxy IP.
    curl_setopt($ch, CURLOPT_PROXY, $proxyIP);
    
    //Set the port.
    curl_setopt($ch, CURLOPT_PROXYPORT, $proxyPort);
    
    //Specify the username and password.
    curl_setopt($ch, CURLOPT_PROXYUSERPWD, "$proxyUsername:$proxyPassword");
    
    //Execute the request.
    return curl_exec($ch);
}

$output = curl_get_contents($link);
$html = str_get_html($output);

$q1 = (int) $html->find('.gsc_rsb_std', 0)->plaintext;
$q2 = (int) $html->find('.gsc_rsb_std', 1)->plaintext;
$h1 = (int) $html->find('.gsc_rsb_std', 2)->plaintext;
$h2 = (int) $html->find('.gsc_rsb_std', 3)->plaintext;
$i1 = (int) $html->find('.gsc_rsb_std', 4)->plaintext;
$i2 = (int) $html->find('.gsc_rsb_std', 5)->plaintext;
$name = $html->find('#gsc_prf_in', 0)->plaintext;
$data[] = $q1;
$data[] = $q2;
$data[] = $h1;
$data[] = $h2;
$data[] = $i1;
$data[] = $i2;
$data[] = $name;
$data[] = $scholarID;
var_dump($data);
exit;

$proxy_counter = 0;
 
//Функция парсинга списка прокси
function getProxy() {
    global $proxy_counter;
 
    $link = 'https://free-proxy-list.net/';
 
    $agent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36';
 
    $ch = curl_init($link);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response_data = curl_exec($ch);
    if (curl_errno($ch) > 0) {
        die('Ошибка curl: ' . curl_error($ch));
    }
    curl_close($ch);
 
    preg_match_all('#<td>[0-9.]{5,}[0-9]{2,}</td>#', $response_data, $rawlist);
 
    $cleanedList = str_replace('</td><td>', ':', $rawlist[0]);
    $cleanedList = str_replace('<td>', '', $cleanedList);
    $cleanedList = str_replace('</td>', '', $cleanedList);
 
    //Сбрасываем счётчик если это уже не первый список прокси
    $proxy_counter = 0;
    //Докладываем, что список составлен
    
    //Возвращаем спарсеный список
    return $cleanedList;
}
 
//Получаем начальный список из 300 прокси

function curl_get_contents2($page_url, $base_url = 'https://www.google.com/', $pause_time = 1, $retry = 0) {
    $error_page = array();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0");   
    curl_setopt($ch, CURLOPT_PROXY, '155.138.129.240:8080');
    curl_setopt($ch, CURLOPT_COOKIEJAR, str_replace("\\", "/", getcwd()).'/gearbest.txt'); 
    curl_setopt($ch, CURLOPT_COOKIEFILE, str_replace("\\", "/", getcwd()).'/gearbest.txt'); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Автоматом идём по редиректам
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); // Не проверять SSL сертификат
    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0); // Не проверять Host SSL сертификата
    curl_setopt($ch, CURLOPT_URL, $page_url); // Куда отправляем
    curl_setopt($ch, CURLOPT_REFERER, $base_url); // Откуда пришли
    curl_setopt($ch, CURLOPT_HEADER, 0); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Возвращаем, но не выводим на экран результат
    $response['html'] = curl_exec($ch);
    $info = curl_getinfo($ch);
    if($info['http_code'] != 200 && $info['http_code'] != 404) {
        $error_page[] = array(1, $page_url, $info['http_code']);
        if($retry) {
            sleep($pause_time);
            $response['html'] = curl_exec($ch);
            $info = curl_getinfo($ch);
            if($info['http_code'] != 200 && $info['http_code'] != 404)
                $error_page[] = array(2, $page_url, $info['http_code']);
        }
    }
    $response['code'] = $info['http_code'];
    $response['errors'] = $error_page;
    curl_close($ch);
    return $response;
}

function file_get_contents_curl($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

function curl_tt($url){

	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);  
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 3);     
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	$data = curl_exec($ch);
	curl_close($ch);
	
	return $data;
	}
?>
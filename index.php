<?php 
error_reporting(E_ALL); ini_set('display_errors','On'); 
define('FACEBOOK_SDK_V4_SRC_DIR', __DIR__.'/src/Facebook/');
require_once(__DIR__.'/src/facebook.php');
require 'src/config.php';
$fb = new Facebook(array(
  'appId'  => $config['App_ID'],
  'secret' => $config['App_Secret'],
  'cookie' => true,
  'fileUpload' => false
));
$appaccess="EAAN5GPqyCxEBAIHgoJZAFKpMz40PuU8vxmZCQYWnFpxd8dHARzrekhieAoabG7ksjtZBVbioKm8e4HVHHWGo9ZCMDXsVn30ZCNrN5nbXvOcFWWlAPwXqrXHRvJm9lABhJittWiYptqcumZCZAbcSohZBfp8f58ln1IfVb8vp8geaPwZDZD";
$longaccessurl="https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id=".$config['App_ID']."&client_secret=".$config['App_Secret']."&fb_exchange_token=".$appaccess;
//echo $longaccessurl;
$jset['access_token']="EAAN5GPqyCxEBAFEPyxRHiL6OkhWgvInsHhG14Sa0OkJZAIz3hqgPsWkNHcP0eQ2mW9f6hvoGTbr6qkHzpNw1VqoI5KobTIi6egBuS7bKDfEsrR3oYlOaFbz1jn2pGuUTcw1R1DHZBFwDozv73FwzJIMLkm5BnM54iVHZCz7AgZDZD";
//Post property to Facebook
$pageAccessToken =$jset['access_token'];
 $params = array(
          "access_token" => $pageAccessToken, 
          "message" => "Here is a blog post about auto posting on Facebook using PHP #php #facebook",
          "link" => "http://www.railroutes.in/",
          "picture" => "http://www.railroutes.in/images/logo.png",
        );

try {
  $response = $fb->api('/324859894557018/feed', 'POST', $params);
  echo 'Successfully posted to Facebook';
} catch(Exception $e) {
  echo $e->getMessage();
}
		
?>
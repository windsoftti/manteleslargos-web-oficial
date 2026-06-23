<?php

use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

# $facebook_app_id        = '1066264884245418';
# $facebook_app_secret    = 'def3e714dabb19903d89e9ff3f97cd3f';
$facebook_app_id        = '492209112312810';
$facebook_app_secret    = '3e539c891a856199095f17a3dba5dd4d';
$facebook_redirect_URL  = BASE_URL . '/facebook-session.php';
$facebook_permissions   = array('scope' => 'email');

#echo $facebook_redirect_URL;
#die;

$fb = new Facebook(array(
  'app_id' => $facebook_app_id,
  'app_secret' => $facebook_app_secret,
  'default_graph_version' => 'v2.9',
  'persistent_data_handler'=>'session'
));

# Get login helper
$facebook_helper = $fb->getRedirectLoginHelper();

try {
  $facebook_access_token = $facebook_helper->getAccessToken();
} catch (FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch (FacebookSDKException $e) {
  echo 'Facebook SDK returned an error---: ' . $e->getMessage();
  exit;
}
<?php
require_once 'go/src/Google_Client.php';
require_once 'go/src/contrib/Google_Oauth2Service.php';
require_once 'sql.php';
session_start();
$client = new Google_Client();
$client->setApplicationName("YOUR_APPLICATION_NAME");
$client->setClientId('YOUR_CLIENT_ID');
$client->setClientSecret('YOUR_CLIENT_SECRET');
$client->setRedirectUri("YOUR_REDIRECT_URL");
$client->setDeveloperKey('YOUR_DEVELOPER_KEY');
$client->setAccessType("online");
$client->setApprovalPrompt("auto");
$oauth2 = new Google_Oauth2Service($client);


if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['token'] = $client->getAccessToken();
  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
  return;
}

if (isset($_SESSION['token'])) {
 $client->setAccessToken($_SESSION['token']);
}

if (isset($_REQUEST['logout'])) {
  unset($_SESSION['token']);
  unset($_SESSION["emailyt"]);
  $client->revokeToken();
}

if ($client->getAccessToken()) {
  $user = $oauth2->userinfo->get();

  // These fields are currently filtered through the PHP sanitize filters.
  // See http://www.php.net/manual/en/filter.filters.sanitize.php
  $email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
  $_SESSION['emailyt']=$email;
  insert($email,$client->getAccessToken());
  // The access token may have been updated lazily.
  $_SESSION['token'] = $client->getAccessToken();
} else {
  $authUrl = $client->createAuthUrl();
}
?>
<!doctype html>
<html>
 <link href="style.css" rel="stylesheet" type="text/css">
<head><meta charset="utf-8"></head>
<body>
<center>
<header><h1>MyYouTube</h1></header>
<?php if(isset($personMarkup)): ?>
<?php print $personMarkup ?>
<?php endif ?>
<?php
  if(isset($authUrl)) {
    print "<a class='login' href='$authUrl'>Login</a><br/><br/><br/><br/><br/>";
	print "Technologies Used:<br/>Amazon S3 (Storing User Videos)<br/>Amazon EC2 (Running the Web Server)<br/>Amazon CloudFront (Content Distribution)<br/>JWPlayer (Video Player)<br/>HTML5 (Inbuilt Video Playing)<br/>Wowza Media Server (using Amazon CloudFormation) - for live streaming of video<br/><br/><br/>Tasks completed in this Assignment:<br/>
Application should be capable of operations of Upload, List View, Rating and Deletion (Deletion allowed only for the owner of the video)<br/>
Videos are uploaded to an Amazon S3 Bucket. File type compatibility is checked else error is thrown.<br/>
Option to delete a video<br/>
<br/>(BONUS) Creation of the bucket using Amazon S3 API dyanmically. initial.php<br/>
Streaming the video using CloudFront as CDN. (Both Web and RTMP Distribution)<br/>
(BONUS) Creation of the cloudfront distribution using Amazon API dynamically. initial.php<br/>
Streaming done using both JWPlayer (Flash and uses RTMP Distribution) and HTML5 (uses Web Distribution)<br/>
<br/>Rating of Videos. One user can rate a video with only one value which can be modified by the user.<br/>
Use of Amazon RDS to store the Rating details together with the videos details.<br/>
<br/>Amazon EC2 server to host this website<br/>
<br/>(BONUS) Phone Based Video Upload to S3 (through the HTML5 interface, since most smartphones support direct HTML5 uploads)<br/>
<br/>(BONUS) Handled Live Streaming. (livestream.php) (Used an Amazon CloudFormation running Wowza Media Server to encode and <br/>compress the live video and distribute it through the Amazon CloudFront <br/>and then used JWPlayer to live stream the broadcast using the RTMP Playback URL provided in the Wowza Server)<br/>";
  } else {
   header('Location: ' . filter_var(URL."page.php", FILTER_SANITIZE_URL));
  }
?>
</center>
</body></html>

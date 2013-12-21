<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php
require_once 'sql.php';
session_start();
if(!isset($_SESSION["emailyt"]))
header('Location: ' . filter_var(URL, FILTER_SANITIZE_URL));
else
$user=$_SESSION["emailyt"];
?>
<TITLE><?php print "mYT - Live Streaming"?></TITLE>	
<script type='text/javascript' src='https://d2ki7pxs1xnonm.cloudfront.net/jwplayer.js'></script>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="rating.js"></script>
<link rel="stylesheet" type="text/css" href="rating.css" />
<link rel="stylesheet" type="text/css" href="style.css" />
<style type="text/css">
.implementation{
	border:dashed 2px #333333;
	background-color:#CCCCCC;
	color:#000000;
	width:50%;
}
.spacer{
clear:both;
height:0px;
}
.left{
	float:left;
	width:250px;
}
</style>
</HEAD>
<BODY>
<?php print "<div style=\"float:left;margin-left:30em\"><a href='page.php'>Go Back to Videos Page</a></div><div style=\"float:right;margin-right:30em\"><a class='logout' href='index.php?logout'>Logout ( $user )</a></div><br/><br/><br/></center>";?>
<center><div id='mediaplayer'></div></center>
			
			<script type="text/javascript">
			jwplayer("mediaplayer").setup({
    file: "rtmp://ec2-50-16-20-221.compute-1.amazonaws.com/livecf/myStream",
    height: 480,
    width: 853
});
</script>
<center><h2>Live Streaming using Wowza Server on Amazon CloudFormation</h2></center>
</BODY>
</HTML>

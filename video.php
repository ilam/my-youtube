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
if(!isset($_GET["file"]))
header('Location: ' . filter_var(URL."page.php", FILTER_SANITIZE_URL));
else
{
//include the S3 class
if (!class_exists('S3'))require_once('S3.php');

//AWS access info
if (!defined('awsAccessKey')) define('awsAccessKey', 'YOUR_AWS_ACCESS_KEY');
if (!defined('awsSecretKey')) define('awsSecretKey', 'YOUR_AWS_SECRET_KEY');

//instantiate the class
$s3 = new S3(awsAccessKey, awsSecretKey);
if(isset($_GET["file"]))
{
//$obj= $s3->getObjectInfo("ik2342_myyoutube",$user."/".$_GET["file"]);
//print_r($obj);
//if(!($obj && array_key_exists("hash",$obj)))
//header('Location: ' . filter_var("http://localhost/yt/page.php", FILTER_SANITIZE_URL));
$userview = $_GET["user"];
$file=$userview."/".$_GET["file"];
$ext = end(explode('.',$file));
$fileName=$_GET["file"];
$cur = getrating($user,$file);
list($navg,$nusers) = getvideorating($file);
}
else
header('Location: ' . filter_var(URL."page.php", FILTER_SANITIZE_URL));
}
?>
<TITLE><?php print "mYT - ".$fileName." - ". $user;?></TITLE>	
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
<script type="text/javascript">
$(document).ready(function() {
	$('#rate2').rating('<?php print URL;?>rating.php?user=<?php print $user;?>&file=<?php print $file?>', {maxvalue:5, curvalue:<?php print $cur;?>,filepath:<?php print "'$file'";?>});
});		
</script>
</HEAD>
<BODY>
<?php print "<div style=\"float:left;margin-left:30em\"><a href='page.php'>Go Back to Videos Page</a></div><div style=\"float:right;margin-right:30em\"><a class='logout' href='index.php?logout'>Logout ( $user )</a></div><br/><br/><br/><center><h4>$fileName</h4></center>";?>
<div style="margin-right:25em;float:right;margin-top:-3em"><div id="rate2" class="rating">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id=result1>Rating : <?php print round($navg,2);?> by <?php print $nusers;?> user(s)<span></div></div>
<center>If you are on Mobile. <a href=https://d2ki7pxs1xnonm.cloudfront.net/<?php echo $file;?>>Click here</a> to view the video<br/><br/></center>
<center><div id='mediaplayer'></div><br/><br/><br/>Streaming using JWPlayer (For viewing in Flash(fallback mode too)</center>
			
			<script type="text/javascript">
			jwplayer("mediaplayer").setup({
	   		playlist: [{
				sources: [{ 
	    <?php echo 'file: "rtmp://s37a4czipreym.cloudfront.net/cfx/st/'.$ext.':'.$file.'"';?>	
				},{
			<?php
            echo 'file: "https://d2ki7pxs1xnonm.cloudfront.net/'.$file.'"';
			?>
        }]
    }],
    height: 480,
    primary: "flash",
    width: 853
});
</script>


<br/><br/><br/>
<center><video id=mycloud preload=auto width=853 height=480 data-setup="{}" controls autobuffer >
<source src="<?php echo "https://d2ki7pxs1xnonm.cloudfront.net/$file"?>" type='video/mp4'/>
</video><br/>HTML5 Streaming (For Viewing and Downloading of Files too)
</center>
</BODY>

</HTML>

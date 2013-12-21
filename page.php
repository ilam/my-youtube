<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
require_once 'sql.php';
session_start();
if(!isset($_SESSION["emailyt"]))
header('Location: ' . filter_var(URL, FILTER_SANITIZE_URL));
else
$user=$_SESSION["emailyt"];
$bucket="YOUR_BUCKET_NAME_IN_AMAZON_S#";
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>mYT - <?php print $user; ?></title>
        <link href="style.css" rel="stylesheet" type="text/css">
    </head>

<body>
<?php print "<div style=\"float:left;margin-left:40em\"><a class='logout' href='livestream.php'>Watch Live Streaming</a></div><div style=\"float:right;margin-right:30em\"><a class='logout' href='index.php?logout'>Logout ( $user )</a></div><br/>";?>
<center>
    	<?php
		function startsWith($haystack, $needle)
		{
		return $needle === "" || strpos($haystack, $needle) === 0;
		}
    	ini_set('display_errors', 1);
		ini_set('error_reporting', 8191); 
		ini_set('upload_max_filesize', '2000M');
		ini_set('post_max_size', '2000M');
		ini_set('max_input_time', 30000);
		ini_set('max_execution_time', 30000);
			//include the S3 class
			if (!class_exists('S3'))require_once('S3.php');
			
			//AWS access info
			if (!defined('awsAccessKey')) define('awsAccessKey', 'YOUR_AWS_ACCESS_KEY');
			if (!defined('awsSecretKey')) define('awsSecretKey', 'YOUR_AWS_SECRET_KEY');
			
			//instantiate the class
			$s3 = new S3(awsAccessKey, awsSecretKey);
			
			if(isset($_POST["delete"]))
			{   
				$fname = $_POST["vid"];
				if ($s3->deleteObject($bucket, $user."/".$fname)) {
					$fname = str_replace($user."/", "", $fname);
					echo "Your file ".$fname." was successfully deleted";
					header('Location: ' . filter_var(URL."page.php", FILTER_SANITIZE_URL));
			}
			}
			//check whether a form was submitted
			if(isset($_POST['Submit'])){  
				//retreive post variables
				$fileName = $_FILES['theFile']['name'];
				$fileTempName = $_FILES['theFile']['tmp_name'];
				$okExtensions = array('mp4', 'mpeg','flv','3gp','webm','m4v','f4v','mov');
				$fileName = preg_replace('/\s+/', '_', $fileName);
				$fileParts = explode('.', $fileName);
				if( in_array( strtolower( end($fileParts) ), $okExtensions) )
				{
					//move the file
				if ($s3->putObjectFile($fileTempName, $bucket, $user."/".$fileName, S3::ACL_PUBLIC_READ)) {
					echo "<strong>We successfully uploaded your file.</strong>";
					header('Location: ' . filter_var(URL."page.php", FILTER_SANITIZE_URL));
				}else{
					echo "<strong>Something went wrong while uploading your file... sorry.</strong>";
					}
				}
				else
				{
				echo "Sorry !! The extension of the file is NOT a valid video type. The extension of the file you uploaded is ".end($fileParts).". Please try with a valid extension !!";
				}
				
				
				
			}
		?>
<h1>Upload a file</h1>
<p>Please select a file by clicking the 'Browse' / 'Choose File' button.<br/>Press 'Upload' to start uploading your file.</p>
   	<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <input name="theFile" type="file" />
      <input name="Submit" type="submit" value="Upload">
	</form>
	<br/><br/><br/>
<h1>Your Uploaded Videos</h1>
<?php
	
	// Get the contents of our bucket
	$contents = $s3->getBucket($bucket);
	echo "<table>";
	
	
	$ffile=array();
	$fval=array();
	foreach($contents as $file){
	$fname = $file['name'];
	if(startsWith($fname,$user))
		{
		list($na,$nu)=getvideorating($fname);
		array_push($ffile,$fname);
		array_push($fval,$na);
		}
	}
	$m=0;
	array_multisort($fval,SORT_DESC,$ffile);
	foreach ($ffile as $f){	
		$fname=$f;
		$val=round($fval[$m],2);
		$m++;
		$fname = str_replace($user."/", "", $fname);
		$furl = "video.php?user=".$user."&file=".$fname;
		echo "<tr><td><a href=\"$furl\">$fname ( Rating: $val ) </a></td>
		<td>
		<form method=post>
		<input type=hidden name=vid value=\"$fname\" />
		<input name=delete type=submit value=Delete />
		</form>
		</td>
		</tr>";
		}
	
	echo "Number of Videos : ".$m;
	echo "</table><br/><br/>";
	
	$st=mysql_query("select * from yt_users");
	if(mysql_num_rows($st))
	{
	echo "<h1>Videos Uploaded by Others</h1><br/>";
	$sp=mysql_num_rows($st)/3;
	for($i=0;$i<mysql_num_rows($st);$i++)
	{
	$u= mysql_result($st,$i,"id");
	if($u!=$user)
	{
	echo "<table>";
	echo $u;

	$ffile=array();
	$fval=array();
	foreach($contents as $file){
	$fname = $file['name'];
	if(startsWith($fname,$u))
		{
		list($na,$nu)=getvideorating($fname);
		array_push($ffile,$fname);
		array_push($fval,$na);
		}
	}
	$m=0;
	array_multisort($fval,SORT_DESC,$ffile);
	foreach ($ffile as $f){	
		$fname=$f;
		$val=round($fval[$m],2);
		$m++;
		$fname = str_replace($u."/", "", $fname);
		$furl = "video.php?user=".$u."&file=".$fname;
		echo "<tr><td><a href=\"$furl\">$fname ( Rating: $val ) </a></td>
		<td>
		<form method=post>
		<input type=hidden name=vid value=\"$fname\" />
	
		</form>
		</td>
		</tr>";
		}
	
	echo " - Number of Videos : ".$m;
	echo "</table><br/><br/>";
	
	
	}
	}
	}
	
	
	
?>
</center>
</body>
</html>

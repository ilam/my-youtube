<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>mYT - Initial Setup</title>
        <link href="style.css" rel="stylesheet" type="text/css">
    </head>
	
<center>
<h1>Settings of the MyYouTubeApp</h1><br/><br/><br/>
<?php
require_once 'sql.php';
if (!class_exists('S3')) require_once 'S3.php';
// AWS access info
if (!defined('awsAccessKey')) define('awsAccessKey', 'YOUR_AWS_ACCESS_KEY');
if (!defined('awsSecretKey')) define('awsSecretKey', 'YOUR_AWS_SECRET_KEY');
$s3 = new S3(awsAccessKey, awsSecretKey);

function test_createDistribution($bucket, $cnames = array()) {
	if (($dist = S3::createDistribution($bucket, true, $cnames, 'New distribution created')) !== false) {
		echo "createDistribution($bucket): "; var_dump($dist);
	} else {
		echo "createDistribution($bucket): Failed to create distribution\n";
	}
}


if(isset($_POST["addBucket"]))
{
$bucketName = uniqid($_POST["bucket"]);
if ($s3->putBucket($bucketName, S3::ACL_PUBLIC_READ)) {
	echo "Created bucket {$bucketName}"."<br/>";
addbucket($bucketName);
test_CreateDistribution($bucketName);
}
}
if(checkbucket())
{
echo "Thank You!! The initial setup is COMPLETE<br/>The buckets and the distributions were created. Here are the details.<br/>";
}
else
{
echo "<form method=post>Bucket Name : <input type=text name=bucket /><input type=submit name=addBucket value=\"Create Bucket\" />";
}
?>
</center>

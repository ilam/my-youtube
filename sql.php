<?php
define('URL', 'YOUR_ENTIRE_URL');
$con=mysql_connect("YOUR_AMAZON_RDS_DATABASE_URL","YOUR_DATABASE_USERNAME","YOUR_DATABASE_PASSWORD");
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("mydb") or die( "Unable to select database");

function insert($user,$token)
{
$check=mysql_query("select * from yt_users where id='$user'");
if(mysql_num_rows($check)==0)
{
mysql_query("insert into yt_users values ('$user','$token',NULL)");
}
}

function addrating($id,$vid,$rating)
{
$check=mysql_query("select * from yt_ratings where id='$id' and vid='$vid'");
if(mysql_num_rows($check)==0)
{
$st=mysql_query("insert into yt_ratings values ('$id','$vid','$rating')");
}
else
{
if($rating==0)
$st=mysql_query("delete from yt_ratings where id='$id' and vid='$vid'");
else
$st=mysql_query("update yt_ratings set rating='$rating' where id='$id' and vid='$vid'");
}
}

function removerating($id,$vid)
{
$check=mysql_query("delete from yt_ratings where id='$id' and vid='$vid'");
}

function getrating($id,$vid)
{
$check=mysql_query("select * from yt_ratings where id='$id' and vid='$vid'");
if(mysql_num_rows($check)!=0)
{
return mysql_result($check,0,"rating");
}
return 0;
}

function getvideorating($vid)
{
$check=mysql_query("select avg(rating) as avr,count(rating) as cvr from mydb.yt_ratings where vid='$vid' group by vid");
if(mysql_num_rows($check))
{
return array(mysql_result($check,0,"avr"),mysql_result($check,0,"cvr"));
}
return array(0,0);
}

function checkbucket()
{
$st=mysql_query("select * from settings where id='bucket'");
if(mysql_num_rows($st)!=0)
return true;
else
return false;
}

function checkdistribution()
{
$st=mysql_query("select * from settings where id='web' or id='rtmp'");
if(mysql_num_rows($st)!=0)
return true;
else
return false;
}

function addbucket($val)
{
$st=mysql_query("insert into settings values ('bucket','$val')");
}

function adddistribution($web,$rtmp)
{
$st=mysql_query("insert into settings values ('web','$web')");
$st=mysql_query("insert into settings values ('rtmp','$rtmp')");
}
?>

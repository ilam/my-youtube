<?php
require_once 'sql.php';
if(isset($_POST["rating"]))
{
$rating=$_POST["rating"];
$id=$_GET["user"];
$vid=$_GET["file"];
addrating($id,$vid,$rating);
}
if(isset($_GET["getdetails"]))
{
list($na,$nu)=getvideorating($_GET["getdetails"]);
echo "Rating : ".round($na,2)." by ".$nu." user(s)";
}
?>

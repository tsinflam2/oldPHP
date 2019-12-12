<?php

function checkempty($myele, $mystr){
    if(strlen($mystr)==0) return $myele." is empty.";
}
function checkstring($myele, $mystr){
    if(!filter_var($mystr, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES|FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)) return $myele." is invalid.";
}
function checkint($myele, $mystr){
    if(!filter_var($mystr, FILTER_VALIDATE_INT)) return $myele." is invalid.";
}
function check5digi($myele, $mystr){
    if(strlen($mystr)!=5) return $myele." should be 5 digis.";
}
if(!isset($_GET['textbox'])) {
    echo "<font color=#FF0000>Error: Invalid GET data.</font>";
}

$str=$_GET['textbox'];
$val=explode("__", $str);

$message='';

switch($val[0]){

case "Loginnames":
    $message= checkempty($val[0], $val[1]);
    if($message=="")$message= checkstring($val[0], $val[1]);
    break;
case "Username":
    $message= checkempty($val[0], $val[1]);
    if($message=="")$message= checkstring($val[0], $val[1]);
    break;
case "Skill":
    $message= checkempty($val[0], $val[1]);
    if($message=="")$message= checkstring($val[0], $val[1]);
    break;
case "Interest":
    $message= checkempty($val[0], $val[1]);
	if($message=="")$message= checkstring($val[0], $val[1]);
    //if($message=="")$message= checkint($val[0], $val[1]);
    break;
case "Password":
    $message= checkempty($val[0], $val[1]);
	if($message=="")$message= checkstring($val[0], $val[1]);
    //if($message=="")$message= checkint($val[0], $val[1]);
    break;
}
echo "<font color=#FF0000>".$message."</font>";
?>

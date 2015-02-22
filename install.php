<?php include "config.php"; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>XDCC Fetcher</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
TABLE.Section {
	border:solid 1px #333333;
	width:300;
	margin-bottom: 25px;
}
TD {
	font-family: Verdana, Arial;
	font-size: 12px;
	color: #333333;
}
TD.Section {
	padding:5px;
	font-size: 10px;
}
.TableTitle {
	position: relative;
	top: -15px;
	padding-left: 2px;
	padding-right: 2px;
	color: #333333;
	background-color: #CCCCCC;
	font-weight: bold;
	font-size: 10px;
}
IMG {
	width: 300px;
	height: 32px;
	border: 0px;
	margin-top: 30px;
	margin-bottom: 15px;
}
.CopyRight {
	font-family: Verdana, Arial;
	font-size: 10px;
	color: #999999;
	text-align: center;
}
</style>
</head>
<body bgcolor="#CCCCCC">
<center><img src="logo.gif"></center>
<?php

echo '<table align="center" class="Section">
  <tr>
    <td class="Section"><span class="TableTitle">Downloads folder</span><br>';


echo "The downloads folder is: <b>" . $downloadfolder . "</b><br>
If this is incorrect, please edit config.php<br>";

echo "<br>Directory listing test...";
flush();
$handler = @opendir($downloadfolder);
if ($handler) {
	echo ' <font color="green"><b>success!</b></font>';
}
else {
	echo ' <font color="red"><b>error - directory does not exist, or access denied</b></font>';
}


echo "<br>File creation test...";
flush();
$testfilename = $downloadfolder . "xf" . rand(10000,99999) . ".please.delete.me";
$handler = @fopen($testfilename, 'a');
if ($handler) {
	echo ' <font color="green"><b>success!</b></font>';
	@fclose($handler);
	echo "<br>Deleting test file...";
	flush();
	$handler = @unlink($testfilename);
	if ($handler) {
		echo ' <font color="green"><b>success!</b></font>';
	}
	else {
		echo ' <font color="red"><b>error - access denied. Please delete manually.</b></font>';
	}
}
else {
	echo ' <font color="red"><b>error - access denied</b></font>';
}


echo '</td>
  </tr>
</table>';










echo '<table align="center" class="Section">
  <tr>
    <td class="Section"><span class="TableTitle">Logs folder</span><br>';


echo "The logs folder is: <b>" . $logsfolder . "</b><br>
If this is incorrect, please edit config.php<br>";

echo "<br>Directory listing test...";
flush();
$handler = @opendir($logsfolder);
if ($handler) {
	echo ' <font color="green"><b>success!</b></font>';
}
else {
	echo ' <font color="red"><b>error - directory does not exist, or access denied</b></font>';
}


echo "<br>File creation test...";
flush();
$testfilename = $logsfolder . "xf" . rand(10000,99999) . ".please.delete.me";
$handler = @fopen($testfilename, 'a');
if ($handler) {
	echo ' <font color="green"><b>success!</b></font>';
	@fclose($handler);
	echo "<br>Deleting test file...";
	flush();
	$handler = @unlink($testfilename);
	if ($handler) {
		echo ' <font color="green"><b>success!</b></font>';
	}
	else {
		echo ' <font color="red"><b>error - access denied. Please delete manually.</b></font>';
	}
}
else {
	echo ' <font color="red"><b>error - access denied</b></font>';
}


echo '</td>
  </tr>
</table>';
include 'footer.php';
?>
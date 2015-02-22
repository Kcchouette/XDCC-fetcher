<html><head>
<style type="text/css"><!--
BODY { font-family: Courier New, Fixedsys; margin-left:5ex; font-size: 12px; background-color:#CCCCCC; }
.TS { color: #999999; }
IMG {
	width: 300px;
	height: 32px;
	border: 0px;
	margin-top: 30px;
	margin-bottom: 15px;
}
A {
	color: #333333;
	font-size: 12px;
}
P { margin:0px; text-indent: -2ex; }
--></style></head>
<body>
<center><img src="logo.gif"></center>
<?php

include "config.php";

$logfilename = $logsfolder . $_GET["log"];

if (file_exists($logfilename)) {
	echo "Server time: " . date("Y-m-d H:i:s") . "<br><br><a href=\"stopclient.php?log=" . $_GET["log"] . "\">Stop this client</a><br><br>";

	$logfile = fopen($logfilename,"r");
	if (filesize($logfilename) > 0) {
		$line = explode("\n",fread($logfile,filesize($logfilename)));
		$counter = 0;
		while (isset($line[$counter])) {
			if ($line[$counter] != "") {
				$parse = explode(" ",$line[$counter]);
				if (substr($parse[0],0,8) == "<script>") {
					echo $line[$counter] . "\n";
				}
				else {
					$startparse = 2;
					$echoline = "<p style=\"color:" . $parse[1] . "\">";

					$string = "";
					for ($x=$startparse; $x<count($parse); $x++) { $string .= $parse[$x] . " "; }
					$echoline .= "<span class=\"TS\">" . date("[Y-m-d H:i:s]",$parse[0]) . "</span> " . rtrim($string) . "</p>";

					echo $echoline;
				}
			}
			$counter++;
		}
	}
	fclose($logfile);

	echo "<br><br>
	<a href=\"stopclient.php?log=" . $_GET["log"] . "\">Stop this client</a></body></html>";
}
else {
	echo "File does not exist";
}

/*
	This file is part of XDCC Fetcher.

        XDCC Fetcher is free software: you can redistribute it and/or modify
        it under the terms of the GNU General Public License as published by
        the Free Software Foundation, either version 2 of the License, or
        (at your option) any later version.

        XDCC Fetcher is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
        GNU General Public License for more details.

        You should have received a copy of the GNU General Public License
        along with XDCC Fetcher.  If not, see <http://www.gnu.org/licenses/>.
*/

?>
<?php

set_time_limit(0);
ignore_user_abort(true);
register_shutdown_function("xfdie");

include "config.php";
include "raw.php";

if ($showrealtime == false) {
	echo "<script>window.location.href='index.php?clientstarted=yes&" . $_SERVER["QUERY_STRING"] . "';</script>";
	flush();
}
else {
	echo "<pre>";
}

$killed = false;
$handle = "";
$dcc = "";
$timer = "";

function xfwrite ($data, $echo = true) {
	if (substr($data,-1) != "\n") { $data .= "\n"; }
	fwrite($GLOBALS["fp"],$data);
	if ($echo) { xfecho($data, "blue"); }
}

function p ($num) {
	if (isset($GLOBALS["parse"][$num])) {
		return $GLOBALS["parse"][$num];
	}
}

function savetofile () {
	$dccgettemp = "";
	$dccgettemp = fgets($GLOBALS["dcc"],1024);
	if ($dccgettemp != "") {
		$GLOBALS["dccget"] = $dccgettemp;
		$GLOBALS["currfilesize"] += strlen($dccgettemp);
		fwrite($GLOBALS["handle"], $dccgettemp);
	}
}

function xfecho ($data, $color = "black", $ts = 1) {
	if (($data != "") && ($data != "\n") && (file_exists($GLOBALS["logfilename"]))) {
		if (substr($data,-1) != "\n") { $data .= "\n"; }

		if ($ts == 1) {
			$write = time() . " " . $color . " " . $data;
		}
		elseif ($color == "") {
			$write = $data;
		}
		else {
			$write = $color . " " . $data;
		}
		fwrite($GLOBALS["logfile"],$write);
		if ($GLOBALS["showrealtime"]) { echo $write; }
	}
}

function xfdie () {
	if ($GLOBALS["killed"] == false) {
		if ($GLOBALS["handle"]) { fclose($GLOBALS["handle"]); }
		if (($GLOBALS["dcc"]) && (!feof($GLOBALS["dcc"]))) { fclose($GLOBALS["dcc"]); }
		if ($GLOBALS["lockfilename"] && $GLOBALS["lockfilename"] != "" && file_exists($GLOBALS["lockfilename"])) { @unlink($GLOBALS["lockfilename"]); }
		if (($GLOBALS["fp"]) && (!feof($GLOBALS["fp"]))) {
			xfwrite("PRIVMSG " . $GLOBALS["user"] . " :XDCC REMOVE");
			xfwrite("PRIVMSG " . $GLOBALS["user"] . " :XDCC REMOVE " . $GLOBALS["pack"]);
			xfwrite("QUIT :XDCC Fetcher");
			sleep(5);
			if (!feof($GLOBALS["fp"])) { fclose($GLOBALS["fp"]); }
		}
		xfecho("process killed (connection status: " . connection_status() . ")");
		fclose($GLOBALS["logfile"]);
		$GLOBALS["killed"] = true;
		die();
	}
}


// Initialisation variables
$server = ltrim(rtrim($_GET["server"]));
$port = ltrim(rtrim($_GET["port"]));
$channel = ltrim(rtrim($_GET["channel"]));
if (substr($channel,0,1) != "#") { $channel = "#" . ltrim(rtrim($channel)); }
$user = $_GET["user"];
$pack = $_GET["pack"];
if (substr($pack,0,1) != "#") { $pack = "#" . ltrim(rtrim($pack)); }
$join = 0;
$joined = 0;
$ison = 0;
$percent = -1;
while ((!isset($nick)) || file_exists($logfilename)) {
	$nick = "xf" . rand(10000,99999);
	$logfilename = $logsfolder . $nick . ".log";
	$delfilename = $logsfolder . $nick . ".del";
}
$logfile = fopen($logfilename, 'a');


while (1 == 1) {
	$fp = @fsockopen($server, $port, $errno, $errstr, 30);
	if (!$fp) {
		xfecho("$errstr ($errno)");
		xfdie();
	}
	else {
		stream_set_blocking($fp,0);
		xfwrite("NICK " . $nick);
		xfwrite("USER " . $nick . " \"xdccfetcher.com\" \"$server\" :XDCC Fetcher");

		while (!feof($fp)) {
			$fparr = array($fp);
			stream_select($fparr, $write = NULL, $except = NULL, 3);
			$get = fgets($fp);
			$parse = explode(" ",$get);
			CheckRaw($get);
			if (rtrim($get) != "" && p(0) != "PING") {
				if ($logall == true || (stristr($get,$user) && p(2) == $nick)) { xfecho($get); }
			}
			if (p(0) == "PING") {
				xfecho("PING? PONG!","green");
				xfwrite("PONG " . substr(p(1), 1),false);
			}
			elseif ((p(1) == "PRIVMSG") && (p(2) == $nick) && (p(3) == ":STOPXF")) {
				xfecho("Manual abort");
				xfdie();
			}
			elseif ((p(1) == "PRIVMSG") && (p(2) == $nick) && (p(3) == ":COMMANDXF")) {
				$string = "";
				for ($x=4; $x<count($parse); $x++) { $string .= $parse[$x] . " "; }
				if ($string != "") { xfwrite(rtrim($string)); }
			}
			elseif (!file_exists($logfilename)) {
				xfdie();
			}
			elseif (file_exists($delfilename)) {
				@unlink($delfilename);
				sleep(2);
				fclose($logfile);
				@unlink($logfilename);
				xfdie();
			}
			elseif ((stristr($get,$user)) && (stristr($get,"all slots full")) && (!stristr($get,"Added you")) && (p(1) == "NOTICE") && (p(2) == $nick)) {
				$timer = time() + 30;
			}
			elseif ((time() >= $timer) && ($timer != 0)) {
				$timer = 0;
				xfwrite("PRIVMSG " . $user . " :XDCC SEND " . $pack . "");
			}
			elseif ((stristr(p(0),$user)) && (p(3) == ":DCC")) {
				if (p(4) == "SEND") {
					echo "Starting DCC...\n";
					$DCCfilesize = (int)(substr(p(8),0,-3));
					$DCCfilename = p(5);
					$DCCip = long2ip(p(6));
					$DCCport = p(7);
					$filename = $downloadfolder . $DCCfilename;
				}
				elseif (p(4) == "ACCEPT") {
					echo "Resume accepted...\n";
				}
				if ((file_exists($filename)) && (p(4) == "SEND")) {
					if (filesize($filename) >= $DCCfilesize) {
						xfecho("File already downloaded");
						xfdie();
					}
					xfecho("Attempting resume...");
					xfwrite("PRIVMSG " . $user . " :DCC RESUME " . $DCCfilename . " " . $DCCport . " " . filesize($filename) . "");
				}
				else {
					xfecho("Connecting to $DCCip on port $DCCport ($DCCfilesize bytes)...");
					$dcc = @fsockopen($DCCip, $DCCport, $errno, $errstr, 30);
					if (!$dcc) {
						xfecho("$errstr ($errno)");
						xfdie();
					}
					else {
						stream_set_blocking($dcc,0);
						xfecho("connected...");
						$filename = $downloadfolder . $DCCfilename;
						if (file_exists($filename . ".lck")) {
							$filename = $downloadfolder . $nick . ".sav";
						}

						$lockfilename = $filename . ".lck";

						$handle = fopen($lockfilename, 'a');
						fclose($handle);

						if (file_exists($filename)) {
							$currfilesize = filesize($filename);
						}
						else {
							$currfilesize = 0;
						}
						$handle = fopen($filename, 'a');

						while (!feof($dcc)) {
							savetofile();
							if (!feof($fp)) {
								$get = fgets($fp);
								if ($get) {
									$parse = explode(" ",$get);
									if (p(0) == "PING") {
										xfecho($get);
										xfwrite("PONG " . substr(p(1), 1));
									}
									elseif ((p(1) == "PRIVMSG") && (p(2) == $nick) && (p(3) == ":STOPXF")) {
										xfecho("Manual abort");
										xfdie();
									}
									elseif ((p(1) == "PRIVMSG") && (p(2) == $nick) && (p(3) == ":COMMANDXF")) {
										$string = "";
										$x = 4;
										while (p(x) != "") {
											$string .= $p($x) . " ";
											$x++;
										}
										if ($string != "") { xfwrite(rtrim($string)); }
									}
								}
							}
							$currpercent = (int)(($currfilesize / $DCCfilesize) * 100);
							if ($currpercent > $percent) {
								$percent = $currpercent;
								xfecho("<script>document.title='" . $percent . "% completed - " . $DCCfilename . " - " . $nick . "';</script>", "", false);
							}
							if (!file_exists($logfilename)) {
								xfdie();
							}
							elseif (file_exists($delfilename)) {
								@unlink($delfilename);
								sleep(2);
								fclose($logfile);
								@unlink($logfilename);
								xfdie();
							}
							elseif ($currfilesize >= $DCCfilesize) {
								xfecho("Downloaded!");
								xfdie();
							}
							elseif ($currfilesize > $DCCfilesize) {
								xfecho("Current filesize is greater than expected! Aborting.");
								xfdie();
							}
							$dccarr = array($dcc);
							@stream_select($dccarr, $write = NULL, $except = NULL, 3);
						}
						if (filesize($filename) < $DCCfilesize) {
							xfecho("aborted.");
							fclose($dcc);
							@unlink($lockfilename);
							fclose($handle);
							xfwrite("PRIVMSG " . $user . " :XDCC SEND " . $pack . "");
						}
					}
				}
			}
		}
	}

	xfecho("Disconnected from server! Reconnecting in 60 seconds...");
	sleep(60);
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
</pre>

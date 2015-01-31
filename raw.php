<?php

$raw['004']['command'] = "GetServerName";
$raw['004']['pretext'] = "Connected to server (#xf#server#xf#)";
$raw['004']['precolor'] = "red";
function GetServerName($rawexplode) { $server = $rawexplode[3]; }




$raw['376']['command'] = "IsOn";
$raw['376']['ttext'] = "Checking if #xf#user#xf# is online...";
$raw['376']['tcolor'] = "green";
$raw['MODE'] = $raw['376'];
function IsOn ($rawexplode) {
	if ($GLOBALS['ison'] == 0) {
		$GLOBALS['ison'] = 1;
		xfwrite("ISON " . $GLOBALS['user'], false);
		return true;
	}
	else { return false; }
}




$raw['303']['command'] = "JoinChannel";
$raw['303']['ttext'] = "User is online. Attempting to join #xf#channel#xf#...";
$raw['303']['tcolor'] = "green";
$raw['303']['ftext'] = "User is NOT online!";
$raw['303']['fcolor'] = "red";
$raw['303']['fquit'] = 1;
function JoinChannel ($rawexplode) {
	if ($rawexplode[3] == ":" . $GLOBALS['user']) {
		$GLOBALS['join'] = 1;
		xfwrite("JOIN " . $GLOBALS['channel'], false);
		return true;
	}
	else { return false; }
}




$raw['366']['command'] = "SendRequest";
$raw['366']['ttext'] = "Successfully joined #xf#channel#xf#. Attempting to retrieve pack #xf#pack#xf# from #xf#user#xf#";
$raw['366']['tcolor'] = "green";
$raw['366']['ftext'] = "Successfully rejoined #xf#channel#xf#";
$raw['366']['fcolor'] = "red";
function SendRequest ($rawexplode) {
	if ($GLOBALS['joined'] == 0) {
		$GLOBALS['joined'] = 1;
		xfwrite("PRIVMSG " . $GLOBALS['user'] . " :XDCC SEND " . $GLOBALS['pack'] . "", false);
		return true;
	}
	else { return false; }
}




$raw['PART']['command'] = "UserAction";
$raw['PART']['ttext'] = "User has parted #xf#channel#xf#!";
$raw['PART']['tcolor'] = "red";
function UserAction ($rawexplode) {
	if (substr($rawexplode[0],0,strlen($GLOBALS['user'])+2) == ":" . $GLOBALS['user'] . "!") { return true; }
	else { return false; }
}




$raw['JOIN']['command'] = "UserAction";
$raw['JOIN']['ttext'] = "User has rejoined #xf#channel#xf#!";
$raw['JOIN']['tcolor'] = "red";




$raw['QUIT']['command'] = "UserAction";
$raw['QUIT']['ttext'] = "User has quit IRC!";
$raw['QUIT']['tcolor'] = "red";
$raw['QUIT']['tquit'] = 1;




$raw['NICK']['command'] = "ChangedNick";
$raw['NICK']['ttext'] = "User has changed nick to #xf#user#xf#";
$raw['NICK']['tcolor'] = "blue";
function ChangedNick ($rawexplode) {
	if (substr($rawexplode[0],0,strlen($GLOBALS['user'])+2) == ":" . $GLOBALS['user'] . "!") {
		return true;
		$GLOBALS['user'] = substr($rawexplode[2],1);
	}
	else { return false; }
}




$raw['KICK']['command'] = "Kicked";
$raw['KICK']['tcolor'] = "red";
function Kicked ($rawexplode) {
	if ($rawexplode[3] == $GLOBALS['user']) {
		$GLOBALS['raw']['KICK']['ttext'] = "User was kicked from #xf#user#xf#";
		return true;
	}
	elseif ($rawexplode[3] == $GLOBALS['nick']) {
		$GLOBALS['raw']['KICK']['ttext'] = "You were kicked by #xf#user#xf#. Attempting to rejoin...";
		xfwrite("JOIN " . $GLOBALS['channel'], false);
		return true;
	}
	else { return false; }
}




$raw['471']['pretext'] = "Cannot join #xf#channel#xf# as the channel has too many users!";
$raw['471']['precolor'] = "red";
$raw['471']['quit'] = 1;




$raw['473']['pretext'] = "Cannot join #xf#channel#xf# as the channel is invite-only!";
$raw['473']['precolor'] = "red";
$raw['473']['quit'] = 1;




$raw['474']['pretext'] = "Cannot join #xf#channel#xf# as you are banned!";
$raw['474']['precolor'] = "red";
$raw['474']['quit'] = 1;




$raw['475']['pretext'] = "Cannot join #xf#channel#xf# as the channel requires a key!";
$raw['475']['precolor'] = "red";
$raw['475']['quit'] = 1;




$raw['477']['pretext'] = "Cannot join #xf#channel#xf# as you need a registered nick!";
$raw['477']['precolor'] = "red";
$raw['477']['quit'] = 1;




$raw['485']['pretext'] = "Cannot join #xf#channel#xf# due to an unknown reason!";
$raw['485']['precolor'] = "red";
$raw['485']['quit'] = 1;




$raw['401']['pretext'] = "User is NOT online!";
$raw['401']['precolor'] = "";
$raw['401']['quit'] = 1;




$raw['433']['command'] = "ChangeNick";
function ChangeNick ($rawexplode) {
	$GLOBALS['nick'] = "xf" . rand(10000,99999);
	xfwrite("NICK " . $GLOBALS['nick']);
	fclose($GLOBALS['logfile']);
	@unlink($GLOBALS['logfilename']);
	$GLOBALS['logfilename'] = $GLOBALS['logsfolder'] . $GLOBALS['nick'] . ".log";
	$GLOBALS['delfilename'] = $GLOBALS['logsfolder'] . $GLOBALS['nick'] . ".del";
	$GLOBALS['logfile'] = fopen($GLOBALS['logfilename'], 'a');
}




$raw['AUTH']['pretext'] = "#xf#get#xf#";
$raw['AUTH']['precolor'] = "brown";



$raw['PRIVMSG']['command'] = "VersionReply";
$raw['PRIVMSG']['ttext'] = "Version request received from #xf#versionnick#xf#. Reply sent.";
$raw['PRIVMSG']['tcolor'] = "red";
function VersionReply ($rawexplode) {
	if (substr($rawexplode[3],0,10) == ":VERSION") {
		$versionnickparse = split('!',$rawexplode[0]);
		$GLOBALS['versionnick'] = substr($versionnickparse[0],1);
		xfwrite("NOTICE " . $GLOBALS['versionnick'] . " :VERSION XDCC Fetcher 0.1 beta - http://xdccfetcher.sourceforge.net", false);
		return true;
	}
}









function CheckRaw ($line) {
	$rawexplode = explode(" ",$line);
	if (isset($rawexplode[1])) {
		$rawcode = $rawexplode[1];
		$commandreturn = true;
		if (isset($GLOBALS['raw'][$rawcode]['pretext'])) { RawEcho($GLOBALS['raw'][$rawcode]['pretext'],$GLOBALS['raw'][$rawcode]['precolor']); }
		if (isset($GLOBALS['raw'][$rawcode]['command'])) { eval("$" . "commandreturn = " . $GLOBALS['raw'][$rawcode]['command'] . "($" . "rawexplode);"); }
		if (isset($GLOBALS['raw'][$rawcode]['posttext'])) { RawEcho($GLOBALS['raw'][$rawcode]['posttext'],$GLOBALS['raw'][$rawcode]['postcolor']); }
		if ($commandreturn == true) {
			if (isset($GLOBALS['raw'][$rawcode]['ttext'])) { RawEcho($GLOBALS['raw'][$rawcode]['ttext'],$GLOBALS['raw'][$rawcode]['tcolor']); }
			if (isset($GLOBALS['raw'][$rawcode]['tquit']) && (!isset($GLOBALS["handle"]) || $GLOBALS["handle"] == $false)) { xfdie(); }
		}
		else {
			if (isset($GLOBALS['raw'][$rawcode]['ftext'])) { RawEcho($GLOBALS['raw'][$rawcode]['ftext'],$GLOBALS['raw'][$rawcode]['fcolor']); }
			if (isset($GLOBALS['raw'][$rawcode]['fquit']) && (!isset($GLOBALS["handle"]) || $GLOBALS["handle"] == $false)) { xfdie(); }
		}
		if (isset($GLOBALS['raw'][$rawcode]['quit']) && (!isset($GLOBALS["handle"]) || $GLOBALS["handle"] == $false)) { xfdie(); }
	}
}

function RawEcho ($text,$color) {
	xfecho(preg_replace('/#xf#(\w+)#xf#/e', '\$GLOBALS["\\1"]', $text), $color);
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

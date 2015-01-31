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
FORM {
	margin: 0px;
}
INPUT {
	font-size: 10px;
	border:solid 1px #333333;
	background-color: #FFFFFF;
	color:#333333;
}
.ClientText {
	font-weight: bold;
	font-size: 10px;
	color: #FF0000;
}
.ClientList {
	font-size: 10px;
	position: absolute;
	visibility: hidden;
}

.ClientList A {
	padding-left: 15px;
	color: #333333;
	font-size: 12px;
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
<table align="center" class="Section">
  <tr>
    <td class="Section"><span class="TableTitle">Connected Clients</span><br>
    <div id="ClientList" class="ClientList"></div>
    <div id="ClientText" class="ClientText">
    <?
    if (isset($_GET["clientstarted"]) && $_GET["clientstarted"] == "yes") {
    	echo "Client started! ";
    }
    elseif (isset($_GET["clientstopped"]) && $_GET["clientstopped"] == "yes") {
    	echo "Client stopped! ";
    }
    ?>Loading client list...</div>
    </td>
  </tr>
</table>
<table align="center" class="Section">
  <tr>
    <td class="Section"><span class="TableTitle">Add New Client</span><br><form method="GET" action="startclient.php">
        <table cellpadding="0" cellspacing="0">
          <tr>
            <td>IRC server address:</td>
            <td width="10"></td>
            <td><input name="server" type="text" size="25" value="<? echo showContent("server"); ?>"></td>
          </tr>
          <tr>
            <td>IRC server port:</td>
            <td width="10"></td>
            <td><input name="port" type="text" size="4" value="<? echo showContent("port","6667"); ?>"></td>
          </tr>
          <tr>
            <td>IRC channel:</td>
            <td width="10"></td>
            <td><input name="channel" type="text" size="15" value="<? echo showContent("channel"); ?>"></td>
          </tr>
          <tr>
            <td>User nick:</td>
            <td width="10"></td>
            <td><input name="user" type="text" size="15" value="<? echo showContent("user"); ?>"></td>
          </tr>
          <tr>
            <td>Pack number:</td>
            <td width="10"></td>
            <td><input name="pack" type="text" size="4" value="<? echo showContent("pack"); ?>"></td>
          </tr>
          <tr>
            <td align="center" colspan="3"><br>
              <input type="submit" value="GO GET IT!"></td>
          </tr>
        </table>
      </form></td>
  </tr>
</table>
<div class="CopyRight">XDCC Fetcher 0.1 beta. Copyright &copy; 2005 Mark Cilia Vincenti</div>
<div class="CopyRight">Comments &amp; downloads @ http://xdccfetcher.sourceforge.net</div>
<?

include "config.php";

function showContent($field, $default = "") {
	if (isset($_GET[$field])) {
		if ($_GET[$field] == "") {
			return $default;
		}
		else {
			return $_GET[$field];
		}
	}
	else {
		return $default;
	}
}

echo "
<iframe name=\"ClientRef\" src=\"jsgetclientlist.php\" width=\"0\" height=\"0\"></iframe>
<script language=\"JavaScript\">
var oldclients = new Array(9);
var clients = new Array(9);
var oldclientscounter = -2;
var clientscounter = -1;
var flag = 0;
var counter = 0;

function ShowClients() {
	if (oldclientscounter == clientscounter) {
		flag = 0;
		for (counter = 0; counter <= clientscounter; counter++) {
			if (clients[counter] != oldclients[counter]) { flag = 1; }
		}
	}
	else {
		flag = 1;
	}
	if (flag == 1) {
		if (oldclientscounter >= 0) { document.getElementById('ClientList').innerHTML=''; }
		for (counter = 0; counter <= clientscounter; counter++) {
			document.getElementById('ClientList').innerHTML+=(counter + 1) + '<a href=\"showlog.php?log=' + clients[counter] + '\">' + clients[counter] + '</a><a href=\"showlog.php?log=' + clients[counter] + '\">view</a><a href=\"stopclient.php?log=' + clients[counter] + '\">stop</a><br>';
		}
		oldclients = clients;
		oldclientscounter = clientscounter;
		if (clientscounter >= 0) {
			ClientList.style.visibility='visible';
			ClientList.style.position='relative';
			ClientText.style.visibility='hidden';
			ClientText.style.position='absolute';
			document.getElementById('ClientText').innerHTML='';
		}
		else {
			ClientText.style.visibility='visible';
			ClientText.style.position='relative';
			ClientList.style.visibility='hidden';
			ClientList.style.position='absolute';
			document.getElementById('ClientText').innerHTML='There are currently no connected clients';
		}
	}
}
</script></body>
</html>";

/* XDCC Fetcher 0.1 beta (build 20050305)
Copyright (C) 2005  Mark Cilia Vincenti

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

Comments, bugs etc @ markciliavincenti@gmail.com */

?>




<!DOCTYPE html>
<html>
	<head>
		<title>XDCC Fetcher</title>
		<!--<link rel="stylesheet" href="main.css"> -->

		<!-- OMGCSS core CSS -->
		<link href="https://fabienwang.github.io/omgcss/dist/css/omg.css" rel="stylesheet">

	</head>
	<body class="omgcontainer">
	<header class="">
		<h1 class="omgcenter"><a class="title" href="index.php">XDCC Fetcher</a></h1>
	</header>

		<section>
			<table align="center">
				<tr>
					<th>Connected Clients<th>
				</tr>
				<tr>
					<td>
						<div id="ClientList" class="ClientList">Loading client list...</div>
					</td>
				</tr>
			</table>
		</section>
<br>
		<section>
			<table align="center">
				<tr>
					<th>Add New Client<th>
				</tr>
				<tr>
					<td>
						<form method="GET" action="startclient.php" class="omgvertical">
							<input type="text" name="server" placeholder="IRC server address:" value="<?php echo showContent("server"); ?>">
							<input type="number" name="port" placeholder="IRC server port:" min="0" value="<?php echo showContent("port","6667"); ?>">
							<input type="text" name="channel" placeholder="IRC channel:" value="<?php echo showContent("channel"); ?>">
							<input type="text" name="channel" placeholder="User nick:" value="<?php echo showContent("user"); ?>">
							<input type="number" name="pack" placeholder="Pack number:" min="-1" value="<?php echo showContent("pack"); ?>">
							<input type="submit" value="GO GET IT! &raquo;">
						</form>
					</td>
				</tr>
			</table>
		</section>
<br>

		<section>
			<div id="notifCenter" class="omgmsg omgmain">
				<h3 class="omgcenter">Notification</h3>
					<p id="notif">
					<?php
					if (isset($_GET["clientstarted"]) && $_GET["clientstarted"] == "yes") {
						echo "Client started! ";
					}
					elseif (isset($_GET["clientstopped"]) && $_GET["clientstopped"] == "yes") {
						echo "Client stopped! ";
					}
					?>
				</p>
			</div>
		</section>

		<?php
include 'footer.php';

include "config.php";

function showContent($field, $default = "") {
	if (isset($_GET[$field]) && $_GET[$field] != "") {
				return $_GET[$field];
	}
	else {
		return $default;
	}
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

	<script type='text/javascript'>

		setInterval(AjaxGetRequest, 5*1000 );//all 5 seconds

		function AjaxGetRequest() {
			var req = new XMLHttpRequest();

			req.open('GET', 'clientlist.php', true); //true for asynchronous

			req.onreadystatechange = function () {
				if (req.readyState == 4) { //4 == XMLHttpRequest.DONE ie8+
					if((req.status == 200) || (req.status == 304)) {
						var obj = JSON.parse(req.responseText);
						ClientList(obj);
					}
					else {
					}
				}
			};
			req.send(null);
		}

		function ClientList(clients) {
			document.getElementById('ClientList').innerHTML='';

			document.getElementById('notif').innerHTML='';
			document.getElementById('notifCenter').style.visibility="hidden";

			for (counter = 0; counter < clients.length; ++counter) {
				document.getElementById('ClientList').innerHTML+= (counter + 1) + '.';
				document.getElementById('ClientList').innerHTML+= ' ' + clients[counter] + ': <u><a href="showlog.php?log=' + clients[counter] + '">View Log</a></u>';
				document.getElementById('ClientList').innerHTML+= ' <u><a href="stopclient.php?log=' + clients[counter] + '">Stop it</a></u><br>';
			}

			if (document.getElementById('ClientList').innerHTML.length == 0){
				document.getElementById('ClientList').innerHTML+='<span class="omgerr">There are currently no connected clients</span>';
			}
		}
	</script>

	<script src="https://fabienwang.github.io/omgcss/dist/js/omg.js"></script>

</body>
</html>

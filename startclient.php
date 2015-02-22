<?php

include "config.php";

if (substr(php_uname(), 0, 7) == "Windows" || $showrealtime == true) {
	header("Location: client-script.php?" . $_SERVER["QUERY_STRING"]);
}
else {
	shell_exec("php client-script.php server='" . $_GET['server'] . "' port='" . $_GET['port'] . "' channel='" . $_GET['channel'] . "' user='" . $_GET['user'] . "' pack='" . $_GET['pack'] . "' >/dev/null 2>/dev/null &");
	header("Location: index.php?clientstarted=yes&" . $_SERVER["QUERY_STRING"]);
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
<?php

include "config.php";

$logfilename = $logsfolder . $_GET["log"];

if (!@unlink($logfilename)) {
	$delfile = fopen(substr("$logfilename", 0, -4) . ".del", 'a');
	fclose($delfile);
}

header("Location: index.php?clientstopped=yes");

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
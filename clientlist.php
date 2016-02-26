<?php

	$arr = array();

	include "config.php";

	$handler = opendir($logsfolder);

	$counter = 0;
	while ($file = readdir($handler)) {
		if ($file != '.' && $file != '..' && substr($file,-4) == ".log") {
			array_push($arr, $file);
			$counter++;
		}
	}

	echo json_encode($arr);

	closedir($handler);

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

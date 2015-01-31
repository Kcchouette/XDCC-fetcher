<?

include "config.php";

$logfilename = $logsfolder . $_GET["log"];

if (!@unlink($logfilename)) {
	$delfile = fopen(substr("$logfilename", 0, -4) . ".del", 'a');
	fclose($delfile);
}

header("Location: index.php?clientstopped=yes");

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

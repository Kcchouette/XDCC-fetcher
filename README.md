# VERY BASIC INSTRUCTIONS

## Install it!

You must have install php(5) + (apache/nginx/others)

You will need 3 folders in all:

1. A folder (preferably non-web accessible) that holds the downloads. it is defined in config.php. CHMOD to 777 if on *nix, or give IUSR_MACHINENAME read/write access if on Windows (you might need to disable simple file sharing to do this).
2. A folder (preferably non-web accessible) that holds the logs. it is defined in config.php. CHMOD to 777 if on *nix, or give IUSR_MACHINENAME read/write access if on Windows (you might need to disable simple file sharing to do this).
3. (Optionnal) A web-accessible folder (ideally password protected) that holds the scripts (eg: xf)

To check that you have given the correct accesses, you can run install.php into your browser, which is saved in your script directory (eg: http://localhost/xf/install.php)

##Run it!
You can run the project by the following methods:
* a browser ; access it with index.php
* a terminal emulator (konsole, GNOME Terminal, putty, etc) ; launch it with the command ``php client-script.php server='YourIrcServerAdress' port='YourIrcServerPort' channel='YourIrcChannel' user='TheIrcBotXdcc' pack='ThePackNumber'``

## Some problems

In case of any problems, don't hesitate to [open an issue](https://github.com/Kcchouette/XDCC-fetcher/issues/new)

## Stop the deamon (client)

You can stop the client in 3 ways:
* On IRC, with this private message ``STOPXF s``
* Delete its logfile
* Use the logfile deleter script included in the interface

## Note

Note to IIS users:
IIS has a CGI timeout that overrides the PHP setting. You can change this by doing the following:

Click Start -> Settings -> Control Panel -> Administrative tools -> Internet Information services
Right click on the 'Web Sites' folder, and click properties.
Under the Home Directories Tab, click 'Configuration...' at the bottom right.
Go to the 'Process Options' tab, and change the 'CGI Script Timeout' to a larger value.

## License

XDCC Fetcher
Copyright (C) 2015 Kcchouette

Based on XDCC Fetcher 0.1 beta (build 20050305)
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

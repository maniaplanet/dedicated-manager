dedicated-manager
=================
The dedicated manager is a maniaplanet dedicated server web manager.
This tools allows you to start, configure and manage easily your dedicated server.

Installation
------------
* Download the archive here: https://github.com/maniaplanet/dedicated-manager/releases . It contains the DedicatedManager itself, and a SQL script to create the database.
* Unzip it wherever you want on your server
* Use your SQL Manager (phpMyAdmin, HeidiSQL, etc.) to import Manager.sql, this will create the database and its tables
* Create a MySQL user and grant SELECT, INSERT, UPDATE, DELETE to Manager database
* Create the alias manager on your web server. This alias must linked to the www folder in DedicatedManager
* Give write access to thumbnails folder in www/media/images/thumbnails
* Create your app.ini file
* Edit the DedicatedManager's config file (DedicatedManager/config/app.ini) and give the correct values to the following parameters:
```
application.URL
database.user
database.password
DedicatedManager\Config.dedicatedPath
DedicatedManager\Config.manialivePath
```

Developers
-----------
In order have a working version, you need to have [Composer](https://getcomposer.org/).

* Clone our repository: `$ git clone git@github.com:maniaplanet/dedicated-manager.git`
* Go in `dedicated-manager` directory: `$ cd dedicated-manager`
* Run composer to update the dependencies: `$ composer install`
* Use your SQL Manager (phpMyAdmin, HeidiSQL, etc.) to import Manager.sql, this will create the database and its tables
* Create a MySQL user and grant SELECT, INSERT, UPDATE, DELETE to Manager database
* Create an Apache alias, or a symbolic link to www folder
* Give write access to thumbnails folder in www/media/images/thumbnails
* Create your app.ini file
* Edit the DedicatedManager's config file (DedicatedManager/config/app.ini) and give the correct values to the following parameters:
```
application.URL
database.user
database.password
DedicatedManager\Config.dedicatedPath
DedicatedManager\Config.manialivePath
```

Secure access with OAuth2
-------------------------
If you want to secured access to your Dedicated Manager page, you can enable OAuth2 authentication.
With this system only users with ManiaPlanet account allowed in your app.ini file.

* Create a web service account on your player page (https://player.maniaplanet.com/webservices/)
* Create an application linked to your web service account
* Edite your app.ini file and set the following values:
```
DedicatedManager\Config.maniaConnect = On

webservices.username = 'Your API Username'
webservices.password = 'Your API Password'

DedicatedManager\Config.admins[] = 'Admin1Login'
DedicatedManager\Config.admins[] = 'Admin2Login'
DedicatedManager\Config.admins[] = 'Admin3Login'
```
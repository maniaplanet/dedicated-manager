dedicated-manager
=================
The dedicated manager is a maniaplanet dedicated server web manager.
This tools allows you to start, configure and manage easily your dedicated server.

Installation
------------
* Download the archive here: https://github.com/maniaplanet/dedicated-manager/releases . It contains the DedicatedManager itself, and a SQL script to create the database.
* Unzip it wherever you want on your server
* Use your SQL Manager (phpMyAdmin, HeidiSQL, etc.) to import Manager.sql, this will create the database and its tables
* Create the alias manager on your web server. This alias must linked to the www folder in DedicatedManager
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
* Create an Apache alias, or a symbolic link to www folder
* Create your app.ini file
* Edit the DedicatedManager's config file (DedicatedManager/config/app.ini) and give the correct values to the following parameters:
```
application.URL
database.user
database.password
DedicatedManager\Config.dedicatedPath
DedicatedManager\Config.manialivePath
```

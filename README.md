# Installation guide and instructions for setting up the environment and running the app on Windows 10 64-bit:

## Download and install the following things

### PHP 7.4
https://windows.php.net/downloads/releases/archives/php-7.4.0-Win32-vc15-x64.zip

### Apache

https://www.apachelounge.com/download/

I used version 2.4.58 win64 but I see .59 is only available now

### VC Redistributable

https://aka.ms/vs/17/release/VC_redist.x64.exe

### MongoDB server community edition

https://www.mongodb.com/try/download/community

### MongoDB PHP Extension for Windows

https://github.com/mongodb/mongo-php-driver/releases/download/1.18.0/php_mongodb-1.18.0-7.4-nts-x64.zip

### Composer

https://getcomposer.org/Composer-Setup.exe
<br />
## Setup

### I found this guide very helpful in installing PHP/Apache on Windows 10 64bit

https://www.sitepoint.com/how-to-install-php-on-windows/

### 1. Setup Apache

Ensure the necessary VC redistributable for Apache is installed

Extract the Apache24 folder from the Apache zip file to the root of your C drive

### 2. Setup PHP

Create a "php" folder in the root of your C drive

Extract the contents of the php zip into this folder

Copy and rename "php.ini-development" from the php folder to the same folder and rename it to "php.ini"

Open the php.ini file in a text editor/notepad and search for "extension"

Find the extension entries for pdo_sqlite and sqlite3. Enable extensions by removing the ";" from them. It should look like the below:

extension=pdo_sqlite
extension=sqlite3

### 3. Add C:\php to the path environment variable

Click Windows Start and type "enviroment" and then click Edit the system environment variables.

Click Enviroment Variables at the bottom. In the section below find the "Path variable and click on it and then click Edit. Click New on the next screen and type "C:\php" in the field

Click OK

### 4. Configure PHP 7.4 as an Apache module

Make sure Apache is not running. Go to the Apache folder and then the "conf" folder

Open the file httpd.conf in a text editor like Notepad

Add this to the bottom of the file:

\# PHP7 module<br/>
PHPIniDir "C:/php"<br/>
LoadModule php7_module "C:/php/php7apache2_4.dll"<br/>
AddType application/x-httpd-php .php

Find this section in the file:

<IfModule dir_module>
    DirectoryIndex index.html
</IfModule>

And change to:

<IfModule dir_module>
    DirectoryIndex index.php index.html
</IfModule>

### 5. Clone repo and download application from Github

Clone or download the repo to your machine

Copy the folders "test_1" and "test_2" to the htdocs folder inside the Apache folder

### 6. Setup MongoDB PHP Driver

Ensure MongoDB Server Community and Composer is installed from above instructions

Ensure the MongoDB PHP extension is downloaded from above instructions

Extract the "php_mongodb.dll" from the MongoDB extension file and copy to C:\php\ext

Open the command line/cmd and change the directory/cd to "C:\Apache24\htdocs\test_1"

Enable the MongoDB extension in the php.ini file by searching for "extension" and adding the line "extension=php_mongodb.dll" just above the ";extension=mysqli" line

Now run "composer install" in cmd. This should install the MongoDB composer dependency

### 7. Change File Upload maximum size in php.ini

Open php.ini in a text editor/notepad and search for "upload_max_filesize" and change the amount to "100M"

Search for "post_max_size" and change it also to "100M"
<br />
## Running the application:

Open the command line and change the directory using cd to C:\Apache24\bin

Type "httpd" and hit enter to run Apache

Open a browser such as Google Chrome and enter the web address to run the app

For Test 1:

localhost/test_1/

For Test 2:

localhost/test_2/

MongoDB Compass installed with MongoDB Server Community Edition can be used to view the MongoDB database for Test 1

SQLite Viewer Web App can be used to view the SQLite database file in the test_2/database folder for Test 2
<br/>
Link: https://sqliteviewer.app/

# GPS_REST_API
A simple web app for live visualization of a vehicle's trajectory based on the VIN number.

# Current Status of the Project
1. Currently, a web URL writes vehicle's lat, long with timestamp to a database.
2. Another webpage lets user select VIN number from available list of VINs. Then the webpage redirects to a map page where trajectory of the vehicle can be seen. 

# Limitations
1. Currently, the map can only show one vehicle's trajectory at the same time.
2. The map page needs to refreshed manually to get new coordinates of the vehicle.


# Installation of the WebAPP on a new server
## Install Apache, PHP, MYSQL

### Installing Apache2
1. ```sudo apt install -y apache2```
2. Test your Apache2 installation by going to http://127.0.0.1/
### Installing Database Server
We will install mariadb version of MYSQL
1. ```sudo apt install -y mariadb-server mariadb-client```

2. Secure your db: ```sudo mysql_secure_installation```. Make sure to remember the password you enter.  

### Installing PHP and PHP-extensions
1. ```sudo apt install -y php php-{common,mysql,xml,xmlrpc,curl,gd,imagick,cli,dev,imap,mbstring,opcache,soap,zip,intl}```
2. Restart the Apache to load PHP ```sudo systemctl restart apache2```
3. Testing your PHP installation:
a. Create a test php script at 
```
sudo gedit /var/www/html/info.php
```
Add the following text to `info.php`

```
<?php phpinfo(); ?>
```
save it, and and then go to http://127.0.0.1/info.php on your browser. You should be able to some information about PHP.


## Create Database, Tables

We need to create a database now:

1. Login to mysql
```
sudo mysql -u root -p
```
enter the password you had used while securing your database earlier.
 
2. Create a database
```
CREATE database circledb;  
```
3. Create a user named `circles` for this database.
```
CREATE USER 'circles'@'localhost' IDENTIFIED WITH mysql_native_password BY 'D8ab@seP@55w0rd';
GRANT ALL PRIVILEGES ON circledb.* TO 'circles'@'localhost';
FLUSH PRIVILEGES;
EXIT;

```
4. Let's test database connection with PHP. Create a file:
```
sudo gedit /var/www/html/database_test.php
```
and enter the following content to the file and save it:
```
<?php

$conn = new mysqli('localhost', 'circles', 'D8ab@seP@55w0rd', 'circledb');

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

echo "Database connection was successful";

?>
```

Once you save it and go to http://127.0.0.1/database_test.php.
On the webpage you should see a text written: **Database connection was successful**.

5. Create a table for saving vehicle data. Go to your MYSQL again but this time login with user circles and corresponding password you create just a moment ago

```
 mysql -p -u circles circledb
```
Note: if the above command doesn't work, try `mysql -h 127.0.0.1 -P 3306 -p -u circles circledb`
enter the password `D8ab@seP@55w0rd`.

Create a table:
```
CREATE TABLE `GPSDB` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `VIN` varchar(255) DEFAULT NULL,
  `GpsTime` double DEFAULT NULL,
  `SysTime` double DEFAULT NULL,
  `Latitude` double DEFAULT NULL,
  `Longitude` double DEFAULT NULL,
  `Altitude` double DEFAULT NULL,
  `Status` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=729874 DEFAULT CHARSET=latin1;

```


 
6. Clone this repo to your 
```
cd /var/www/html/
git clone https://github.com/jmscslgroup/GPS_REST_API
```
You can check the webapp at http://127.0.0.1/GPS_REST_API/viz.php


# Usage
1. On Raspberry PI, following command writes lat, long to the database 
```
curl --connect-timeout 5 -k https://engr-sprinkle01s.catnet.arizona.edu:8080/rest.php?circles,1f234567890123,1594266088.600000,1594266088.600000,-86.7796743,36.1316557,165.3,A
```

The format for GET request is VIN, GpsTime, SysTime, Latitude, Longitude, Altitude, Status.

2. For visualization, go to `viz.php` and select VIN number. Then the webpage is redirected to `map.php` where you can see the lat,long coordinates.


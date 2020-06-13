# Stock and Inventory Management System
This is a stock and inventory manager - done as a hobby project and is written in PHP and MySQL.

## Demo : https://inventory-mgmnt-system.herokuapp.com/

### Local setup :

1. Create a folder called *inventory* at the desired location of your local server
2. git clone this repository into this folder

### Database setup :

Head over to phpmyadmin or mysql interface on your terminal and do the following:
1. Create a database and
2. Import sql from database/Inventory.sql to load the tables
3. Go to dist/includes/dbcon.php and admin/dbcon.php and edit the following line in each of these files
```php
$con = mysqli_connect("<database-host-path:<port>","<username>","<password>","<database-name>");
```

#### Login to administrative portal:

Use the following url and credentials:

Url : https://inventory-mgmnt-system.herokuapp.com/admin or localhost/inventory/admin/ (If you are running it on local host)

Username : admin

Password : admin

![alt text](https://github.com/Raxerz/stock-and-inventory-management-system/blob/master/screenshots/screen-1.png "Admin Login")

#### Add branch details:

![alt text](https://github.com/Raxerz/stock-and-inventory-management-system/blob/master/screenshots/screen-3.png "Add Branch Details")

#### Associate user with the branch :

![alt text](https://github.com/Raxerz/stock-and-inventory-management-system/blob/master/screenshots/screen-2.png "Associate user with Branch")

### Login to the branch portal:

Use the following url and credentials:

   Url : https://inventory-mgmnt-system.herokuapp.com/ or localhost/inventory/ (If you are running it on local host)
   
   Username : admin 
   
   Password : admin
   
   Branch : <Select your branch>

![alt text](https://github.com/Raxerz/stock-and-inventory-management-system/blob/master/screenshots/screen-5.png "Login to branch details")

### Use the portal to add customers, track inventory and generate invoices

![alt text](https://github.com/Raxerz/stock-and-inventory-management-system/blob/master/screenshots/screen-4.png "Access Portal")

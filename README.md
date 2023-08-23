# myumkm-regular
This repository holds common files used for regular myumkm.com website projects

# Requirement
1. Composer
2. PHP 8.0+
3. CodeIgniter 4 Framework
4. MySQL DBMS

# Installation
1. Clone this repository to your computer
2. Import the MySQL file located in the ".install" folder to your computer's local database
3. Open a terminal and navigate to the directory "CORE"
4. Run the command "composer update" to install dependencies
5. Open the file on "CORE/app/Config/Paths.php" and set the system path on line 26. Navigate to the correct "vendor" folder location
6. Open a terminal and navigate to the ".local" folder, then run the command "php spark serve" to run the application on your local system
    - You can also use the "php spark start" command to use the default port set in the ".env" file

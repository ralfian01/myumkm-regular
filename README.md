<h1>MyUMKM Regular Website</h1>
This repository holds common files used for regular myumkm.com website projects

<h2>Requirement</h2>
1. Composer </br>
2. PHP 8.0+ </br>
3. CodeIgniter 4 Framework </br>
4. MySQL DBMS </br>

<h2>Installation</h2>
1. Clone this repository to your computer </br>
2. Import the MySQL named <b>"submyu_seed.sql.zip"</b> file located in the <b>".install"</b> folder to your computer's local database </br>
3. Open a terminal and navigate to the "CORE" directory </br>
4. Run the command below to install dependencies
<blockquote>
    composer update
</blockquote>

<h2>Run Program</h2>
Open a terminal and navigate to the <b>".local"</b> folder, 
<blockquote>
    cd CORE/.local
</blockquote>
Then run the command below to run the application on your local system </br>
<b>Run the program with default port (8080)</b>
<blockquote>
    php spark serve
</blockquote>
<b>Run the program using the port set in the <b>.env</b> file</b>
<blockquote>
    php spark start
</blockquote>

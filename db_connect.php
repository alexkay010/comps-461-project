<?php

//constant variables for connecting to the database
define("DATABASE_SERVER", "localhost");
define("DATABASE_USERNAME", "root");
define("DATABASE_PASSWORD", "usbw");
define("DATABASE_NAME", "userdb_33704");

//creating a connection object and storing inside a $con variable
$con = new mysqli(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

if (!$con) {
    echo '<h3 class="text-danger text-center display-4">An Error Occured while connecting to the database</h3>';
    die();
}
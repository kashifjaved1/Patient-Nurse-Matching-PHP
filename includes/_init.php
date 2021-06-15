<?php
session_start();
session_regenerate_id(true);

require 'classes/database.php';
// require 'classes/nurse.php';
// require 'classes/patient.php';
require 'classes/friend.php';

// DATABASE CONNECTIONS
$db_obj = new Database();
$db_connection = $db_obj->dbConnection();

// PATIENT OBJECT
//$pat_obj = new User($db_connection);

// USER OBJECT
//$nurse_obj = new User($db_connection);

// FRIEND OBJECT
$frnd_obj = new Friend($db_connection);
?>
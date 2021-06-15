<?php
session_start();
session_regenerate_id(true);

require 'classes/database.php';
require 'classes/nurse.php';
require 'classes/n_friend.php';

// DATABASE CONNECTIONS
$db_obj = new Database();
$db_connection = $db_obj->dbConnection();

// PATIENT OBJECT
$nurse_obj = new Nurse($db_connection);

// FRIEND OBJECT
$frnd_obj = new nFriend($db_connection);
?>
<?php 

$db = null;

function getDatabaseConnection() {
    global $db;
    $db = new PDO('sqlite:' . __DIR__ . '/linkito.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);    

    if (!$db) {
        die("Could not connect to the database.");
    }
    return $db;
}

function closeDatabaseConnection() {
    global $db;
    if ($db) {
        $db = null;
    }
}
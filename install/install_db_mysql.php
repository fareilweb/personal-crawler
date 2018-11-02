<?php

/* Dependencies */
require dirname(__FILE__) . '/../Config.php';

/* Database connection */
$mysqli = new mysqli(
    Config::$mysql_host, 
    Config::$mysql_user, 
    Config::$mysql_pass
);     
if($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $this->mysqli->connect_error;
    exit;
}

/* Queries */
$queries = [
    0 => "CREATE DATABASE personal_crawler COLLATE utf8mb4_unicode_520_ci;",
    1 => "USE personal_crawler",
    2 => "CREATE TABLE webpages (
	    id int(11) NOT NULL,
	    uri text NOT NULL,
	    lang varchar(8) DEFAULT NULL,
	    title varchar(510) DEFAULT NULL,
	    h1 varchar(510) DEFAULT NULL,
	    h2 varchar(510) DEFAULT NULL,
	    metakeywords varchar(255) DEFAULT NULL,
	    metadescription varchar(255) DEFAULT NULL,
	    top_word varchar(128) DEFAULT NULL,
	    response_code int(3) DEFAULT NULL,
	    timestamp int(12) DEFAULT NULL
	  ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;",
    3 => "ALTER TABLE webpages ADD PRIMARY KEY (id);",
    4 => "ALTER TABLE webpages MODIFY id int(11) NOT NULL AUTO_INCREMENT;",
    5 => "ALTER SCHEMA personal_crawler DEFAULT COLLATE utf8mb4_unicode_520_ci;",
    6 => "ALTER TABLE webpages COLLATE = utf8mb4_unicode_520_ci;"
];

/* Queries Error Messages */
$errors_messages = [
    0 => 'Failded to create database "personal_crawler".',
    1 => 'Failed to select database "personal_crawler"',
    2 => 'Failed to create table "webpages".',
    3 => 'Failed to set primary key on table "webpages".',
    4 => 'Failed to set auto increment on "in" in table "webpages".',
    5 => 'Failed to set database COLLATE',
    6 => 'Failed to set table COLLATE'
];

/* Execute queries */
foreach ($queries as $key => $query) {
    if(!$mysqli->query($query)) {
	    echo $errors_messages[$key] . "<br>" . $mysqli->error;
	    $mysqli->close();
	    exit;
    }
}

/* Success! */
echo "Database for personal_crawler was successful created.";


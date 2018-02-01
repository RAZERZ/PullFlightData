<?php
/**
 * This script gets all the entries in the database and places them in a csv file
 * The csv can be used to import into phpvms
 * If a tailnumber doesn't exist in your phpvms, that flight will not be imported
 */

//Create database connection
$mysql = new mysqli('localhost', 'root', '', 'FlightAwarePuller');

//Use the SELECT statement to select everything in the database
$query = $mysql->query("SELECT * FROM flights");

//Open the csv file to place the data into with write mode enabled
$csv = fopen("schedules.csv", "w");

//Loops all the rows in the database and writes them to the csv file
while ($results = $query->fetch_assoc()) {
    fputcsv($csv, $results);
}
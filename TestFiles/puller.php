<?php
/**
 * This is only to test the functionality of the program
 * It uses the AirportBoards.json file which is included in the /TestFiles directory
 * It contains outdated information, and is again, just used as an example
 * It will print out to the database, this is to demonstrate that there won't be duplicates
 */

//We'll create a class to represent the API class
//And a function that will take in the json file and decode it into an array which represents the authentication part
class jsonExample {
    public function importer($jsonFile) {
        $json = file_get_contents($jsonFile);
        return $decodedJson = json_decode($json, true); //Gives us the array, like the puller.php file
    }
}

//Now we can create an instance of the class and use the function to return the data
$newSession = new jsonExample();

//Lets get the array!
$decodedResult = $newSession->importer("AirportBoards.json");

/**Now the rest of the code is imported from the puller.php*/
//Create a function that takes in the decoded json and sends it to the database
function flights($decodedResult, $position) { //Position is either 'arrival' or 'departures'

    //We need to get the flights from the multidimensional array
    $positionState = $decodedResult['AirportBoardsResult']["$position"]['flights'];

    //Foreach position State, we want to get the info and send them to the database
    foreach($positionState as $flight) {

        //We need to make a connection to the database, we use SQL
        $mysql = new mysqli('localhost', 'root', '', 'FlightAwarePuller');

        //We should set some variables to make it easier
        $callsign = $flight['ident'];
        $depIcao = $flight['origin']['code'];
        echo "$depIcao<br>";
        $arrIcao = $flight['destination']['code'];
        $tailnumber = $flight['tailnumber'];
        $distance = $flight['distance_filed']; //We use the filed one to match the irl schedules, not irl events
        $deptime = date('H:i', strtotime($flight['filed_departure_time']['time'])); //It is recommended to use 24 hour format
        $arrtime = date('H:i', strtotime($flight['filed_arrival_time']['time']));
        $flighttime = gmdate('H:i', $flight['filed_ete']); //We convert it from Unix time stamp to human readable
        $route = $flight['route']; //Doesn't always exist, if it doesn't, we'll set it to nothing to avoid issues when sending to database
        $altitude = $flight['filed_altitude']; //Same as above
        if(empty($route)) $route = "";
        if(empty($altitude)) $altitude = "";
        $currDay = date("N"); //This is to get the current day of the week in numbers
        global $airline; //We'll also be using the '$airline' variable

        //Now we can start pushing to the database!
        //First of all, we need to check if the callsign already exits
        //If it does, we'll just update the data in the database rather than create two entries
        //We also need to increment the days of the week the flight is flown if it exits

        if($mysql->query("SELECT flightnum FROM flights WHERE flightnum = '$callsign';")->num_rows > 0) {
            $mysql->query("UPDATE flights SET depicao = '$depIcao', arricao = '$arrIcao', route = '$route', tailnum = '$tailnumber', flightlevel = '$altitude', distance = '$distance', deptime = '$deptime', arrtime = '$arrtime', flighttime = '$flighttime', daysofweek = CONCAT(daysofweek, '$currDay') WHERE flightnum = '$callsign';");
        }
        else {
            $mysql->query("INSERT INTO flights VALUES('$airline', '$callsign', '$depIcao', '$arrIcao', '$route', '$tailnumber', '$altitude', '$distance', '$deptime', '$arrtime', '$flighttime', 'Pulled using Ramis free FlightAwarePuller', '160', 'P', '$currDay', '1');");
        }

    }

}

flights($decodedResult, "arrivals");
flights($decodedResult, "departures");
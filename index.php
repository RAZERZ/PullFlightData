<form action="" method="post">
    3 letter airline code to pull data from: <input type="text" name="airline">
    ICAO to pull data from: <input type="text" name="ICAO"> <br>
    <input type="submit">
</form>

<?php

if(isset($_POST['airline'])) {
    $airline = trim($_POST['airline']);

    if(isset($_POST['ICAO'])) {
        $ICAO = trim($_POST['ICAO']);
    
    $apiUserId = "yourFlightAwareID";
    $apiKey = "yourFlightAwareApiKey";
    $apiUrl = "https://flightxml.flightaware.com/json/FlightXML3/";
    
    $queryArray = array(
        'airport_code' => $ICAO,
        'filter' => 'airline:' . $airline
    );
    
    $queryUrl = $apiUrl . 'AirportBoards?' . http_build_query($queryArray);
    
    $ch = curl_init($queryUrl);
    curl_setopt($ch, CURLOPT_USERPWD,  $apiUserId . ':' . $apiKey);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $queryResult = curl_exec($ch);

    curl_close($ch);
    
    $queryResultJsonDecode = json_decode($queryResult, true);
        
$flightsArray = $queryResultJsonDecode['AirportBoardsResult']['arrivals']['flights'];
$flightnumber = $queryResultJsonDecode['AirportBoardsResult']['arrivals']['flights']['0']['ident']; 
$sasCallsign = array();
$totalArray = array('code','flightnum','depicao','arricao','route','aircraft','flightlevel','distance','deptime','arrtime','flighttime','notes','price','flighttype','daysofweek','enabled');

$fp = fopen('schedules.csv', 'a');

fputcsv($fp, $totalArray);

fclose($fp);
    
foreach($flightsArray as $flight) {
    $sasCallsign[] = $flight['ident'];
    }
    
    $i = 0;
    
    foreach($sasCallsign as $callsigns) {
        ${"callsign$i"} = $callsigns;
        $i++;
    }   
    
    $x = count($sasCallsign);
    $x--;
?>

<center>
    <p style="font-size:120%;"><b>Arrivals</b></p><hr>
</center>

<?
    
    for($x; $x >= 0; $x--) {
        
        $dep = $queryResultJsonDecode['AirportBoardsResult']['arrivals']['flights']["$x"]['origin']['code'];
        $arr = $queryResultJsonDecode['AirportBoardsResult']['arrivals']['flights']["$x"]['destination']['code'];
        $depTime = date("H:i", strtotime($queryResultJsonDecode['AirportBoardsResult']['arrivals']['flights']["$x"]['filed_departure_time']['time']));
        $arrTime = date("H:i", strtotime($queryResultJsonDecode['AirportBoardsResult']['arrivals']['flights']["$x"]['filed_arrival_time']['time']));
        $distance = $queryResultJsonDecode['AirportBoardsResult']['arrivals']['flights']["$x"]['distance_filed'];
        $aircraft = $queryResultJsonDecode['AirportBoardsResult']['arrivals']['flights']["$x"]['full_aircrafttype'];
        $tailnumber = $queryResultJsonDecode['AirportBoardsResult']['arrivals']['flights']["$x"]['tailnumber'];
        $airline = $queryResultJsonDecode['AirportBoardsResult']['arrivals']['flights']["$x"]['airline'];
        $fnumber = $queryResultJsonDecode['AirportBoardsResult']['arrivals']['flights']["$x"]['flightnumber'];
        $totalDepTime = new DateTime($depTime);
        $totalArrTime = new DateTime($arrTime);
        $interval = $totalDepTime->diff($totalArrTime);

        $totalArray = array($airline, $fnumber, $dep, $arr, "N/A", $tailnumber, "0", $distance, $depTime, $arrTime, "N/A", "Pulled using Rami's code plugin", "160", "P", "123456", "1");
        
        $fp = fopen('schedules.csv', 'a');
        
        fputcsv($fp, $totalArray);
        
        fclose($fp);
        
        error_reporting( error_reporting() & ~E_NOTICE );
        
?>
<center>
<table border="1" style="margin-top:1%;">
    <tr>
        <th>Airline</th>
        <th>Flightnumber</th>
        <th>Departure</th>
        <th>Arrival</th>
        <th>Route</th>
        <th>Tailnumber</th>
        <th>FlightLevel</th>
        <th>Distance</th>
        <th>Departure Time</th>
        <th>Arrival Time</th>
        <th>Flight Time</th>
        <th>Notes</th>
        <th>Price</th>
        <th>Flight Type</th>
        <th>Daysofweek</th>
        <th>Enabled</th>
    </tr>
    <tr>
        <td><? print_r("$airline"); ?></td>
        <td><? print_r("$fnumber"); ?></td>
        <td><? print_r("$dep"); ?></td>
        <td><? print_r("$arr"); ?></td>
        <td>N/A</td>
        <td><? print_r("$tailnumber"); ?></td>
        <td>0</td>
        <td><? print_r("$distance"); ?></td>
        <td><? print_r($depTime); ?></td>
        <td><? print_r($arrTime); ?></td>
        <td><? echo $interval->format("%H" . ":" . "%i"); ?></td>
        <td></td>
        <td>160</td>
        <td>P</td>
        <td>123456</td>
        <td>1</td>
    </tr>
</table>
</center>
<?php
        
    }
    

    $x = count($sasCallsign);
    $x--;
?>

<center>
    <p style="font-size:120%;"><b>Departures</b></p><hr>
</center>

<?
    
    for($x; $x >= 0; $x--) {
        
        $dep = $queryResultJsonDecode['AirportBoardsResult']['departures']['flights']["$x"]['origin']['code'];
        $arr = $queryResultJsonDecode['AirportBoardsResult']['departures']['flights']["$x"]['destination']['code'];
        $depTime = date("H:i", strtotime($queryResultJsonDecode['AirportBoardsResult']['departures']['flights']["$x"]['filed_departure_time']['time']));
        $arrTime = date("H:i", strtotime($queryResultJsonDecode['AirportBoardsResult']['departures']['flights']["$x"]['filed_arrival_time']['time']));
        $distance = $queryResultJsonDecode['AirportBoardsResult']['departures']['flights']["$x"]['distance_filed'];
        $aircraft = $queryResultJsonDecode['AirportBoardsResult']['departures']['flights']["$x"]['full_aircrafttype'];
        $tailnumber = $queryResultJsonDecode['AirportBoardsResult']['departures']['flights']["$x"]['tailnumber'];
        $fnumber = $queryResultJsonDecode['AirportBoardsResult']['departures']['flights']["$x"]['flightnumber'];
        $totalDepTime = new DateTime($depTime);
        $totalArrTime = new DateTime($arrTime);
        $interval = $totalDepTime->diff($totalArrTime);
        
        $totalArray = array($airline, $fnumber, $dep, $arr, "N/A", $tailnumber, "0", $distance, $depTime, $arrTime, "N/A", "Pulled using Rami's code plugin", "160", "P", "123456", "1");
        
        $fp = fopen('schedules.csv', 'a');
        
        fputcsv($fp, $totalArray);
        
        fclose($fp);
        
        error_reporting( error_reporting() & ~E_NOTICE );
?>
<center>
<table border="1" style="margin-top:1%;">
    <tr>
        <th>Airline</th>
        <th>Flightnumber</th>
        <th>Departure</th>
        <th>Arrival</th>
        <th>Route</th>
        <th>Tailnumber</th>
        <th>FlightLevel</th>
        <th>Distance</th>
        <th>Departure Time</th>
        <th>Arrival Time</th>
        <th>Flight Time</th>
        <th>Notes</th>
        <th>Price</th>
        <th>Flight Type</th>
        <th>Daysofweek</th>
        <th>Enabled</th>
    </tr>
    <tr>
        <td><? print_r("$airline"); ?></td>
        <td><? print_r("$fnumber"); ?></td>
        <td><? print_r("$dep"); ?></td>
        <td><? print_r("$arr"); ?></td>
        <td>N/A</td>
        <td><? print_r("$tailnumber"); ?></td>
        <td>0</td>
        <td><? print_r("$distance"); ?></td>
        <td><? print_r("$depTime"); ?></td>
        <td><? print_r("$arrTime"); ?></td>
        <td><? print_r($interval->format("%H" . ":" . "%I")); ?></td>
        <td></td>
        <td>160</td>
        <td>P</td>
        <td>123456</td>
        <td>1</td>
    </tr>
</table>
</center>
<?php   
        }
    }
}

?>

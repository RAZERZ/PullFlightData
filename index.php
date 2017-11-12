<form action="" method="post">
    <input type="text" name="ICAO">
</form>

<?php

if(isset($_POST['ICAO'])) {
    $ICAO = trim($_POST['ICAO']);
    
    $apiUserId = "yourFlightAwareUserID";
    $apiKey = "yourFlightAwareAPiKey";
    $apiUrl = "https://flightxml.flightaware.com/json/FlightXML3/";
    
    $queryArray = array(
        'airport_code' => $ICAO,
    );
    
    $queryUrl = $apiUrl . 'AirportBoards?' . http_build_query($queryArray);
    
    $ch = curl_init($queryUrl);
    curl_setopt($ch, CURLOPT_USERPWD,  $apiUserId . ':' . $apiKey);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $queryResult = curl_exec($ch);

    curl_close($ch);
    
    $queryResultJsonDecode = json_decode($queryResult, true);

    //TODO: Create loop that gets the above info + departure + arrival, the loop shall be a for loop and the int to loop is how many arrivals. Check if the airline is SAS, if not, don't echo :p
    
$flightsArray = $queryResultJsonDecode['AirportBoardsResult']['arrivals']['flights'];
$flightnumber = $queryResultJsonDecode['AirportBoardsResult']['arrivals']['flights']['0']['ident']; 
$sasCallsign = array();
    
foreach($flightsArray as $flight) {
    $flightnumber = $flight['ident'];
    
    if(substr($flightnumber, 0, 3) == 'SAS') {
        $sasCallsign[] = $flightnumber;
        }
    }
    
    $i = 0;
    foreach($sasCallsign as $callsigns) {
        ${"callsign$i"} = $callsigns;
        $i++;
    }   
    
    $x = count($sasCallsign);
    $x--;
    
    for($x; $x >= 0; $x--) {
        $queryArray = array(
            'ident' => ${"callsign$x"},
            'howMany' => 1
        );
        
        echo ${"callsign$x"} . " ";
    
$flightInfoStatus = $apiUrl . 'FlightInfoStatus?' . http_build_query($queryArray);
        $ch1 = curl_init($flightInfoStatus);
        curl_setopt($ch1, CURLOPT_USERPWD, $apiUserId . ':' . $apiKey);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        $flightInfoStatusResult = curl_exec($ch1);
        curl_close($ch1);
        $flightInfoStatusDecode = json_decode($flightInfoStatusResult, true);
        
        print_r($flightInfoStatusDecode);
        
        $dep = $flightInfoStatusDecode['FlightInfoStatusResult']['flights']['0']['origin']['code'];
        $arr = $flightInfoStatusDecode['FlightInfoStatusResult']['flights']['0']['destination']['code'];
        $depTime = $flightInfoStatusDecode['FlightInfoStatusResult']['flights']['0']['filed_departure_time']['time'];
        $depDate = $flightInfoStatusDecode['FlightInfoStatusResult']['flights']['0']['filed_departure_time']['date'];
        $arrTime = $flightInfoStatusDecode['FlightInfoStatusResult']['flights']['0']['filed_arrival_time']['time'];
        $arrDate = $flightInfoStatusDecode['FlightInfoStatusResult']['flights']['0']['filed_arrival_time']['date'];
        $distance = $flightInfoStatusDecode['FlightInfoStatusResult']['flights']['0']['distance_filed'];
        $aircraft = $flightInfoStatusDecode['FlightInfoStatusResult']['flights']['0']['full_aircrafttype'];
        print_r("$dep" . " $depDate " . "@ " . "$depTime " . " -> " . "$arr" . " $arrDate " . "@ " . "$arrTime" . " Distance Filed: " . "$distance " . "$aircraft ");
    }
}
?>

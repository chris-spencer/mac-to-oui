<?php
	

# START A TIMER (USED TO CALCULATE EXECUTION TIME)
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

## MEMORY FUNCTION
$mem_peak = memory_get_peak_usage();

## FILENAME ##
$filename = "oui.txt";

## GET QUERY STRING
$query =  $_GET['mac'];
$refresh =  $_GET['refresh'];
$searchterm =  $query;

## CHECK FOR MANUAL DATA REFRESH
if ($refresh == "yes") {
	file_put_contents($filename, fopen("http://standards-oui.ieee.org/oui.txt", 'r'));
}

## CHECK IF FILE ACTUALLY EXISTS IF NOT DOWNLOAD IT
if (file_exists($filename))  { 
    ## THE FILE EXISTS LETS DO NOTHING
	} else { 
    file_put_contents($filename, fopen("http://standards-oui.ieee.org/oui.txt", 'r'));
} 

## CONVERT TO UPPER CASE TO EASE FORMATTING LATER
$mac = strtoupper($query);

## CHECK WE GOT SOMETHING TO DO
if ($searchterm == "") {
	echo "NO MAC DETECTED";
	die;
}

## STRIP STRAY MAC FORMATTING CHARACTERS
$mac = preg_replace("/[^A-Z0-9]/", "", $mac);

$mac = str_replace(":", "", $mac);
$mac = str_replace("-", "", $mac);
$mac = str_replace(".", "", $mac);

## STRIP CHARACTERS OUTSIDE OF HEX RANGE
$mac = str_replace("G", "", $mac);
$mac = str_replace("H", "", $mac);
$mac = str_replace("I", "", $mac);
$mac = str_replace("J", "", $mac);
$mac = str_replace("K", "", $mac);
$mac = str_replace("L", "", $mac);
$mac = str_replace("M", "", $mac);
$mac = str_replace("N", "", $mac);
$mac = str_replace("O", "", $mac);
$mac = str_replace("P", "", $mac);
$mac = str_replace("Q", "", $mac);
$mac = str_replace("R", "", $mac);
$mac = str_replace("S", "", $mac);
$mac = str_replace("T", "", $mac);
$mac = str_replace("U", "", $mac);
$mac = str_replace("V", "", $mac);
$mac = str_replace("W", "", $mac);
$mac = str_replace("X", "", $mac);
$mac = str_replace("Y", "", $mac);
$mac = str_replace("Z", "", $mac);


# LETS CHECK WE HAVE 6 CHARACTERS LEFT TO WORK WITH
if (strlen($searchterm)<6) {
	echo "TOO FEW CHARACTERS";
	die;
} 


### LETS SORT THE MAC OUT TO A GOOD FORMAT 
$searchterm = $mac;

## CUT MAC TO FIRST 6 DIGITS
$mac = mb_substr($mac,0,6);

## ADJUST TO CORRECT FORMAT
$searchterm = wordwrap($mac, 2, '-', true);

## OUI LOOKUP ##
$handle = fopen("oui.txt", "r");

$found = false;

if ($handle) 

{
    $countline = 0;
    while (($buffer = fgets($handle, 4096)) !== false)
    {
        if (strpos($buffer, "$searchterm") !== false)
        {
	        
	        // FOUND A HEX MATCH
            $found = true;	
			$result = $buffer;
			  
        }
        $countline++;
    }
    fclose($handle);
}

if (!$found) {
        echo "NO MATCH FOUND"; 
        die;
        }

// TIDY COMPANY NAME
$company = mb_substr($result,18);
$company = str_replace("\n", "", $company);
$company = str_replace("\r", "", $company);

// STRA TO CONVERT RESULTS TO JSON
$json_output = new \stdClass();
$json_output->query = $query;
$json_output->hex = mb_substr($result,0,8);
$json_output->base16 = $mac;
$json_output->company = $company;
$json_output->data_source = date ("F d Y H:i", filemtime($filename));
		
// CALCULATE COMPUTATIONAL TIME TAKEN
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
	
// FINALIZE JSON RESPONSE 
$json_output->querytime = $total_time . "ms";
$json_output->peakmemory = round($mem_peak / 1024) . "KB";
	
$json = json_decode(json_encode($json_output), true);

// SORT OUTPUT ORDERING

// JSON ENCODE
$sorted_response = json_encode($json, JSON_PRETTY_PRINT);

// OUTPUT JSON
header('Content-Type: application/json');
echo $sorted_response;

?>
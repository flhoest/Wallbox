<?php
	include_once "wbFramework.php";
	date_default_timezone_set('Europe/Brussels');

	//                     .__   .__  ___.                    
	//    __  _  _______   |  |  |  | \_ |__    ____ ___  ___ 
	//    \ \/ \/ /\__  \  |  |  |  |  | __ \  /  _ \\  \/  / 
	//     \     /  / __ \_|  |__|  |__| \_\ \(  <_> )>    <  
	//      \/\_/  (____  /|____/|____/|___  / \____//__/\_ \ 
	//                  \/                 \/              \/ 
	//                                                        	
	//    API Hack (C) 2022 - Frederic Lhoest

	// Variables Definition

	$auth=array(
			"username" => "your_email_address",
			"password" => "your_password"
			);

	// Token are valid for 15 days. Think at renewing them !
	$token="your_token";
	
	// Get charging power every $sampleRate seconds
	$sampleRate=60;
	
	// Output file 
	$outputFile="stats.csv";
	
	// Charger ID 
	$chargerID=376871;
	
	print("Dumping data to file : ".$outputFile." every ".$sampleRate." secs\n\n");

	// Init variables before starting process
	$sample=0;
	$sampleArray=array();
	
	// Init stdin for keystroke detection
	$stdin = fopen('php://stdin', 'r');
	stream_set_blocking($stdin, 0);
	system('stty cbreak -echo');	
	
	$keypressed=FALSE;

	while (!$keypressed)
	{
		$stats=wbGetCharger($token,$chargerID);
		print("Last update : ".date("Y/m/d @ H:i:s")."\n");
		print("\tEnergy added : ".$stats->addedEnergy." kWh\n");
		print("\tCharging power : ".$stats->chargingPower." kW\n");
		
		$sampleArray[$sample]["date"]=date("Y/m/d @ H:i:s");
		$sampleArray[$sample]["energy"]=$stats->addedEnergy;
		$sampleArray[$sample]["power"]=$stats->chargingPower;
		$sample++;
		print("\tSample #".$sample." captured -  PRESS ANY KEY TO TERMINATE PROCESS\n\n");
		sleep($sampleRate);

    	$keypress=fgets($stdin);
    	if($keypress)
    	{
    		print("-> Key pressed, terminating process...");
    		$keypressed=TRUE;
    	} 
	}

	print("\nIt's time to write data to file...\n\n");
	system('stty -cbreak echo');	

	// Write data to disk
	$file=fopen($outputFile,"w");
	
	fwrite($file,"Date Time\",\"energy\",\"power\"\n");
	
	for($i=0;$i<count($sampleArray);$i++)
	{
		fwrite($file,"\"");
		fwrite($file,$sampleArray[$i]["date"]);
		fwrite($file,"\",");
		fwrite($file,"\"");
		fwrite($file,$sampleArray[$i]["energy"]);
		fwrite($file,"\",");
		fwrite($file,"\"");
		fwrite($file,$sampleArray[$i]["power"]);
		fwrite($file,"\"\n");
	}

	fclose($file);
	print("Done.\n\n");

?>

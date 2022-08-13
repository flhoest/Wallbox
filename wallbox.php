<?php

//                     .__   .__  ___.                    
//    __  _  _______   |  |  |  | \_ |__    ____ ___  ___ 
//    \ \/ \/ /\__  \  |  |  |  |  | __ \  /  _ \\  \/  / 
//     \     /  / __ \_|  |__|  |__| \_\ \(  <_> )>    <  
//      \/\_/  (____  /|____/|____/|___  / \____//__/\_ \ 
//                  \/                 \/              \/ 
//                                                        	
//    API Hack (c) 2022 - Frederic Lhoest

	include_once "wbFramework.php";
	date_default_timezone_set('Europe/Brussels');

	// Variables Definition

	$auth=array(
			"username" => "emailAddress",
			"password" => "password"
			);

	// Token are valid for 15 days. Think at renewing them !
	$token="long_string_token";
	$id="376871";
	
	// =======================================
	// Main entry point
	// =======================================

	print("\n\n");
	print("                   .__   .__  ___.                    \n");
	print("  __  _  _______   |  |  |  | \_ |__    ____ ___  ___ \n");
	print("  \ \/ \/ /\__  \  |  |  |  |  | __ \  /  _ \\  \/  / \n");
	print("   \     /  / __ \_|  |__|  |__| \_\ \(  <_> )>    <  \n");
	print("    \/\_/  (____  /|____/|____/|___  / \____//__/\_ \ \n");
	print("                \/                 \/              \/ \n");
	print("\t>>> Charger Data Dump v 0.9 - (C) 2022 Frederic Lhoest\n\n");

	$chargerInfo=wbGetCharger($token,$id);

	print("+--------------------------------------------------+--------------------------------------------------+\n");
	print("|".str_pad("G E N E R A L   I N F O R M A T I O N",50," ",STR_PAD_BOTH)."|".str_pad("C U R R E N T   S T A T U S",50," ",STR_PAD_BOTH)."|\n");
	print("+--------------------------------------------------+--------------------------------------------------+\n");
	print("| ".str_pad("Charger Type : ".$chargerInfo->chargerType,49," ",STR_PAD_RIGHT));
	
	if($chargerInfo->locked) print("| ".str_pad("Charger id locked : YES",48," ",STR_PAD_RIGHT)." |\n");
	else print("| ".str_pad("Charger is locked : NO",48," ",STR_PAD_RIGHT)." |\n");
	
	print("| ".str_pad("Max Charing Current : ".$chargerInfo->maxChargingCurrent."A",49," ",STR_PAD_RIGHT));
	
	if($chargerInfo->chargingPower) print("| ".str_pad("Currently charging at ".$chargerInfo->chargingPower." kW",48," ",STR_PAD_RIGHT)." |\n");
	else print("| ".str_pad("Currently not charging",48," ",STR_PAD_RIGHT)." |\n");

	print("| ".str_pad("Software Version : ".$chargerInfo->software->currentVersion,49," ",STR_PAD_RIGHT)."|".str_pad(" ",50," ",STR_PAD_RIGHT)."|\n");

	if($chargerInfo->software->updateAvailable)
	{
		print("| ".str_pad(" -> Softrware update available to version ".$chargerInfo->software->latestVersion,49," ",STR_PAD_RIGHT)."|".str_pad(" ",50," ",STR_PAD_RIGHT)."|\n");
	}
	else
	{
		print("| ".str_pad(" -> Currently no update available",49," ",STR_PAD_RIGHT)."|".str_pad(" ",50," ",STR_PAD_RIGHT)."|\n");
	}
	print("| ".str_pad("Last session sync : ".date("Y-m-d H:i:s", $chargerInfo->lastSync),49," ",STR_PAD_RIGHT)."|".str_pad(" ",50," ",STR_PAD_RIGHT)."|\n");

	// Last 10 sessions summary
	
	print("+--------------------------------------------------+--------------------------------------------------+\n");
	print("|".str_pad("L A S T   1 0   S E S S I O N S   S U M M A R Y",101," ",STR_PAD_BOTH)."|\n"); 
	print("+-----------------------------------------------------------------------------------------------------+\n");

	$startDate=strtotime(date("Y/m/d", strtotime("-1 months")));
	$endDate=strtotime(date("Y/m/d"));

	$sessions=wbGetSessionList($token,$id,$startDate,$endDate);

	$num=count($sessions);
	if($num>=10) $num=10;
	else $num=count($sessions);
	$gCost=0;
	
	for($i=0;$i<$num;$i++)
	{
		// cost per session 
		$cost=$sessions[$i]->attributes->total_cost;
		$gCost+=$cost;
		$sessID=$i+1;
		print("| ".str_pad("#".$sessID." - ".date("Y-m-d H:i:s", $sessions[$i]->attributes->start_time).", for a duration of ".format_time($sessions[$i]->attributes->charging_time).", ".$sessions[$i]->attributes->energy." kWh added (cost ".round($cost,2)." EUR)",100," ",STR_PAD_RIGHT)."|\n");
	}
	print("+-----------------------------------------------------------------------------------------------------+\n");
	print("| Total cost for above sessions : ");
	print(str_pad(round($gCost,2)." EUR ",67," ",STR_PAD_RIGHT)." |\n");
	print("+-----------------------------------------------------------------------------------------------------+\n");
	print("|".str_pad("L A S T   S E S S I O N    D E T A I L S",101," ",STR_PAD_BOTH)."|\n");
	print("+-----------------------------------------------------------------------------------------------------+\n");
	
	print("| ".str_pad("Added Energy : ".$chargerInfo->addedEnergy." kWh",100," ", STR_PAD_RIGHT)."|\n");
	print("| ".str_pad("Charging for : ".format_time($chargerInfo->chargingTime)." (hh:mm:ss)",100," ",STR_PAD_RIGHT)."|\n");

	$currentPrice=$chargerInfo->addedEnergy*$chargerInfo->energyPrice;

	print("| ".str_pad("Price for session : ".round($currentPrice,2)." EUR",100," ",STR_PAD_RIGHT)."|\n");
	print("+-----------------------------------------------------------------------------------------------------+\n\n");
	print("-> Data coming from Wallbox portal, I cannot be liable for any wrong information, \nit is directly fetched from Wallbox extranet using REST API!\n\n");
?>

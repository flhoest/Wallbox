<?php
	include_once "wbFramework.php";
	include_once "wbCredentials.php";
	
	$version="0.3";
	
	//                     .__   .__  ___.                    
	//    __  _  _______   |  |  |  | \_ |__    ____ ___  ___ 
	//    \ \/ \/ /\__  \  |  |  |  |  | __ \  /  _ \\  \/  / 
	//     \     /  / __ \_|  |__|  |__| \_\ \(  <_> )>    <  
	//      \/\_/  (____  /|____/|____/|___  / \____//__/\_ \ 
	//                  \/                 \/              \/ 

	// Step 1 : get basic parameters and settings from the charger defined in wbCredentials.php

	function displayMenu()
	{
		global $selection, $version;
		
		print("+------------------------------------------------------------+\n");
		print("|                    .__   .__  ___.                         |\n");
		print("|   _   _  _______   |  |  |  | \_ |__    ____ ___  ___      |\n");
		print("|   \ \/ \/ /\__  \  |  |  |  |  | __ \  /  _ \\  \/  /       |\n");
		print("|    \     /  / __ \_|  |__|  |__| \_\ \(  <_> )>    <       |\n");
		print("|     \/\_/  (____  /|____/|____/|___  / \____//__/\_ \      |\n");
		print("|                 \/                 \/              \/      |\n");
		print("|".str_pad("Wallbox Commander v".$version,59," ",STR_PAD_LEFT)." |\n"); 
		print("+------------------------------------------------------------+\n");
		print("|".str_pad("M A K E   A   S E L E C T I O N",60," ",STR_PAD_BOTH)."|\n");
		print("+------------------------------------------------------------+\n");
		print("|".str_pad(" 1 : Lock Charger",60," ",STR_PAD_RIGHT)."|\n");
		print("|".str_pad(" 2 : Unlock Charger",60," ",STR_PAD_RIGHT)."|\n");
		print("|".str_pad(" 3 : Pause charging Session",60," ",STR_PAD_RIGHT)."|\n");
		print("|".str_pad(" 4 : Resume charging Session",60," ",STR_PAD_RIGHT)."|\n");
		print("|".str_pad(" 5 : Set charging power",60," ",STR_PAD_RIGHT)."|\n");
		print("|".str_pad(" ",60," ",STR_PAD_RIGHT)."|\n");
		print("|".str_pad(" 9 : Exit",60," ",STR_PAD_RIGHT)."|\n");
		print("+------------------------------------------------------------+\n");
	
		$valid_input=FALSE;
		$selection=0;
	
		// Ask for selection until valid value is submitted
		while(!$valid_input)
		{
			$selection=(int)readline("\rMake a selection : ");
			// Validate selection
			if(is_int($selection))
				if($selection>0 && $selection<10) $valid_input=TRUE;
		}
	}
	
	// ==================================
	// Main Entry Point
	// ==================================
	
	// Entire script runs in an infinite loop that can only be stopped using the exit option
	
	while (TRUE)
	{
		// Clear the screen each time menu is displayed
		system("clear");
		displayMenu();
		switch ($selection)
		{
			case 1:		print("Trying to lock the charger... ");
						$lock=wbLockCharger($token,$id);
						if($lock) print("Charger is locked.\n");
						else print("Charger is not locked.\n");
						// Introduce a pause to see result of command
						readline("Press <Enter> to continue...");
						break;
			case 2:		print("Trying to Unlock the charger... ");
						$lock=wbUnlockCharger($token,$id);
						if(!$lock) print("Charger is unlocked.\n");
						else print("Charger is not unlocked.\n");
						readline("Press <Enter> to continue...");
						break;
			case 3:		print("Pausing charge ...");
						wbPauseCharge($token,$id);
						print("Charge has been paused!\n");
						readline("\nPress <Enter> to continue...");
						break;
			case 4:		print("Resuming charge ...");
						wbResumeCharge($token,$id);
						print("Charge resumed!\n");
						readline("\nPress <Enter> to continue...");
						break;
			case 5:		$valid=FALSE;
						while(!$valid)
						{
							$power=readline("Please enter maximum charging power (1-32) : ");
							if($power>0 && $power<33) $valid=TRUE;
							else $valid=FALSE;
						}
						// Set power to $power
						print("Setting charging power to ".$power." ...");
						$res=wbSetMaxChargingCurrent($token,$id,$power);
						print("Charging power has been set to ".$power."\n");
						readline("\nPress <Enter> to continue...");
						break;
			case 9:		print("\nExit selected.\n\n");
						exit();
						break;
			default:	print("Invalid choice\n\n");
		}
	}	
?>

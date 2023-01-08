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
	//    API Hack (C) 2023 - Frederic Lhoest

	// Variables Definition

	$auth=array(
			"username" => "my_user_name",
			"password" => "my_password"
			);

	// Charger ID 
	$chargerID=12345;

	// Get new token
	$wbToken=wbGetToken($auth);
	
	// Restarting the unit
	var_dump(wbRestart($wbToken,$chargerID));

?>

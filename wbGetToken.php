<?php

//                     .__   .__  ___.                    
//    __  _  _______   |  |  |  | \_ |__    ____ ___  ___ 
//    \ \/ \/ /\__  \  |  |  |  |  | __ \  /  _ \\  \/  / 
//     \     /  / __ \_|  |__|  |__| \_\ \(  <_> )>    <  
//      \/\_/  (____  /|____/|____/|___  / \____//__/\_ \ 
//                  \/                 \/              \/ 
//                                                        	
//    API Hack (C) 2022 - Frederic Lhoest

	include_once "wbFramework.php";

	$auth=array(
			"username" => "your_email_address",
			"password" => "your_password"
			);

	print("Your Wallbox token is : ".wbGetToken($auth)."\n\n");
	
?>

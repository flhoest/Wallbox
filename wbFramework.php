<?php

	//////////////////////////////////////////////////////////////////////////////
	//                     Wallbox Php Framework version 0.7                    //
	//                        (c) 2023 - Frederic Lhoest                        //
	//////////////////////////////////////////////////////////////////////////////
	//                       Created on macOS with BBEdit                       //
	//////////////////////////////////////////////////////////////////////////////

	//                     .__   .__  ___.                    
	//    __  _  _______   |  |  |  | \_ |__    ____ ___  ___ 
	//    \ \/ \/ /\__  \  |  |  |  |  | __ \  /  _ \\  \/  / 
	//     \     /  / __ \_|  |__|  |__| \_\ \(  <_> )>    <  
	//      \/\_/  (____  /|____/|____/|___  / \____//__/\_ \ 
	//                  \/                 \/              \/ 

	//
	// Function index in alphabetical order (total 12)
	//------------------------------------------------

	// format_time($t,$f=':')
	// wbColorBold($string)
	// wbGetCharger($token,$id)
	// wbGetSessionList($token,$id,$startDate,$endDate)
	// wbGetStatus($token,$id)
	// wbGetToken($auth)
	// wbLockCharger($token,$id)
	// wbPauseCharge($token,$id)
	// wbRestart($token,$chargerID)
	// wbResumeCharge($token,$id)
	// wbSetMaxChargingCurrent($token,$id,$maxCurrent)
	// wbUnlockCharger($token,$id)
	
	// ==========================================================================
	//                           Code starts here
	// ==========================================================================

	// ----------------------------
	// Function locking the charger
	// ----------------------------
	
	function wbLockCharger($token,$id)
	{
		$API="api.wall-box.com";

		$config_params="{\"locked\":1}";

   		$curl = curl_init();
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
   		
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS,$config_params);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
							"Authorization: Bearer ".$token,
							'Accept: application/json, text/plain, */*',
							'Content-Type: application/json;charset=utf-8'
							));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, "https://".$API."/v2/charger/".$id);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);
		curl_close($curl);
		return json_decode($result)->data->chargerData->locked;
	}

	// ------------------------------
	// Function unlocking the charger
	// ------------------------------

	function wbUnlockCharger($token,$id)
	{
		$API="api.wall-box.com";

		$config_params="{\"locked\":0}";

   		$curl = curl_init();
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
   		
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS,$config_params);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
							"Authorization: Bearer ".$token,
							'Accept: application/json, text/plain, */*',
							'Content-Type: application/json;charset=utf-8'
							));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, "https://".$API."/v2/charger/".$id);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);
		curl_close($curl);
		return json_decode($result)->data->chargerData->locked;
	}

	// -----------------------------------
	// Function pausing a charging session
	// -----------------------------------
	
	function wbPauseCharge($token,$id)
	{
		$API="api.wall-box.com";

		$config_params=" {\"action\":2}";

   		$curl = curl_init();
// 		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
   		
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS,$config_params);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
							"Authorization: Bearer ".$token,
							'Accept: application/json, text/plain, */*',
							'Content-Type: application/json;charset=utf-8'
							));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, "https://".$API."/v3/chargers/".$id."/remote-action");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);
		curl_close($curl);

		return $result;
	}

	// ------------------------------------
	// Function resuming a charging session
	// ------------------------------------
	
	function wbResumeCharge($token,$id)
	{
		$API="api.wall-box.com";

		$config_params=" {\"action\":1}";

   		$curl = curl_init();
   		
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS,$config_params);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
							"Authorization: Bearer ".$token,
							'Accept: application/json, text/plain, */*',
							'Content-Type: application/json;charset=utf-8'
							));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, "https://".$API."/v3/chargers/".$id."/remote-action");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);
		curl_close($curl);

		return $result;
	}

	// ----------------------------------------------
	// Function retrieving the current charger status
	// ----------------------------------------------

	function wbGetStatus($token,$id)
	{
		$data=wbGetCharger($token,$id);
		
		switch ($data->status)
		{
			case 164:
			case 180:
			case 181:
			case 183:
			case 184:
			case 185:
			case 186:
			case 187:
			case 188:
			case 189: $status="WAITING";
			break;
			case 193:
			case 194:
			case 195: $status="CHARGING";
			break;
			case 161:
			case 162: $status="READY";
			break;
			case 178:
			case 182: $status="PAUSED";
			break;
			case 177:
			case 179: $status="SCHEDULED";
			break;
			case 196: $status="DISCHARGING";
			break;
			case 14:
			case 15: $status="ERROR";
			break;
			case 0:
			case 163: $status="DISCONNECTED";
			break;
			case 209:
			case 210:
			case 165: $status="LOCKED";
			break;
			case 166: $status="UPDATING";
			break;
			default: $status="UNKNOWN";
		}		
		
		return $status;
	}

	// ------------------------------------------------------
	// Function retrieving the authentication token
	// ------------------------------------------------------

	function wbGetToken($auth)
	{
		$API="api.wall-box.com";

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
							"Authorization: Basic ".base64_encode($auth["username"].":".$auth["password"]),
							'Accept: application/json, text/plain, */*',
							'Content-Type: application/json;charset=utf-8'
							));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, "https://".$API."/auth/token/user");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		curl_close($curl);

		return json_decode($result)->jwt;
	}

// 	function wbGetChargerStatus($token,$id)
// 	{
// 		$API="api.wall-box.com";
// 
// 		$curl = curl_init();
// 		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
// 		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
// 							"Authorization: Bearer ".$token,
// 							'Accept: application/json, text/plain, */*',
// 							'Content-Type: application/json;charset=utf-8'
// 							));
// 		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
// 		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
// 		curl_setopt($curl, CURLOPT_URL, "https://".$API."/chargers/status/".$id);
// 		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
// 		$result = curl_exec($curl);
// 		curl_close($curl);
// 
// 	var_dump(json_decode($result));
// 	exit();
// 	
// 		return json_decode($result)->result->groups;
// 	}

	// ----------------------------------------
	// Function retrieving all chargers details
	// ----------------------------------------

	function wbGetCharger($token,$id)
	{
		$API="api.wall-box.com";

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
							"Authorization: Bearer ".$token,
							'Accept: application/json, text/plain, */*',
							'Content-Type: application/json;charset=utf-8'
							));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, "https://".$API."/v3/chargers/groups");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		curl_close($curl);
		$result=json_decode($result)->result->groups;

		$chargerInfo="";
		// Collect all details for charger $id
	
		for($i=0;$i<count($result[0]->chargers);$i++)
		{
			if($result[0]->chargers[$i]->id==$id)
			{
				$chargerInfo=$result[0]->chargers[$i];
			}
			else $chargerInfo=FALSE;
		}

		return $chargerInfo;
	}

	// -------------------------------------
	// Function setting max charging current
	// -------------------------------------

	function wbSetMaxChargingCurrent($token,$id,$maxCurrent)
	{
		$API="api.wall-box.com";

		$config_params="
				{
				  \"maxChargingCurrent\": \"".$maxCurrent."\"
				}			
				";

   		$curl = curl_init();
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
   		
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS,$config_params);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
							"Authorization: Bearer ".$token,
							'Accept: application/json, text/plain, */*',
							'Content-Type: application/json;charset=utf-8'
							));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, "https://".$API."/v2/charger/".$id);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);
		curl_close($curl);

		return json_decode($result)->data->chargerData->maxChargingCurrent;
	}
	
	// -------------------------------------
	// Function converting seconds to hours 
	// -------------------------------------

	function format_time($t,$f=':') 
	{
	  return @sprintf("%02d%s%02d%s%02d", floor($t/3600), $f, ($t/60)%60, $f, $t%60);
	}	

	// -----------------------------------------------------
	// Function retrieving charging sessions between 2 dates
	// -----------------------------------------------------

	function wbGetSessionList($token,$id,$startDate,$endDate)
	{
		$API="api.wall-box.com";

		$params=
					"\"filters\":[{\"field\":\"start_time\",
					\"operator\":\"gte\",\"value\":".$startDate."},{\"field\":\"end_time\",
					\"operator\":\"lte\",\"value\":".$endDate."}]}";

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
							"Authorization: Bearer ".$token,
							'Accept: application/json',
							'Content-Type: application/json;charset=utf-8')
							);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, "https://".$API."/v4/groups/257061/charger-charging-sessions");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($curl);
		curl_close($curl);

		return json_decode($result)->data;
	}

	// ----------------------------
	// Function restarting the unit
	// ----------------------------

	function wbRestart($token,$chargerID)
	{
		$API="api.wall-box.com";

		$config_params="
				{
				  \"action\": 3
				}			
				";

   		$curl = curl_init();
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
   		
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS,$config_params);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
							"Authorization: Bearer ".$token,
							'Accept: application/json, text/plain, */*',
							'Content-Type: application/json;charset=utf-8'
							));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, "https://".$API."/v3/chargers/".$chargerID."/remote-action");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);
		curl_close($curl);

		return $result;
	}
	
	// ------------------------------------
	// Function displaying a string in bold
	// ------------------------------------
	
	function wbColorBold($string)
	{
			return ("\e[1;37m".$string."\033[0m");
	}
	
?>

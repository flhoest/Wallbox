<?php

//                     .__   .__  ___.                    
//    __  _  _______   |  |  |  | \_ |__    ____ ___  ___ 
//    \ \/ \/ /\__  \  |  |  |  |  | __ \  /  _ \\  \/  / 
//     \     /  / __ \_|  |__|  |__| \_\ \(  <_> )>    <  
//      \/\_/  (____  /|____/|____/|___  / \____//__/\_ \ 
//                  \/                 \/              \/ 
//                                                        	
//    API Hack (C) 2022 - Frederic Lhoest

	// Function index in alphabetical order (total 5)
	//------------------------------------------------

	// format_time($t,$f=':')
	// wbGetCharger($token,$id)
	// wbGetSessionList($token,$id,$startDate,$endDate)
	// wbGetToken($auth)
	// wbSetMaxChargingCurrent($token,$id,$maxCurrent)
	
/*

/*

Note to myslef :

Charger Status Code : 

class Statuses(MultiValueEnum):
    WAITING = 164, 180, 181, 183, 184, 185, 186, 187, 188, 189,
    CHARGING = 193, 194, 195,
    READY = 161, 162,
    PAUSED = 178, 182,
    SCHEDULED = 177, 179,
    DISCHARGING = 196,
    ERROR = 14, 15,
    DISCONNECTED = 0, 163, None,
    LOCKED = 209, 210, 165,
    UPDATING = 166
    
    https://community.jeedom.com/t/pluging-wallbox/85244/3
	https://community.homey.app/t/wallbox-pulsar-plus-charger-lock-unlock-pause-resume/54616/2    
    https://community.home-assistant.io/t/wallbox-pulsar-plus-integration/200339    

	/v4/groups/257061/sessions/calculate/daily?filters={"filters":[{"field":"start_date","operator":"gte","value":"2022-07-27T04:13:57+0200"},{"field":"end_date","operator":"lte","value":"2022-08-02T04:13:57+0200"},{"field":"charger_id","operator":"eq","value":376871}]}
	/sessions/filters?group_id=257061
	/v4/groups/257061/charger-charging-sessions?filters={"filters":[{"field":"start_time","operator":"gte","value":1659132000},{"field":"end_time","operator":"lte","value":1659304800}]}&limit=50&offset=0
	/users/data/253447
	/chargers/config/376871
	
*/

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

?>

#Wallbox Php Framework

![logo0](https://s28.q4cdn.com/641324054/files/design/svg/Wallbox_Logotype-Isotype_TM-b.svg)	![logo1](https://d1yjjnpx0p53s8.cloudfront.net/styles/logo-thumbnail/s3/062015/php_0.png?itok=W6WL-Rbh)	

## Background
This project's goal is to provide anyone who needs to script automation, a collection of functions that call Wallbox's APIs. Do not hesitate to check the supporting blog post here : https://www.lets-talk-about.tech/2022/08/wallbox-get-most-of-it-with-api.html 

## Functions Reference

The below section is a list of all existing functions in this framework.

### Index
````
format_time($t,$f=':')
wbGetCharger($token,$id)
wbGetSessionList($token,$id,$startDate,$endDate)
wbGetStatus($token,$id)
wbGetToken($auth)
wbLockCharger($token,$id)
wbPauseCharge($token,$id)
wbResumeCharge($token,$id)
wbSetMaxChargingCurrent($token,$id,$maxCurrent)
wbUnlockCharger($token,$id)
````

### Explanation

> **format_time($t,$f=':')**

This function is converting a number of second into a human readable hh:mm:ss format.

| Input | Description |
| ----------|----------|
| `$t`   | An integer representing number of seconds  | 
| `$f=':'`   | The time separator, ":" if omitted | 


- Usage sample : 

```php
$var=format_time(5381);
var_dump($var);
```

The above will display : 

```
01:29:41 
```

> **wbGetCharger($token,$id)**

This function is retieving charger details for charger `$id`

| Input | Description |
| ----------|----------|
| `$token`   | The token as a string format | 
| `$id`   | The charger ID | 


- Usage sample : 

```php
$var=wbGetCharger($token,$id);
var_dump($var);
```

The above will display : 

```
object(stdClass)#4 (30) {
  ["id"]=>
  int(12345)
  ["uid"]=>
  string(26) "01FZAP4TQXYUER806C3Q6J6EQ"
  ["access"]=>
  bool(true)
  ["name"]=>
  string(20) "PulsarPlus SN 12345"
  ["status"]=>
  int(181)
  ["chargerType"]=>
  string(10) "PulsarPlus"
  ["ocppReady"]=>
  string(9) "ocpp_1.6j"
  ["ocppConnectionStatus"]=>
  int(1)
  ["connectionType"]=>
  string(4) "wifi"
  ["locked"]=>
  int(0)
  ["maxChargingCurrent"]=>
  int(32)
  ["addedEnergy"]=>
  float(8.359)
  ["chargingPower"]=>
  int(0)
  ["chargingTime"]=>
  int(5381)
  ["energyUnit"]=>
  string(3) "kWh"
  [...]
```

> **wbGetSessionList($token,$id,$startDate,$endDate)**

This function is retriving charging sessions between `$startDate` and `$endDate`

| Input | Description |
| ----------|----------|
| `$token`   | The token as a string format | 
| `$id`   | The charger ID | 
| `$startDate`   | The start date as a timestamp | 
| `$endDate`   | The end date as a timestamp | 


- Usage sample : 

```php
$var=wbGetSessionList($token,$id,$startDate,$endDate);
var_dump($var);
```

The above will display : 

```
array(98) {
  [0]=>
  object(stdClass)#8 (3) {
    ["type"]=>
    string(24) "charger_charging_session"
    ["id"]=>
    string(26) "01GABVEXXXXXW50WGZW8TTB34D"
    ["attributes"]=>
    object(stdClass)#9 (33) {
      ["id"]=>
      string(26) "01GABVEXXXXXW50WGZW8TTB34D"
      ["start_time"]=>
      int(1660389677)
      ["end_time"]=>
      int(1660402460)
      ["charging_time"]=>
      int(5400)
      ["user_id"]=>
      int(233327)
      ["user_name"]=>
      string(8) "First_name"
      ["user_surname"]=>
      string(6) "Last_name"
      ["user_email"]=>
      string(17) "emailAddress"
      ["charger_id"]=>
      int(12345)
      ["charger_name"]=>
      string(20) "PulsarPlus SN 12345"
      ["group_id"]=>
      int(257061)
      ["location_id"]=>
      int(257061)
      ["location_name"]=>
      string(11) "MyTown"
      ["energy"]=>
      int(8361)
      ["mid_energy"]=>
      int(0)
      ["energy_price"]=>
      float(0.20645)
      ["currency_code"]=>
      string(3) "EUR"
      ["session_type"]=>
      string(4) "free"
      ["application_fee_percentage"]=>
      int(5)
      [...]
```

> **wbGetToken($auth)**

This function is retrieving the authentication token. Reminder, the token is valid 15 days, you need to manage the renewal within your code.

| Input | Description |
| ----------|----------|
| `$auth`   | An array containing your credentials to the Wallbox portal | 


- Usage sample : 

```php
$var=wbGetToken($auth);
var_dump($var);
```

The above will display : 

```
string(856) "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VybmFtZSI6ImZsaG9lc3RAZ21haWwuY29tIiwidWlkIjoiMDFHQTdDVzQ3UFNGNVpCRFBTQUQ3UFM2SloiLCJleHAiOjE2NjE1NDkyNDUsImlhdCI6MTY2MDI1MzI0NX0.USapLnAZqKuzx0KtdpdN_hzLcvcD5sKeYoZZYoFuvIDuvz-OhEeWS5UulhX2UM7aKkx_eQUIdBmrY4uhVfQcCcuB6DtsQik7MkB1MuZfess2ovO3-QbPvDFNWxUfWk4Hwsi_Ei-03KVTLbMD9vCZBMYVBgpzv-xAxmN94sT1K8eG3wdIM5ZSr5GcHpWqdxSHNq1b81UJKEuw5KDIb2OM44lLIJv3ErlAlmU8hepord9HAM5QS5VLLIQgvN8eALiMLHRaV117VZtnoXY6zoFof10sOVG1_zdql6VNLzuP4BBriYkpqdJAbpfuRN0-JMdZqbxxJG63RkwrrYuZn__9cKr0ENtSmIGRKM7lVXW5dMEv6UXT9swZM3YzHMwyTj4DaqDnnkmAfy-cbLLOUpaAAukoDPw0YVUBUIKbsB86Ix2CYemWm_XOj-RAVjvp-Qk1BqrVqaZZgVJ49wVuhI8GcrRGC_RwLw604FsMcYScMUZZmdVwBV2Vq_ioBhIFh9vMWgTi4DaZjtHuV--T82gTBJipJIZCnh7UUHNFP4WocIVzEZJM51UnQT4gjxk3nt8-iZY3kuXjbFOfMhMthjpj3fRWBQL8HvKwiMegGIY3c_N2tUVdLolVIPuZGyGQmWEv74wuZBBLrrv52KTzw65tKjjuYyejQnA_mXQNsnsbilE"
```

> **wbSetMaxChargingCurrent($token,$id,$maxCurrent)**

This function sets the maximum charging currrent

| Input | Description |
| ----------|----------|
| `$token`   | The token as a string format | 
| `$id`   | The charger ID | 
| `$maxCurrent`   | An integer setting the current in Amps | 


- Usage sample : 

```php
$var=wbSetMaxChargingCurrent($token,$id,$maxCurrent);
var_dump($var);
```

The above will display : 

```
ENTER TEXT HERE 
```

## Todo List

- Finalize the documentation
- Add more functions
- Explore, explore, explore, ...


## Versioning

This is the first release as of 2022, Aug 13.

## Thanks to

Chrome and the developer module

## Authors

Frederic Lhoest - *[@flhoest](https://twitter.com/flhoest)*

> Disclaimer : This documentation has been generated with *docGen.php v0.3*. An Open Source initiative producing easy and accurate documentation of php codes in seconds.
Available on [gitHub](https://github.com/flhoest/docGen).

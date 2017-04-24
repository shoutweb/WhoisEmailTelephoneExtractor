<?php


//Types of input 
$typesOfInput = array("Registrant", "Admin", "Tech");
$typesOfAttributes = array("Name:", "Organization:", "Street:", "City:", "State/Province:", "Postal Code:", "Phone:", "Phone Ext:", "Email:");
$output = array();


//Easy way to search for specific values
function get_item($output, $request) {
	$count = strlen($request);
	if (strpos($output, $request) !== false) {
		$temp = substr($output, strpos($output,$request)+$count);
		$payload = substr($output, strpos($output, $request)+$count, strpos($temp,'"'));
		return $payload;
	} else {
		return '';
	}
}
$i = 0;

//SET WEBSITES THAT YOU WANT TO BE ANALYZED INTO THIS ARRAY
$websitesToBeAnalyzed = array('cnn.com','facebook.com');


//Actual Work being done
foreach ($websitesToBeAnalyzed as $key => $value) {
 	$website = $value;
	$command = "whois $website";
	exec($command, $output, $exit_code);
	$output = json_encode($output);

	$registrantEmail = get_item($output,"Registrant Email:");
	$registrantName = get_item($output,"Registrant Name:");
	$registrantStreet = get_item($output,"Registrant Street:");
	$registrantCity = get_item($output,"Registrant City:");
	$registrantState = get_item($output,"Registrant State/Province:");
	$registrantTelephone = get_item($output,"Registrant Phone:");

	//ADMINISTRATIVE CONTACT
	$administrativeEmail =  get_item($output,"Admin Email:");
	/*$administrativeName = $response->WhoisRecord->administrativeContact->name;
	$administrativeStreet = $response->WhoisRecord->administrativeContact->street1;
	$administrativeCity = $response->WhoisRecord->administrativeContact->city;
	$administrativeState = $response->WhoisRecord->administrativeContact->state;
	$administrativeTelephone = $response->WhoisRecord->administrativeContact->telephone;
	*/

	//TECHNICAL CONTACT
	$technicalEmail =  get_item($output,"Tech Email:");;
	/*
	$technicalName = $response->WhoisRecord->technicalContact->name;
	$technicalStreet = $response->WhoisRecord->technicalContact->street1;
	$technicalCity = $response->WhoisRecord->technicalContact->city;
	$technicalState = $response->WhoisRecord->technicalContact->state;
	$technicalTelephone = $response->WhoisRecord->technicalContact->telephone;
	*/
	$additionalEmails = '';
	if ($administrativeEmail !== $registrantEmail) {
		$additionalEmails = $additionalEmails . $administrativeEmail;
	}

	if ($technicalEmail !== $registrantEmail) {
		$additionalEmails = $additionalEmails . $technicalEmail;
	}
	
	
		
	echo "$i - The email for $website is $registrantEmail, the name is $registrantName, the telephone is $registrantTelephone, and additional emails found were $additionalEmails <br>";

	
	$i++;
	echo "$i rows have been written </br>";
	
	//Allows the response to be outputted whil script runs & throttles number of requests
	flush();
	ob_flush();
	usleep(rand(2000000,4000000));
}
<?php
	$inData = getRequestInfo();

// expected JSON
// {
// 	  "userId" : "0",
// 		"firstname" : "firstname",
//    "lastname" : "lastname",
//    "phonenumber" : "phonenumber",
//    "email" : "email@email.com"
// }

	$userId = $inData["userId"];
	$firstname = $inData["firstname"];
	$lastname = $inData["lastname"];
	$phonenumber = $inData["phonenumber"];
	$email = $inData["email"];


	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "Contacts");
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		$stmt = $conn->prepare("INSERT into ContactCards (DateCreated, UserId, FirstName, LastName, PhoneNumber, Email) VALUES(now(), ?,?,?,?,?)");
		$stmt->bind_param("sssss", $userId, $firstname, $lastname, $phonenumber, $email);
		$stmt->execute();
		$stmt->close();
		$conn->close();
		returnWithError("");
	}

	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}

	function returnWithError( $err )
	{
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

?>

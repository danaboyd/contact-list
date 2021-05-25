<?php
	$inData = getRequestInfo();
	// expected JSON
	// {
	// 	"firstname" : "firstname",
	// 	"lastname" : "lastname",
	// 	"login" : "login",
	// 	"password" : "password"
	// }

	$firstname = $inData["firstname"];
	$lastname = $inData["lastname"];
	$login = $inData["login"];
	$password = $inData["password"];

	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "Contacts");
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		$check = $conn->prepare("SELECT ID,firstName,lastName FROM Users WHERE Login=?");
		$check->bind_param("s", $inData["login"]);
		$check->execute();
		$result = $check->get_result();

		if( $row = $result->fetch_assoc()  ){
			returnWithError("A user with this login already exists.");
		}
		else
		{
			$stmt = $conn->prepare("INSERT into Users (DateCreated, DateLastLoggedIn, FirstName, LastName, Login, Password) VALUES(now(),now(),?,?,?,?)");
			$stmt->bind_param("ssss", $firstname, $lastname, $login, $password);
			$stmt->execute();
			$stmt->close();
			$conn->close();
			returnWithError("");
		}


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

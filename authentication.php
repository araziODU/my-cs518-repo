<?php

	// Server, database name, sqluserid, and sqlpassword
	$server = "localhost";
    $sqlUsername = "root";
    $sqlPassword = "862629Al!";
    $databaseName = "mydatabase";
			
	$connectionInfo = array('Database'=>$databaseName, 'UID'=>$sqlUsername, 'PWD'=>$sqlPassword,
									'Encrypt'=>'0', 'ReturnDatesAsStrings'=>true );


    //function to authenticate user, and return TRUE or FALSE
	function authenticateUser($connection, $username, $password)
	{
	  // User table which stores userid and password
	  $userTable = "users";

	  // Test the username and password parameters
	  if (!isset($username) || !isset($password))
		return false;

	  $pa = md5($password);
	  // Formulate the SQL statment to find the user
	  $sql = "SELECT *
		 FROM $userTable
		 WHERE email = '$username' AND password = '$pa'";

	  // Execute the query
      $query_result = $connection->query($sql);
      if (!$query_result) {
              echo "Sorry, query is wrong";
              echo $query;
      }

	  // exactly one row? then we have found the user
      $nrows = $query_result->num_rows;
	  if ( $nrows != 1)
		return false;
	  else
		return true;
	}

	//function to authenticate user, and return TRUE or FALSE
	function authenticateUserForgotPassword($connection, $username)
	{
	  // User table which stores userid and password
	  $userTable = "users";

	  // Test the username and password parameters
	  if (!isset($username))
		return false;

	 
	  // Formulate the SQL statment to find the user
	  $sql = "SELECT *
		 FROM $userTable
		 WHERE email = '$username'";

	  // Execute the query
      $query_result = $connection->query($sql);
      if (!$query_result) {
              echo "Sorry, query is wrong";
              echo $query;
      }

	  // exactly one row? then we have found the user
      $nrows = $query_result->num_rows;
	  if ( $nrows != 1)
		return false;
	  else
		return true;
	}

    //function to get the type of user that is logged in
    function getFieldInfo($connection, $username, $password,$columnName)
    {
        // User table which stores userid and password
	  $userTable = "users";
      // Test the username and password parameters
	  if (!isset($username) || !isset($password))
      return false;

      $pa = md5($password);
	  // Formulate the SQL statment to find the user
	  $sql = "SELECT $columnName
		 FROM $userTable
		 WHERE email = '$username' AND password = '$pa'";
	 

	  // Execute the query
      $query_result = $connection->query($sql);
      if (!$query_result) {
              echo "Sorry, query is wrong";
              echo $query;
      }

	  // exactly one row? then we have found the user
      $nrows = $query_result->num_rows;
      $result = $query_result->fetch_assoc();
	  return $result[$columnName];

    }

?>
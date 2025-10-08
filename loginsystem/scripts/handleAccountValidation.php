<?php

//Get the validation keystring from the URL 
$queryString = $_SERVER['QUERY_STRING'];

//Step 1: establish connection to db
$pdo;
try {
    $pdo = new PDO("mysql:host=localhost;dbname=ex", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch (PDOException $err) {
    error_log("Error: " . $err->getMessage());
    die("Error: could not establish connection");
}

$pdo->beginTransaction();

//Step 1: check if ID exists for that keystring
$acquiredUserID;
try{
     $statement = $pdo->prepare("SELECT userID FROM userValidationKeys WHERE keyString = :targetKeyString");
     $statement->bindParam(':targetKeyString', $queryString, PDO::PARAM_STR);
     $statement->execute();

     $results = $statement->fetch();
    
    //error handler (if no results found)
    if($results == false){
        error_log("Error: No users found for keyString: " .$queryString , 3, "./PDOErrorLogs.log");
        die("Error: No such user found with that keystring");
    }

    else{
        //now use the acquired ID to update the users table
        $acquiredUserID = $results[0];  
    }

}
catch(PDOException $err){
    error_log("Error: " . $err->getMessage(), 3, "./PDOErrorLogs.log");
    die("Error: Could not find userID in userValidationKeys table with current KeyString: " . $queryString);
}

//Step 2: Now update that record in the users table to set the registered field to true
try{
    $newRegVal_int = (int) true;
    $statement = $pdo->prepare("UPDATE users SET registered = :newRegVal WHERE id = :targetID");
    $statement->bindParam(':newRegVal', $newRegVal_int, PDO::PARAM_INT);
    $statement->bindParam(':targetID', $acquiredUserID, PDO::PARAM_INT);
    $statement->execute();
}

catch(PDOException $err){
    error_log("Error: " . $err->getMessage(), 3, "./PDOErrorLogs.log");
    die("Error: CANNOT UPDATE THE USERS TABLE");
}


//Step 3: Now erase that record from the userValidationKeys table
try {
    $statement = $pdo->prepare("DELETE FROM userValidationKeys WHERE userID = :targetID");
    $statement->bindParam(':targetID', $acquiredUserID, PDO::PARAM_INT);
    $statement->execute();

} catch (PDOException $err) {
    error_log("Error: " . $err->getMessage(), 3, "./PDOErrorLogs.log");
    die("Error: Could not find userID in userValidationKeys table with current KeyString: " . $queryString);
}

$pdo->commit();


?>
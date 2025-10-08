<?php

session_start();

//configurable constants (to be changed when moving from local to production environment)
//  Instruction: Change this to the server's address of where the account confirmation page is hosted
define("ACCOUNT_VALIDATED_PAGE_URL" , "https://localhost/loginsystem/accountValidated.php");


$email = $_POST["emailInput"];
$fName = $_POST["firstNameInput"];
$lName = $_POST["lastNameInput"];
$pwd = $_POST["passwordInput"];
$registeredStatus = false;

//Step 1: hash password for DB insertion
$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

//Step 2: establish connection to DB
$pdo;
try{
    $pdo = new PDO("mysql:host=localhost;dbname=ex", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
}

catch(PDOException $err){
    error_log("Error: " . $err->getMessage());
    die("Error: could not establish connection");
}

//Step 3: prepare transaction for insertion into "users" table and "userValidationKeys" table
 $pdo->beginTransaction();

 //step 3.1: statement for "users" table
 try{
    $statement = $pdo->prepare("INSERT INTO users (firstName, lastName, email, hashedPassword, registered) values (?, ?, ?, ?, ?)");
    $statement->bindParam(1, $fName);
    $statement->bindParam(2, $lName);
    $statement->bindParam(3, $email);
    $statement->bindParam(4, $hashedPwd);
    //Note: Casting registered status to int because MySQL interprets BOOLEAN as tinyint
    $registeredStatus_int = (int) $registeredStatus;
    $statement->bindParam(5, $registeredStatus_int, PDO::PARAM_INT);
    $statement->execute();
 }

 catch(PDOException $err){
     error_log("Error: " . $err->getMessage(), 3, "./PDOErrorLogs.log");
    die("Error: could not insert into users table");
 }


//Now keep track of the user's ID (post-insertion)
$newUserID = $pdo->lastInsertId();

//generate a random alphanumeric string for the user
$randStr = generateRandomString();

try{
    //Step 4: prepare statement for insertion into userValidationKeys table   
    $statement = $pdo->prepare("INSERT INTO userValidationKeys (userID, keyString) values (?, ?)");
    $statement->bindParam(1, $newUserID);
    $statement->bindParam(2, $randStr);
    $statement->execute();
} catch (PDOException $err) {
    error_log("Error: " . $err->getMessage(), 3, "./PDOErrorLogs.log");
    die("Error: could not insert into userValidationKeys table: newUserID: " . $newUserID . " type(". gettype($newUserID)  .") || randStr: " . $randStr . " type(".gettype($randStr).") " );
}

//Commit the transaction
$pdo->commit();

//Now sending a registration email to the user
//append the randomly generated alphanumeric string to the url
$msg = "Congratulations you made an account! Please click on the following link to verify your account: " . ACCOUNT_VALIDATED_PAGE_URL . "?" . $randStr;
mail($email, "Account activation" , $msg);

//Update the lastOperation key of the SESSION variable so that the user knows that the sign up was successful
$_SESSION['lastOperation'] = "An email has been sent to the specified email address. Please follow the instructions to validate your account";
header("Location: ../index.php");

exit;




function generateRandomString(){
    //to-do: You must write a cryptographically secure generator that will return back a random
    //The current return value is just for testing purposes

    //Note: You can change this based on how long you want the string to be
    $strLength = 20;

    $charSet = [ "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T",
  "U", "V", "W", "X", "Y", "Z"];

    $digitSet = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];

    $str = "";

    for($x=0; $x<$strLength; $x++){

        $letterOrDigit = random_int(0, 10);

        if($letterOrDigit < 5){
            $charIndex = random_int(0, 25);
            $str = $str . $charSet[$charIndex];
        }

        else{
            $digitIndex = random_int(0, 9);
            $str = $str . $digitSet[$digitIndex];
        }

    }

    return $str;


    //return "abc123";

}


?>
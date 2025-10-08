<?php

    session_start();

    $email = $_POST["emailInput"];
    $pwd = $_POST["passwordInput"];

    $pdo;
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=ex", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } 
    catch (PDOException $err) {
        error_log("Error: " . $err->getMessage());
        die("Error: could not establish connection to DB");
    }
 

    $loginQueryResults;
    //stage 1 : check if the email exists
    try {
        echo "Email Input: " . $email;
        $statement = $pdo->prepare("SELECT * FROM users WHERE email=:targetEmail");
        $statement->bindParam(':targetEmail', $email);
        $statement->execute();

        $loginQueryResults = $statement->fetchAll();

        if($loginQueryResults == false){
            $_SESSION['errorMessage'] = "Error: That email does not exist on the system";
            header("Location: ../index.php");
            die("Error: Email does not exist in system");
        }
    } 
    catch (PDOException $err) {
        error_log("Error: " . $err->getMessage(), 3, "./PDOErrorLogs.log");
        die("Error: Could not query users table");
    
    }

    //Stage 2: Check if password matches (issue wrong password if mismatch and exit)
    if( !(password_verify($pwd, $loginQueryResults[0]['hashedPassword'])) ){
        $_SESSION['errorMessage'] = "Error: Incorrect password entered";
        header("Location: ../index.php");
        die("Error: Incorrect password");
    }

    
    //Stage 3: Check if registered (update error message if not validated)
    if( $loginQueryResults[0]['registered'] == 0 ){
    $_SESSION['errorMessage'] = "Error: This account has not been validated. Please click on the validation link sent to you by email";
    header("Location: ../index.php?");
        die("Error: Your account has not yet been validated. Please click on the validation link sent to your email");
    }

    else{
        //Now set the session variables (upon successful login)
        $_SESSION['userID'] = $loginQueryResults[0]['id'];
        $_SESSION['firstName'] = $loginQueryResults[0]['firstName'];
        $_SESSION['lastName'] = $loginQueryResults[0]['lastName'];

        header("Location: ../loginConfirmed.php");
        exit();
    }



?>
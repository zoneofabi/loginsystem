<?php

session_start();

$id = $_SESSION['userID'];
$pwd = $_POST["passwordInput"];

echo "ID: $id";

$pdo;
try {
    $pdo = new PDO("mysql:host=localhost;dbname=ex", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $err) {
    error_log("Error: " . $err->getMessage());
    die("Error: could not establish connection to DB");
}

//Step 1: Get account info
$accountQueryResults;
//stage 1 :
try {
    $statement = $pdo->prepare("SELECT * FROM users WHERE id=:targetID");
    $statement->bindParam(':targetID', $id);
    $statement->execute();

    $accountQueryResults = $statement->fetchAll();

    if ($accountQueryResults == false) {
        $_SESSION['errorMessage'] = "Error: Unable to find user id : " . $id;
        header("Location: ../index.php");
        die("Error: Unable to find user");
    }
} catch (PDOException $err) {
    error_log("Error: " . $err->getMessage(), 3, "./PDOErrorLogs.log");
    die("Error: Could not find query users table");

}



//Step 2: Check if password matches

if ((password_verify($pwd, $accountQueryResults[0]['hashedPassword']))) {

    try {
        $statement = $pdo->prepare("DELETE FROM users WHERE id=:targetID");
        $statement->bindParam(':targetID', $id);
        $statement->execute();

    } catch (PDOException $err) {
        error_log("Error: " . $err->getMessage(), 3, "./PDOErrorLogs.log");
        die("Error: Could not find delete user");

    }

    $_SESSION['lastOperation'] = "User was deleted";
    header("Location: ../index.php");
    die("User deleted");
}





?>
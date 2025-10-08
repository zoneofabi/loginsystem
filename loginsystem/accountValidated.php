<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    

    <h2>Your account has been validated! Please return to the home page and try logging in</h2>

    <?php
        $queryString = $_SERVER['QUERY_STRING'];
        require_once "scripts/handleAccountValidation.php";
    ?>

</body>

</html>
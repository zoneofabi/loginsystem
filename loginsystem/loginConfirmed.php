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
        <?php
            session_start();

           
        ?>    


        <div class="welcomeDiv">
            <?php
            echo "<h2> Welcome ".$_SESSION['firstName']. " " . $_SESSION['lastName'] . "! You have logged in";
            ?> 
        </div>

         <div class="formDiv">

            <form id="deleteAccountForm"  action="scripts/handleAccountDeletion.php" method="post">
              
                 <label for="passwordInput">Password</label>
                <input name="passwordInput" placeholder="Enter Password To Delete Account" type="password" required>

                <button type="submit">DELETE ACCOUNT</button>

            </form>

        </div>



        
    </body>
</html>
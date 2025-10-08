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

            //checking for any errors from a redirect
            if( isset($_SESSION['errorMessage'])  ){
                echo "<h4> Error:".$_SESSION['errorMessage']."</h4>";
            }

            //checking for last operation
            if (isset($_SESSION['lastOperation'])) {
                echo "<h4>" . $_SESSION['lastOperation'] . "</h4>";
            }
   
            ?>

        <div class="formDiv">

            <form id="signupForm"  action="scripts/handleSignUp.php" method="post">
                 <label for="firstNameInput">First Name</label>
                <input name="firstNameInput" placeholder="First Name" type="text" required>

                 <label for="lastNameInput">Last Name</label>
                <input name="lastNameInput" placeholder="Last Name" type="text" required>

                <label for="emailInput">Email</label>
                <input name="emailInput" placeholder="username" type="email" required>

                 <label for="passwordInput">Password</label>
                <input name="passwordInput" placeholder="username" type="password" required>

                <button type="submit">Sign Up</button>

            </form>

        </div>

        <div class="formDiv">

            <form action="scripts/handleLogin.php" method="post">
                <label for="emailInput">Email</label>
                <input name="emailInput" placeholder="email" type="text" required>

                 <label for="passwordInput">Password</label>
                <input name="passwordInput" placeholder="username" type="password" required>

                <button type="submit">Login</button>
            </form>
        </div>


        <script src="script.js" async defer></script>

        <?php
            //clearing any error message in the session variable for a clean refresh
        if (isset($_SESSION['errorMessage'])) {
            unset($_SESSION['errorMessage']);
        }

            //clearing last operation message from session
        if (isset($_SESSION['lastOperation'])) {
            unset($_SESSION['lastOperation']);
        }
        ?>
    </body>
</html>
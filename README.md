# loginsystem

How to set up the application on a local environment:

1) Please install Laragon to set up a portable development environment 

2) Once installed, copy the 'loginsystem' folder from here and paste it in the 
    'www' folder that resides inside your laragon installation. 

3) Start Laragon and open the MySQL terminal. Now copy the inner contents of 
'setupDB.sql' located in the 'SQLScripts' folder in here, and paste all that code 
in the MySQL terminal. Then run it (shortcut key is F9).

This will set up the tables needed to run the app. You are now ready to run the app. 

You can access it by this URL: http://localhost/loginsystem/

The app will let you create a new account, and then validate that account, and then login 
with it. 

Once inside the login page, it will greet you by your name. And you also have a form which you can use to delete
your account (you must enter your password before clicking the delete account button)


How the application works on the back-end:

1) There are 2 tables that the database uses :
    - 'Users' (which contains the user's details such as id, name, password, email, and 
                and a field called "registered" which is a boolean.) 
   
   
    - 'UserValidationKeys'


2) When the user first signs up for an account, the system takes in their name, password (which it will hash before entry)
and initializes the registered field as false (to indicate that the user is not yet registered).

3) After this, the system generates a random alphanumeric string and logs that into the UserValidationKeys table (along with the id of that 
user who just signed up).

It then sends an email (to the email address they mentioned) and appends that alphanumeric string to the end of a URL which 
it attaches to that email. 

4) When the user clicks on that URL, the system will extract out that alphanumeric string from the URL and then check 
the UserValidationKeys table to see if that alphanumeric string is currently on it. If it is, it will take note of the 
userID associated with it and then wipe that record from the UserValidationKeys table. It will then target the 
tuple in the users table which has that userID and set the registered property to 'true' (1). This is to indicate that 
the acount has been validated.

5) When the user logs in, it will check for the email, check if the password matches and check if it is validated. 
If all conditions match, it will log the user in and store their details in the $_SESSION superglobal array. 


Notes:

1) Given that this is a practice assignment, the php scripts are placed within the website's folder itself. 
   As per best practices, it is recommended to put the php scripts outside the www folder. However since 
   that requires server configuration, for the sake of simplicity, the scripts are in the website's folder. 



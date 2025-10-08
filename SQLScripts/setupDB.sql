CREATE DATABASE ex;

USE ex;

/*registered will start out as false and turn true after user confirms their account from their email*/
CREATE TABLE users(
    id int NOT NULL AUTO_INCREMENT, 
    firstName VARCHAR(255) NOT NULL, 
    lastName VARCHAR(255) NOT NULL, 
    email VARCHAR(255) NOT NULL, 
    hashedPassword VARBINARY(255),
    registered BOOLEAN , 
    PRIMARY KEY (id)
);



/*This table is meant to hold validationKeys assigned to a user (right after they create an account, and a 
randomly generated alphanumeric string is assigned to their account.
After they validate their accounts from their email, their record must be wiped from this table and their 
'registered' value must be set to true in the users table*/
CREATE TABLE userValidationKeys(
    userID int NOT NULL, 
    keyString VARCHAR(255) NOT NULL , 
    PRIMARY KEY (userID)
);

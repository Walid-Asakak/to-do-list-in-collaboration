<?php 

include 'repository/connection.php';

function insertUser(array $user, string $passwordHached): int {
    $db = connectToDataBase();
    
    try {
        $request = $db -> prepare(
            "INSERT INTO users (username, email, password)
            VALUES (:username, :email, :password)"
        );
        
        $request -> bindParam(':username', $user['username'], PDO::PARAM_STR);
        $request -> bindParam(':email', $user['email'], PDO::PARAM_STR);
        $request -> bindParam(':password', $passwordHached, PDO::PARAM_STR);
        
        $request -> execute();
        
        // We get the ID of the last registered user
        $id = $db -> lastInsertId();
        return $id;
    }
    
    catch(Exception $e) {
        // We don"t show the error to the users -> he can use it
        //die($e -> getMessage());
        return null;
    }
}

function getUserbyEmail(string $email): ?array {
    $db = connectToDataBase();
    
    try {
        $query = $db -> prepare('SELECT * FROM users WHERE email = :email');
        $query -> bindParam(':email', $email, PDO::PARAM_STR);
        $query -> execute();
        $user = $query -> fetch();
        return $user;
    }
    
    catch (Exception $e) {
        // We don"t show the error to the users -> it is dangerous
        //die($e -> getMessage());
        return null;        
    }
}
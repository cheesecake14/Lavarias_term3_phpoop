<?php
    class database {
        function opencon() {
            return new PDO('mysql:host=localhost;dbname=php_221','root','');
        }
        function check($username, $password) {
            // Open database connection
            $con = $this->opencon();
       
            // Prepare the SQL query
            $stmt = $con->prepare("SELECT * FROM users WHERE user = ?");
            $stmt->execute([$username]);
       
            // Fetch the user data as an associative array
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
       
            // If a user is found, verify the password
            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }
       
            // If no user is found or password is incorrect, return false
            return false;
        }
 
        function signup($firstname, $lastname, $birthday, $gender, $username, $password){
            $con = $this->opencon();
 
            $query = $con->prepare("SELECT user FROM users WHERE user = ?");
            $query->execute([$username]);
            $existingUser = $query->fetch();
 
            if($existingUser){
                return false;
            }
            return $con->prepare("INSERT INTO users (firstname, lastname, birthday, gender, user,password) VALUE(?,?,?,?,?,?)")->execute([$firstname, $lastname, $birthday, $gender, $username, $password]);
        }
 
        // function signupUser($firstname, $lastname, $birthday, $gender, $username, $password){
        //     $con = $this->opencon();
 
        //     $query = $con->prepare("SELECT user FROM users WHERE user = ?");
        //     $query->execute([$username]);
        //     $existingUser = $query->fetch();
 
        //     if($existingUser){
        //         return false;  
        //     }
 
        //     $con->prepare("INSERT INTO users (firstname, lastname, birthday, gender, user,password) VALUE(?,?,?,?,?,?)")->execute([$firstname, $lastname, $birthday, $gender, $username, $password]);
 
        //     return $con->lastInsertId();
        // }
 
        function signupUser($firstname, $lastname, $birthday, $sex, $email, $username, $password, $profilePicture)
        {
            $con = $this->opencon();
            // Save user data along with profile picture path to the database
            $con->prepare("INSERT INTO users (firstname, lastname, birthday, gender, email, user, password, user_profile_picture) VALUES (?,?,?,?,?,?,?,?)")->execute([$firstname, $lastname, $birthday, $sex, $email, $username, $password, $profilePicture]);
            return $con->lastInsertId();
            }  
 
        function insertAddress($user_id,$street,$barangay,$city,$province){
            $con = $this->opencon();
 
            return $con->prepare("INSERT INTO address (user_id,street,barangay,city,province) VALUE(?,?,?,?,?)")->execute([$user_id,$street,$barangay,$city,$province]);
        }
 
        function view() {
            $con = $this->opencon();
            return $con->query("SELECT
            users.user_profile_picture,
            users.user_id,
            users.firstname,
            users.lastname,
            users.gender,
            users.birthday,
            users.user, CONCAT(
            address.street,' ',address.barangay,' ',address.city,' ',address.province) as address
        FROM
            users
        INNER JOIN address ON users.user_id = address.user_id")->fetchAll();  
        }
 
        function delete($id){
            try{
                $con = $this->opencon();
                $con->beginTransaction();
                $query = $con->prepare("DELETE FROM address WHERE user_id = ?");
                $query->execute([$id]);
                $query2 = $con->prepare("DELETE FROM users WHERE user_id = ?");
                $query2->execute([$id]);
                $con->commit();
                return true;
            }   catch(PDOException $e){
                $con->rollBack();
                return false;
            }
        }
 
        function viewdata($id){
            try{
                $con = $this->opencon();
                $query = $con->prepare("SELECT users.user_id,user_profile_picture,users.firstname,users.lastname,users.gender,users.birthday,users.user,users.password,address.street,address.barangay,address.city,address.province
            FROM
                users
            INNER JOIN address ON
                users.user_id = address.user_id
            WHERE
                users.user_id = ?");
            $query->execute([$id]);
            return $query->fetch();
                } catch(PDOException $e){
                    return [];
            }
        }
       
        function update($id){
            try{
                $con = $this->opencon();
                $con->beginTransaction();
                $query = $con->prepare("UPDATE ");
            }catch(PDOException $e){
                $con->rollBack();
                return false;
            }
 
        }
 
        function updateUser($user_id, $firstname, $lastname, $birthday, $gender, $username, $password, $profile){
            try {
                $con = $this->opencon();
                $query = $con->prepare("UPDATE users SET firstname=?,lastname=?,birthday=?,gender=?,user=?,password=?, user_profile_picture=? WHERE user_id=?");
                return $query->execute([$firstname,$lastname,$birthday,$gender,$username,$password,$profile,$user_id]);
            } catch(PDOException $e){
                // $con->rollBack();
                return false;
            }                
        }
 
        function updateAddress($user_id,$street,$barangay,$city,$province){
            try {
                $con = $this->opencon();
                $query = $con->prepare("UPDATE address SET street=?,barangay=?,city=?,province=? WHERE user_id=?");
                return $query->execute([$street,$barangay,$city,$province,$user_id]);
            } catch(PDOException $e){
                // $con->rollBack();
                return false;
            }
        }
 
        function updatePassword($userId, $hashedPassword) {
            $con = $this->opencon();
            $query = $con->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            return $query->execute([$hashedPassword, $userId]);
        }
 
        function validateCurrentPassword($userId, $currentPassword) {
            // Open database connection
            $con = $this->opencon();
       
            // Prepare the SQL query
            $query = $con->prepare("SELECT password FROM users WHERE user_id = ?");
            $query->execute([$userId]);
       
            // Fetch the user data as an associative array
            $user = $query->fetch(PDO::FETCH_ASSOC);
       
            // If a user is found, verify the password
            if ($user && password_verify($currentPassword, $user['password'])) {
                return true;
            }
       
            // If no user is found or password is incorrect, return false
            return false;
        }
        function updateUserProfilePicture($userID, $profilePicturePath) {
            try {
                $con = $this->opencon();
                $con->beginTransaction();
                $query = $con->prepare("UPDATE users SET user_profile_picture = ? WHERE user_id = ?");
                $query->execute([$profilePicturePath, $userID]);
                // Update successful
                $con->commit();
                return true;
            } catch (PDOException $e) {
                // Handle the exception (e.g., log error, return false, etc.)
                 $con->rollBack();
                return false; // Update failed
            }
             }
 
             function updateUserAddress($user_id, $street, $barangay, $city, $province){
                try {
                    $con = $this->opencon();
                    $con->beginTransaction();
                    $query = $con->prepare("UPDATE address SET street=?, barangay=?, city=?, province=? WHERE user_id=?");
                    $query->execute([$street, $barangay, $city, $province, $user_id]);
                    $con->commit();
                    return true; // Update successful
                } catch (PDOException $e) {
                    // Handle the exception (e.g., log error, return false, etc.)
                    $con->rollBack();
                    return false; // Update failed
                }
            }
    }  
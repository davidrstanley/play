<?php
/**
* The user class handles all user related methods
*test
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/


    /**
     * Import PHPMailer classes into the global namespace
     * These must be at the top of your script, not inside a function
     *
     * @author Ken Stanley <ken@stanleysoft.org>
     * @license MIT
     */
    
    require_once('db.class.php');

     /**
     * USER handles user related methods
     *
     * @author Ken Stanley <ken@stanleysoft.org>
     * @license MIT
     */
    class USER
    {
         /**
         * database connection
         *
         * @var string
         */
        private $conn;
        /**
        * Constructor to connect to the database
        *
        * @throws \PDOException
        * @author Ken Stanley <ken@stanleysoft.org>
        *
        */
        public function __construct()
        {
            try 
            {
                $database = new Database();
                $db = $database->dbConnection();
                $this->conn = $db;
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Sets the error mode
            } catch (PDOException $e)
            {
                echo "Connection error: " . $e->getMessage(); // return error message
            }
        } // end construct
        
        /**
        * Destruct
        * Destorys the connection to the database
        *
        * @author Ken Stanley <ken@stanleysoft.org>
        */
        public function __destruct()
        {
            $this->conn = null;
        } // end destruct 
        
        /**
        * This function logs the user in by setting the session
        *
        * @param Place   $umail  the users email address
        * @param integer $upass the users password
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return true or false
        */
        public function doLogin($umail,$upass)
        {
            try
            {
                $stmt = $this->conn->prepare("SELECT * FROM user WHERE user_email=:umail ");
                $stmt->execute(array(':umail'=>$umail));
                $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
                // if email if found check password
                if($stmt->rowCount() == 1)
                {
                    // if password matches then successful login
                    if(password_verify($upass, $userRow['user_password']))
                    {
                        $_SESSION['user_id'] = $userRow['user_id'];
                        $_SESSION['fname'] = $userRow['user_fName'];
                        $_SESSION['lname'] = $userRow['user_lName'];
                    
                        if(isset($_SESSION['emailcheck'])){
                            unset($_SESSION['emailcheck']);
                        }
                        $access = $this->accessCheck();
                        if($access == 'ADMIN')
                        {
                            header('Location: /admin');
                        } else 
                        {
                            header('Location: /user/myexportlist.php');
                        }
                        return true;
                    }
                    else
                    {
                        header('Location: /login.php?error=invalidpassword');
                        return false;
                    }
                } else 
                {
                    header('Location: /login.php?error=notregistered');
                    return false;
                }
                
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end doLogin
        
        /**
        * Returns the user role name
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return role_name (USER or ADMIN)
        */
        public function accessCheck()
        {
            if(isset($_SESSION['user_id']))
            {
                $user_id = $_SESSION['user_id'];
                try
                {
                    $stmt = $this->conn->prepare("SELECT * FROM user 
                    left join role on user_role_id = role_id
                    WHERE user_id=:uid");
                    $stmt->bindparam(":uid", $user_id);
                    $stmt->execute();
                    $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
                    // if email if found check password
                    if($stmt->rowCount() == 1)
                    {
                        return $userRow['role_name'];
                    }
                }
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }
            }
        } // end accessCheck
        
        /**
        * Checks if the user has been disabled
        *
        * @param interger $userID the users ID number
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return send to location /user/disabled.php or true
        */
        public function activeCheck($userID)
        {
            if(isset($_SESSION['user_id']))
            {
                $uid = $_SESSION['user_id'];
                try
                {
                    $stmt = $this->conn->prepare("SELECT * FROM user 
                    WHERE user_id=:uid");
                    $stmt->bindparam(":uid", $uid);
                    $stmt->execute();
                    $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
                    $status = $userRow['user_active'];
                    // if email if found check password
                    if($status == 0)
                    {
                        header("location: /user/disabled.php");
                    }
                    else
                    {
                        return true;
                    }
                }
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }
            }
        } // end activeCheck
        
            /**
            * Adds a new user and sends email verification
            *
            * @param string $fname users first name
            * @param string $lname users last name
            * @param string $email users email address
            * @param string $company users company
            * @author Ken Stanley <ken@stanleysoft.org>
            * @return true or pdo error
            */
        
            public function register($fname, $lname, $email, $password)
            {
                session_start();
                $password = password_hash($password, PASSWORD_DEFAULT);
                
                $fname = strtolower($fname); // change to all lower case
                $fname = ucfirst($fname); // change first letter to upper case
                
                $lname = strtolower($lname);
                $lname = ucfirst($lname);
                
                $email = strtolower($email);

                
                try {
                    $stmt = $this->conn->prepare("INSERT INTO users (user_fname, user_lname, user_email, user_password) VALUES(:fname, :lname, :email, :password)");

                    $stmt->bindparam(":fname", $fname);
                    $stmt->bindparam(":lname", $lname);
                    $stmt->bindparam(":email", $email);
                    $stmt->bindparam(":password", $password);
                    
                    $stmt->execute();	
                    $user_id = $this->conn->lastInsertId();
                    
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['fname'] = $fname;

                    return true;
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        
            /**
            * Password reset function. Sends an email to the user to reset the password
            *
            * @param string $email  the users email address
            * @author Ken Stanley <ken@stanleysoft.org>
            * @return true or false
            */
            public function sendPassLink($email)
            {
                $email_stmp = $this->stmp_email;
                $email_pass = $this->stmp_pass;
                //Load Composer's autoloader
                require '../vendor/autoload.php';
                // for user registeration
                $code = $this->code();
                
                $mail = new PHPMailer(true);                              // Passing `true` enables xceptions
                
                try 
                {
                    $stmt = $this->conn->prepare("SELECT * from user where user_email =:email");
                    $stmt->bindparam(":email", $email);
                    $stmt->execute();
                    $row = $stmt->fetch();
                    $db_id = $row['user_id'];
                    $rowCount = $stmt->rowCount();
                    if($rowCount >= 1)
                    {
                        try 
                        {
                            $update = $this->conn->prepare("UPDATE user SET user_code = :code 
                                WHERE user_email = :email");
                            $update->bindparam(":email", $email);
                            $update->bindparam(":code", $code);
                            $update->execute();	

                            //Server settings
                            $mail->SMTPDebug = 0;                           // Enable verbose debug output
                            $mail->isSMTP();                                // Set mailer to use SMTP
                            $mail->Host = 'visualpartsdb.com';  // Specify main and backup SMTP servers
                            $mail->SMTPAuth = true;                         // Enable SMTP authentication
                            $mail->Username = $email_stmp;     // SMTP username
                            $mail->Password = $email_pass;                           // SMTP password
                            $mail->SMTPSecure = 'ssl';                      // Enable TLS encryption, `ssl` also accepted
                            $mail->Port = 465;                              // TCP port to connect to

                            //Recipients
                            $mail->setFrom('register@visualpartsdb.com', 'Visual Parts Database');
                            $mail->addAddress($email, $fname.' '.$lname);     // Add a recipient
                            //$mail->addAddress($email);               // Name is optional
                            $mail->addReplyTo('register@visualpartsdb.com', 'NoReply');
                            //$mail->addCC('cc@example.com');
                            //$mail->addBCC('bcc@example.com');

                            //Attachments
                            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

                            //Content
                            $mail->isHTML(true);                                  // Set email format to HTML
                            $mail->Subject = 'Visual Parts Database Password Reset';
                            $mail->Body    = 'Hello '.$fname.', <br><br> Someone has requested a password reset for your acount. If you did not do this, please ignore.  <br><br>Your Activation Code is: <b>'.$code.'</b><br><br> Please click on this link https://visualpartsdb.com/user/password_reset.php?id='.$db_id.'&code='.$code.' to activate your account.';
                            $mail->AltBody = 'Your Password Reset Code is: '.$code.' Please click on this link https://visualpartsdb.com/user/password_reset.php?id='.$db_id.'&code='.$code.' to change your password.';

                            $mail->send();
                            return true;
                        } catch (Exception $e) 
                        {
                            echo $e->getMessage();
                        }
                        return true;
                    }
                    else
                    {
                        return 'Email Not Found';
                    }
                    
                } catch (Exception $e) 
                {
                    echo $e->getMessage();
                }

            }

        
            /**
            * Generates a code for use in password reset, new user, etc.
            *
            * 
            * @author Ken Stanley <ken@stanleysoft.org>
            * @return generate code
            */
        
            public function code()
            {
                $code = substr(md5(mt_rand()),0,15);
                return $code;
            }
        
        
            /**
            * Password reset verification. Updates the database user_verify field
            *
            * @param integer $userID the users ID from the database
            * @param string  $code the code from the email verification
            * @author Ken Stanley <ken@stanleysoft.org>
            * @return true, noaccount, no record found
            */
            public function checkVerify($userID, $code)
            {
                try
                {
                    $stmt = $this->conn->prepare("SELECT * from user where user_id=:id and user_code=:code");							  
                    $stmt->bindparam(":id", $userID);
                    $stmt->bindparam(":code", $code);
                    $stmt->execute();	
                    if($stmt->rowCount() == 1){
                        $row = $stmt->fetch();
                        $userFName = $row['user_fName'];
                        $userLName = $row['user_lName'];
                        $userEmail = $row['user_email'];
                        $existsCheck = $this->checkID($userEmail);
                        
                        if($existsCheck)
                        {
                            $verify = 1;
                            try
                            {
                                $adduser = $this->conn->prepare("UPDATE user SET user_verify = :verify
                                    WHERE user_id = :userID");
                                $adduser->bindparam(":verify", $verify);
                                $adduser->bindparam(":userID", $userID);
                                $adduser->execute();
                                return true;
                            } catch(PDOException $e)
                            {
                                echo $e->getMessage();
                            } 
                        } else
                        {
                            return 'noaccount';
                        } 
                    } else {
                      return 'No record found';
                    }
                     return true;
                }
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }	
            }
            
        /**
        * Checks if an email is already in the database
        *
        * @param string $email  the users email address
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return true or false
        */
        public function checkID($email)
        {
            try 
            {
                $stmt = $this->conn->prepare("SELECT * from user WHERE user_email = :email");
                $stmt->bindparam(":email", $email);
                $stmt->execute();
                $rowCount = $stmt->rowCount();
                if($rowCount >= 1)
                {
                    return true;
                } else
                {
                    return false;
                }
            } catch(PDOException $e)
            {
                echo $e->getMessage();
            }	
        }
        
        
        /**
        * Updates the password field in the user database
        *
        * @param integer $userID the users database ID
        * @param integer $password the users new password
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return true
        */
        public function updatePassword($userID, $password){
            $password = password_hash($password, PASSWORD_DEFAULT);
            try 
            {
                $stmt = $this->conn->prepare("UPDATE user SET user_password=:password, user_code = null
                    WHERE user_id=:userid ");
                $stmt->bindparam(":userid", $userID);
                $stmt->bindparam(":password", $password);
                $stmt->execute();
                
                return true;
            } catch(PDOException $e)
            {
                echo $e->getMessage();
            }	
        }
        
        /**
        * Removes all traces of the user from the database. 
        *
        * @param integer $userid the users database ID
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return true or pdo error
        */
        public function remUser($userid){
            try
            {
                $stmt = $this->conn->prepare("DELETE user_part_list_skus FROM user_part_list_skus
                    LEFT join user_part_list on pl_id = pls_list_id 
                    WHERE pl_user_id=:userid ");
                $stmt->bindparam(":userid", $userid);
                $stmt->execute();
                try
                {
                    $stmt = $this->conn->prepare("DELETE FROM user_part_list
                        WHERE pl_user_id=:userid ");
                    $stmt->bindparam(":userid", $userid);
                    $stmt->execute();
                        try
                        {
                            $stmt = $this->conn->prepare("DELETE FROM sku_update_request
                                WHERE update_request_by=:userid ");
                            $stmt->bindparam(":userid", $userid);
                            $stmt->execute();
                                    try 
                                    {
                                        $stmt = $this->conn->prepare("DELETE FROM sku_search WHERE sku_search_by=:userid ");
                                        $stmt->bindparam(":userid", $userid);
                                        $stmt->execute();
                                            try 
                                            {
                                                $stmt = $this->conn->prepare("DELETE FROM user WHERE user_id=:userid ");
                                                $stmt->bindparam(":userid", $userid);
                                                $stmt->execute();
                                                return true;
                                            } catch(PDOException $e)
                                            {
                                                echo $e->getMessage();
                                            }
                                    } catch(PDOException $e)
                                    {
                                        echo $e->getMessage();
                                    }
                        } catch(PDOException $e)
                        {
                            echo $e->getMessage();
                        }	  
                } catch(PDOException $e)
                {
                    echo $e->getMessage();
                }	

            } catch(PDOException $e)
            {
                echo $e->getMessage();
            }		
        }
        
        /**
        * Sets the $_SESSION after login
        *
        * @param integer $userID the users database ID
        * @param string $fname the users first name
        * @param string $lname the users last name
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return true
        */
        
        public function setSession($userID, $fname, $lname)
        {
            $_SESSION['user_id'] = $userID;
            $_SESSION['fname'] = $fname;
            $_SESSION['lname'] = $lname;
            return true;
        }
        
        /**
        * Returns a list of all users
        *
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return array of users
        */
        
        public function getUserList()
        {
            try
            {
                $stmt = $this->conn->prepare("SELECT * FROM user ORDER BY user_id");
                $stmt->execute();
                $result = array(array());
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end dropDownUser
        
        
        /**
        * Returns the users First and Last name as one field
        *
        * @param integer $userID the users database ID
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return $fullname
        */
        public function userFullName($userID)
        {
            try
            {
                $stmt = $this->conn->prepare("SELECT * FROM user
                    WHERE user_id = :userid");
                $stmt->bindparam(":userid", $userID);
                $stmt->execute();
                $row = $stmt->fetch();
                $fullName = $row['user_fName'].' '.$row['user_lName'];
                return $fullName;
                
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end userFullName

        /**
        * Destories session and sends user to the landing page
        *
        * @param string $sku is the part number
        * @param string $sku is the part number 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return true or false
        */
        public function doLogout()
        {   
            session_destroy();
            header("Location: /");
            exit();
        }
    } // End Class
?>
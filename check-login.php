<?php

    session_start();

    if ( (!isset($_POST['email'])) || (!isset($_POST['password'])) )
    {
        header('Location: sign-in.php');
        exit();
    }

    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try
        {
         $connection = new mysqli($host, $db_user, $db_password, $db_name);

        if ($connection->connect_errno!=0)
         {
            echo "Error: ".$connection->connect_errno;
        }
        else
         {
            $email= $_POST['email'];
            $password=$_POST['password'];

             $email = htmlentities($email, ENT_QUOTES, "UTF-8");
                
            if ( $result = $connection->query
            (sprintf("SELECT * FROM users WHERE email='%s'",
             mysqli_real_escape_string($connection, $email) )))
            {
                $how_many_users = $result->num_rows;
                if($how_many_users > 0)
                {
                    $row = $result->fetch_assoc();

                     if (password_verify($password, $row['password']))
                    {
                        $_SESSION['logged'] = true;
                                
                        $_SESSION['username'] = $row['username'];
                        $_SESSION['id'] = $row['id'];

                        unset($_SESSION['error']);
                        $result->close();
                        header('Location: main-menu.php');
                    }
                            
                    else
                    {
                        $_SESSION['error'] = '<span style="color:red"> Nieprawidłowy login lub hasło! </span>';
                        header('Location: sign-in.php');
                    }

                    } else {
                           
                        $_SESSION['error'] = '<span style="color:red"> Nieprawidłowy login lub hasło! </span>';
                         header('Location: sign-in.php');
                    }
                }

                else
                {
                    throw new Exception($connection->error);
                }

                $connection->close();
        }
    }

    catch(Exception $e)
    {
        echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o wizytę w innym terminie!</span>';
		//echo '<br />Informacja developerska: '.$e;
    }

?>
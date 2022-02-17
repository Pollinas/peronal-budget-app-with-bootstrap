<?php

    session_start();

    if (!isset($_SESSION['sign_up_success']))
    {
        header('Location: sign-in.php');
        exit();
    }

    else
    {
        require_once"connect.php";

        mysqli_report(MYSQLI_REPORT_STRICT);

        try
        {
            $connection = new mysqli($host, $db_user, $db_password, $db_name);
            
            if ($connection->connect_errno!=0)
            {
                throw new Exception(mysqli->connect_errno());
            }
            else
            {
                $email= $_SESSION['email'];
             
                if ( $result = $connection->query
                ("SELECT * FROM users WHERE email='$email'") )
                {
                        $row = $result->fetch_assoc();
                        $id = $row['id'];
                }
               else{
                throw new Exception($connection->error);
                }


                //payment methods
                
                if( !$connection->query("INSERT INTO payment_methods_assigned_to_users (payment_methods_assigned_to_users.name,
                payment_methods_assigned_to_users.user_id)
                SELECT payment_methods_default.name, '$id' FROM payment_methods_default") )
                {
                    throw new Exception($connection->error);
                }
                //expense categories 
 
                if( !$connection->query("INSERT INTO incomes_category_assigned_to_users (incomes_category_assigned_to_users.name,
                incomes_category_assigned_to_users.user_id)
                SELECT incomes_category_default.name, '$id' FROM incomes_category_default") )
                {
                    throw new Exception($connection->error);
                }
                //income categories 
                
                if( !$connection->query("INSERT INTO expenses_category_assigned_to_users (expenses_category_assigned_to_users.name,
                expenses_category_assigned_to_users.user_id)
                SELECT expenses_category_default.name, '$id' FROM expenses_category_default") )
                {
                    throw new Exception($connection->error);
                }

                $connection->close();
            }

        }
        catch(Exception $e)
        {
            echo '<span style="color:red"> Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
           // echo '<br />Informacja deweloperska: '.$e;
        }
    

        unset($_SESSION['sign_up_success']);
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome!</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="welcome.css" />
</head>
<body>

    <div class="container mx-auto my-auto d-flex flex-row justify-content-center align-items-center" height="40px">
        <div class="container mx-auto my-auto" >
            <img src="https://images.unsplash.com/photo-1519834785169-98be25ec3f84?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8M3x8c3VjY2Vzc3xlbnwwfHwwfHw%3D&auto=format&fit=crop&w=600&q=60" 
            class="img-fluid" alt="">
        </div>

    <div class="container d-flex flex-column align-items-center">
    Dziękujemy za rejestrację! Możesz już <a href="sign-in.php">zalogować się</a> na swoje konto! 

    </div>  

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>

    
</body>
</html>
<?php

    session_start();

    if (!isset($_SESSION['sign_up_success']))
    {
       // header('Location: sign-in.php');
       // exit();
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
    
    <div class="container-fluid m-md-5 p-md-5  d-flex justify-content-center flex-row align-items-center">
        <img src="https://images.unsplash.com/photo-1519834785169-98be25ec3f84?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MXx8c3VjY2Vzc3xlbnwwfDF8MHx8&auto=format&fit=crop&w=500&q=60" 
        alt="" class="img-fluid img-thumbnail p-5 m-5">
        <div class="container welcome d-flex justify-conetnt-center text-center align-items-center flex-column p-md-5 m-md-5 py-4">
            <h1>Gratulacje! </h1> <span>Pierwszy krok w kierunku wolności finansowej wykonany!</span>
             <span>Potrzebujesz jeszcze garści inspiracji? </span>
             <a href="https://www.youtube.com/watch?v=3Td8aeugXhY">Kliknij tutaj! </a> <br> 
             <span> Chcesz od razu działać dalej? </span>
             <a href="sign-in.php"> Zaloguj się po raz pierwszy na swoje konto! </a>
        </div>

    </div>

    
   

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>

    
</body>
</html>
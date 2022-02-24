<?php
    session_start();
    if(isset($_POST['email']))
    {
        $everything_OK = true; 

        $username = $_POST['username'];
        if (!preg_match('/^[\p{Latin}\s]+$/u', $username))
        {
            $everything_OK = false; 
            $_SESSION['e_username'] = "Imię może składać się tylko z liter.";
        }

        $email = $_POST['email'];
        $emailS = filter_var($email, FILTER_SANITIZE_EMAIL);

        if(filter_var($emailS, FILTER_VALIDATE_EMAIL) == false || $emailS != $email)
        {
            $everything_OK = false; 
            $_SESSION['e_email'] = "Podaj poprawny adres e-mail.";
        }


        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];

        if($password1 != $password2)
        {
            $everything_OK = false; 
            $_SESSION['e_password'] = "Podane hasła muszą być jednakowe!";
        }

        $password_hash = password_hash($password1, PASSWORD_DEFAULT);

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
                $result = $connection->query("SELECT id FROM users WHERE email='$email'");

                if(!$result) throw new Exception ($connection->error);
                $howManyEmails = $result->num_rows;

                if ($howManyEmails>0)
                {
                    $everything_OK = false; 
                    $_SESSION['e_email'] = "Istnieje już konto o takim adresie e-mail!";
                }

                
                if($everything_OK == true)
                {
                    if($connection->query("INSERT INTO users VALUES (NULL,'$username', '$password_hash', '$email')") )
                    {   
                        $_SESSION['email'] = $email;
                        $_SESSION['sign_up_success'] = true; 
                        header('Location: welcome.php');
                    }
                    else
                    {
                        throw new Exception($connection->error);
                    }
                }
      
                $connection->close();
            }

        }
        catch(Exception $e)
        {
            echo '<span style="color:red"> Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
           // echo '<br />Informacja deweloperska: '.$e;
        }
       

    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="sign-up.css">
</head>

<body>

    <div class="container main d-flex flex-column px-4 py-5">
        <h1 class="display-6 text-center p-3">REJESTRACJA</h1>
        <form  method="post" class="fw-bold">

            <div
                class="social-container p-2 align-items-center justify-content-evenly d-flex align-items-center justify-content-center">
                <a href="#" class="social ml-3"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                        fill="currentColor" style="color:#023047" class="bi bi-google" viewBox="0 0 16 16">
                        <path
                            d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z" />
                    </svg></a>
                <a href="#" class="social"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                        fill="currentColor" style="color:#023047" class="bi bi-facebook" viewBox="0 0 16 16">
                        <path
                            d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                    </svg></a>
                <a href="#" class="social"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                        fill="currentColor" style="color:#023047" class="bi bi-linkedin" viewBox="0 0 16 16">
                        <path
                            d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z" />
                    </svg></a>
            </div>
            <hr>
            <div class="d-flex align-self-center p-3 fw-bold align-items-center justify-content-center"><span>lub podaj swoje dane</span></div>

            <div class="input-group align-self-center m-3 px-5">
                <label for="name" class="form-label"> </label>
                <span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                        <path
                            d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                    </svg></span>
                <input type="text" name="username" minlength="2" maxlength="20" class="form-control rounded" id="name" aria-describedby="name" required
                 placeholder="Wpisz swoje imię" required>
            </div>
            <?php
                if(isset($_SESSION['e_username']))
                {
                    echo '<div style="color:red">'.$_SESSION['e_username'].'</div>';
                    unset($_SESSION['e_username']);
                }
            ?>

            <div class="input-group align-self-center m-3 px-5">
                <label for="email" class="form-label"> </label>
                <span class="input-group-text">@</span>
                <input type="email" name="email" class="form-control rounded" id="email" aria-describedby="email" required
                    placeholder="Wpisz swój adres e-mail" required>
            </div>
            <?php
                if(isset($_SESSION['e_email']))
                {
                    echo '<div style="color:red">'.$_SESSION['e_email'].'</div>';
                    unset($_SESSION['e_email']);
                }
            ?>

            <div class="input-group m-3 px-5">
                <label for="password" class="form-label"> </label>
                <input type="password" name="password1" minlength="5" maxlength="20" class="form-control rounded" id="password" aria-describedby="password"
                    placeholder="Wpisz hasło" required>
                    <span class="input-group-text"><i class="bi bi-eye-slash" id="togglePassword"></i></span>
            </div>

            <div class="input-group m-3 px-5">
                <label for="password2" class="form-label"> </label>
                <input type="password" name="password2" class="form-control rounded" id="password2" aria-describedby="password2"
                    placeholder="Wpisz hasło ponownie" required>
                    <span class="input-group-text"><i class="bi bi-eye-slash" id="togglePassword2"></i></span>
            </div>
            <?php
                if(isset($_SESSION['e_password']))
                {
                    echo '<div style="color:red">'.$_SESSION['e_password'].'</div>';
                    unset($_SESSION['e_password']);
                }
            ?>


            <div class=" d-flex justify-content-center">
                <button type="submit" class="btn m-3 p-3" id="register">ZAŁÓŻ KONTO</button>
            </div>

            <div class="container d-flex align-items-center justify-content-center">
                Masz już konto? <a href="sign-in.php" class="m-1 " id="signIn"> Zaloguj się </a>
            </div>


        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <script src="sign-up.js"></script>


</body>

</html>
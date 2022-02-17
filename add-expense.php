<?php
   
   session_start();

    if(!isset($_SESSION['logged']))
    {
        header('Location: sign-in.php');
        exit();
    }

    if(isset($_POST['amount']))
    {
        $expense_ok = true; 

        $amount = $_POST['amount'];
        if($amount<0)
        {
            $expense_ok = false; 
            $_SESSION['e_amount'] = "Kwota wydatku nie może być mniejsza od 0!";
        }

        $date = $_POST['date'];

        //sprawdzenie czy data jest późniejsza od daty dzisiejszej 

        $payment_method = $_POST['payment_method'];
       // sprawdzenie czy któraś jest zaznaczona

        $category = $_POST['category'];

       if($category == "")
        {
            $expense_ok = false; 
            $_SESSION['e_category'] = "Wybierz kategorię!";
        }

        $comment='';
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
               
                if($expense_ok == true)
                {
                    $id = $_SESSION['id'];
                    //tu też pobranie id payment-method i id expense- category

                    if( $connection->query("INSERT INTO expenses
                    VALUES (NULL, '$id' ,''  ,  '' ,'$amount', '$date', '$comment')") )
                    {
                        $_SESSION['expense_success'] = true; 
                        header('Location: main-menu.php');
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
            echo '<span style="color:red"> Błąd serwera! Przepraszamy za niedogodności i prosimy o dodanie wydatku w innym terminie!</span>';
            echo '<br />Informacja deweloperska: '.$e;
        }
       

  
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Expense</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="add-expense.css">
</head>

<body>
    <nav class="navbar sticky-top  navbar-light bg-light opacity-75">
        <div class="container-fluid">
            <span class="navbar-brand  mx-4 fs-4 fw-bold" id="name">
                <img class="img-fluid" width="6%"" src=" https://image.flaticon.com/icons/png/512/417/417095.png?w=740"
                    alt="">
                Personal Budget App
            </span>
        </div>
    </nav>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>Pieniądze nie są celem. Pieniądze nie mają wartości. Wartość mają marzenia, które
                pieniądze pomogą zarealizować.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            <cite title="Source Title">Robert Kiyosaki</cite>
        </figcaption>
    </figure>


    <div class="container main d-flex flex-column align-items-center justify-content-center p-3 m-5 mx-auto">
        <h1 class="display-6 text-center align-self-center p-3 ">WYDATEK</h1>
        <form method="post" class="fw-bold">
            <div class="container d-flex flex-md-row flex-column align-items-center justify-content-center">
                <div class="input-group m-2">
                    <label for="amount" class="form-label align-self-center m-1">KWOTA WYDATKU</label>
                    <span class="input-group-text">PLN</span>
                    <input type="number" name="amount" class="form-control" id="amount" required>
                </div>
                <?php
                if(isset($_SESSION['e_amount']))
                {
                    echo '<div style="color:red">'.$_SESSION['e_amount'].'</div>';
                    unset($_SESSION['e_amount']);
                }
                ?>
                <div class="input-group m-2">
                    <label for="date" class="form-label align-self-center m-1 m-md-2">DATA WYDATKU</label>
                    <input type="date" name="date" class="form-control" id="date" onchange="myFunction(event)" required>
                </div>
            </div>

            <div class="container d-flex m-3 flex-column text-center justify-content-center">
                <label class="form-label fw-bolder m-3">METODA PŁATNOŚCI</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input payment-methods" type="radio" name="payment_method" id="cash"
                        value="cash">
                    <label class="form-check-label" for="cash">gotówka</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input payment-methods" type="radio" name="payment_method" id="credit-card"
                        value="credit-card">
                    <label class="form-check-label" for="credit-card">karta kredytowa</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input payment-methods" type="radio" name="payment_method" id="debit-card"
                        value="debit-card">
                    <label class="form-check-label" for="debit-card">karta debetowa</label>
                </div>
                 <!--tu trzeba będzie pobrać z bazy danych metody płatności do pokazania-->
            </div>



            <div class="container d-flex align-items-center justify-content-center flex-column">
                <div class="input-group m-3">
                    <label for="category"></label>
                    <span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="currentColor" class="bi bi-bookmark" viewBox="0 0 16 16">
                            <path
                                d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5V2zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1H4z" />
                        </svg></span>
                    <select class="form-select" id="category" name="category" aria-label="Default select example">
                        <option selected value="">WYBIERZ KATEGORIĘ</option>
                        <option value="food">jedzenie</option>
                        <!--tu trzeba będzie pobrać z bazy danych kategorie do pokazania-->
                    </select>
                </div>
                <?php
                if(isset($_SESSION['e_category']))
                {
                    echo '<div style="color:red">'.$_SESSION['e_category'].'</div>';
                    unset($_SESSION['e_category']);
                }
                ?>

                <!-- Button trigger modal -->
                <button type="button" id="comment" class="btn btn-sm m-2 d-none" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">
                    Dodaj komentarz
                </button>
            </div>

            <div class="container d-flex align-items-center justify-content-center flex-column m-3">
                <button type="submit" id="submit" class="btn m-2">DODAJ WYDATEK</button>
                <a href="main-menu.php" class="m-2">Wróć do menu głównego</a>
            </div>

        </form>

    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Twoje uwagi do wydatku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <label for="comment" class="form-label"></label>
                        <input type="text" id="comment" name="comment" class="form-control" placeholder="Wpisz tekst">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn mod2  mx-3 p-2">Zapisz</button>
                    <button type="button" class="btn btn-sm mod1 mx-3 p-2" data-bs-dismiss="modal">Zamknij</button>
                </div>
            </div>
        </div>
    </div>





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <script src="add-expense.js"></script>
</body>

</html>
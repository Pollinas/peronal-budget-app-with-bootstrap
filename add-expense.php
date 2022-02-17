<?php
   
   session_start();

    if(!isset($_SESSION['logged']))
    {
        header('Location: sign-in.php');
        exit();
    }

    $id = $_SESSION['id'];
    //rozpoczecie pobierania danych do kategorii i mmetod płatności
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
            $categories = $connection->query("SELECT name FROM expenses_category_assigned_to_users
            WHERE user_id =$id" );

             if(!$categories)
            {
                 throw new Exception($connection->error);
            }

            $payment_methods = $connection->query("SELECT name FROM payment_methods_assigned_to_users
            WHERE user_id =$id ");

             if(!$payment_methods)
            {
                 throw new Exception($connection->error);
            }
                
                $connection->close();
            }
        }
        catch(Exception $e)
        {
            echo '<span style="color:red"> Błąd serwera! Przepraszamy za niedogodności i prosimy o dodanie wydatku w innym terminie!</span>';
            echo '<br />Informacja deweloperska: '.$e;
        }
       
        //zakończenie


    if(isset($_POST['amount']))
    {
        $expense_ok = true; 

        $amount = $_POST['amount'];
        if($amount<=0)
        {
            $expense_ok = false; 
            $_SESSION['e_amount'] = "Kwota wydatku musi być większa od 0!";
        }

        $date = $_POST['date'];

        $payment_method = $_POST['payment_method'];
            
       if($payment_method == "")
       {
           $expense_ok = false; 
           $_SESSION['e_payment_method'] = "Wybierz metodę płatności!";
       }
        

        $category = $_POST['category'];

       if($category == "")
        {
            $expense_ok = false; 
            $_SESSION['e_category'] = "Wybierz kategorię!";
        }
        $comment='';

        if (isset($_POST['comment'])) $comment=$_POST['comment'];

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
                     // id payment-method i id expense- category
                    $stmt = $connection->prepare("SELECT id FROM expenses_category_assigned_to_users
                    WHERE user_id='$id' AND name='$category' limit 1");

                    if($stmt->execute())
                    {
                    $result = $stmt->get_result();
                    $value = $result->fetch_object();
                    $category_id= $value->id;
                    }
                    else
                    {
                        throw new Exception($connection->error);
                    }

                    $stmt = $connection->prepare("SELECT id FROM payment_methods_assigned_to_users
                     WHERE user_id ='$id' AND name='$payment_method' limit 1");

                    if($stmt->execute())
                    {
                    $result = $stmt->get_result();
                    $value = $result->fetch_object();
                    $payment_method_id= $value->id;
                    }
                    else
                    {
                        throw new Exception($connection->error);
                    }

                    

                    if( $connection->query("INSERT INTO expenses
                    VALUES ( NULL, $id , $category_id , $payment_method_id, $amount, '$date', '$comment' )" ) )
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
                    <input type="number" name="amount" class="form-control" id="amount"  required>
                </div>
               
                <div class="input-group m-2">
                    <label for="date" class="form-label align-self-center m-1 m-md-2">DATA WYDATKU</label>
                    <input type="date" name="date" class="form-control" id="date" onchange="myFunction(event)" required>
                </div>
            </div>
            <?php
                if(isset($_SESSION['e_amount']))
                {
                    echo '<div class="text-center" style="color:red">'.$_SESSION['e_amount'].'</div>';
                    unset($_SESSION['e_amount']);
                }
             ?>

            <div class="container d-flex m-3 flex-column text-center justify-content-center">
                <label class="form-label fw-bolder m-3">METODA PŁATNOŚCI</label>

                <?php
                       while($row=$payment_methods->fetch_assoc())
                    {  
                    ?>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input payment-methods" type="radio" name="payment_method" id="<?php echo $row['name'] ?>"
                        value="<?php echo $row['name'] ?>">
                    <label class="form-check-label" for="<?php echo $row['name'] ?>"><?php echo $row['name'] ?></label>
                     </div>
                      <?php }
                    ?>
              
            </div>
            <?php
                if(isset($_SESSION['e_payment_method']))
                {
                    echo '<div class="text-center" style="color:red">'.$_SESSION['e_payment_method'].'</div>';
                    unset($_SESSION['e_payment_method']);
                }
                ?>



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
                     <?php
                       while($row=$categories->fetch_assoc())
                       {  
                    ?>
                       <option value="<?php echo $row['name'] ?>"> <?php echo $row['name'] ?></option>
                      <?php }
                    ?>
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
                <button type="button" id="comment" class="btn btn-sm m-2" data-bs-toggle="modal"
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
                    <button type="submit"  class="btn mod2  m-4 ">Zapisz</button>
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
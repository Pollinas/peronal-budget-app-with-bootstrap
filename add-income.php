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
            $categories = $connection->query("SELECT name FROM incomes_category_assigned_to_users
            WHERE user_id =$id" );

             if(!$categories)
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

        if(isset($_POST['amount']))
    {
        $income_ok = true; 

        $amount = $_POST['amount'];
        if($amount<=0)
        {
            $income_ok = false; 
            $_SESSION['e_amount'] = "Kwota przychodu musi być większa od 0!";
        }

        $date = $_POST['date'];

        $category = $_POST['category'];

       if($category == "")
        {
            $income_ok = false; 
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
               
                if($income_ok == true)
                {
                     // get id income- category
                    $stmt = $connection->prepare("SELECT id FROM incomes_category_assigned_to_users
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


                    if( $connection->query("INSERT INTO incomes
                    VALUES ( NULL, $id , $category_id ,  $amount, '$date', '$comment' )" ) )
                    {
                        $_SESSION['income_success'] = true; 
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
            echo '<span style="color:red"> Błąd serwera! Przepraszamy za niedogodności i prosimy o dodanie przychodu w innym terminie!</span>';
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
    <title>Add Income</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="add-income.css">
</head>

<body>




    <div class="container-fluid align-items-center justify-content-center align-self-center main d-flex flex-column py-5 px-4 my-5">
        <h1 class="display-6 text-center align-self-center p-3 ">PRZYCHÓD</h1>
        <form method="post" class="fw-bold">
        <div class="container d-flex flex-md-row flex-column align-items-center justify-content-center px-5 p-md-1">
            <div class="input-group">
                <label for="amount" class="form-label align-self-center m-1">KWOTA PRZYCHODU</label>
                <span class="input-group-text">PLN</span>
                <input type="number" class="form-control" name="amount" id="amount" required>
            </div>
            <div class="input-group m-2 my-3 my-md-2">
                <label for="date" class="form-label align-self-center m-1">DATA PRZYCHODU</label>
                <input type="date" class="form-control" id="date" name="date" onchange="myFunction(event)" required>
            </div>
        </div>
        <?php
                if(isset($_SESSION['e_amount']))
                {
                    echo '<div class="text-center" style="color:red">'.$_SESSION['e_amount'].'</div>';
                    unset($_SESSION['e_amount']);
                }
             ?>



        <div class="container d-flex align-items-center justify-content-center flex-column mx-auto my-3 px-md-5 py-md-3">
            <div class="input-group  m-3 px-5">
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
                  <div class="m-2">
                <input type="text" class="form-control" name="comment" id="comment" placeholder="Komentarz (opcjonalnie)">
                </div>


        </div>

        <div class="container d-flex align-items-center justify-content-center flex-column m-3">
            <button type="submit" id="submit" class="btn m-3 p-3">DODAJ PRZYCHÓD</button>
            <a href="main-menu.php" class="m-2">Wróć do menu głównego</a>
        </div>
        </form>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <script src="add-income.js"></script>
</body>

</html>
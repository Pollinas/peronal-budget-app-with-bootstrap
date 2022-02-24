<?php
    session_start();

    if(!isset($_SESSION['logged']))
    {
        header('Location: sign-in.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="main-menu.css">
</head>

<body>
    <div class="pos-f-t">
        <div class="collapse " id="navbarToggleExternalContent">
            <div class="bg-dark p-4">
                <h5 class="h3 m-2" style="color: #EE666C">
                    Personal Budget App</h5>
               
                <ul>
                    <li class="fs-5 p-4"><a href="add-expense.php"><i class="bi bi-basket"></i><span class="m-2">Dodaj
                                wydatek</span></a></li>
                    <li class="p-4"><a href="add-income.php"><i class="bi bi-cash"></i><span class="m-2">Dodaj
                                przychód</span></a></li>
                    <li class="p-4"><a href="display-balance.php"><i class="bi bi-pie-chart-fill"></i><span
                                class="m-2">Wyświetl
                                bilans</span></a></li>
                    <li class="p-4"><a href="#"><i class="bi bi-gear"></i><span class="m-2">Ustawienia</span></a></li>
                    <li class="p-4"><a href="logout.php"><i class="bi bi-box-arrow-right"></i><span class="m-2">Wyloguj
                                się</span></a></li>
                </ul>
            </div>
        </div>
        <nav class=" navbar navbar-dark bg-dark p-2">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon m-1"></span> 
            </button>
        </nav>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</body>

</html>
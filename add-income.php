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
    <title>Add Income</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="add-income.css">
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
        <h1 class="display-6 text-center align-self-center p-3 ">PRZYCHÓD</h1>
        <div class="container d-flex flex-md-row flex-column align-items-center justify-content-center">
            <div class="input-group m-2">
                <label for="amount" class="form-label align-self-center m-1">KWOTA PRZYCHODU</label>
                <span class="input-group-text">PLN</span>
                <input type="number" class="form-control" id="amount">
            </div>
            <div class="input-group m-2">
                <label for="date" class="form-label align-self-center m-1 m-md-2">DATA PRZYCHODU</label>
                <input type="date" class="form-control" id="date">
            </div>
        </div>



        <div class="container d-flex align-items-center justify-content-center flex-column m-3">
            <div class="input-group m-3">
                <label for="category"></label>
                <span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        fill="currentColor" class="bi bi-bookmark" viewBox="0 0 16 16">
                        <path
                            d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5V2zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1H4z" />
                    </svg></span>
                <select class="form-select" id="category" aria-label="Default select example">
                    <option selected>WYBIERZ KATEGORIĘ</option>
                    <option value="salary">wynagrodzenie</option>
                    <option value="allegro-sale">sprzedaż na allegro</option>
                    <option value="bank-interest">odsetki bankowe</option>
                    <option value="other">inne</option>
                </select>
            </div>



            <!-- Button trigger modal -->
            <button type="button" id="comment" class="btn btn-sm m-3" data-bs-toggle="modal"
                data-bs-target="#exampleModal">
                Dodaj komentarz
            </button>
        </div>

        <div class="container d-flex align-items-center justify-content-center flex-column m-3">
            <button type="submit" id="submit" class="btn m-3">DODAJ PRZYCHÓD</button>
            <a href="main-menu.html" class="m-2">Wróć do menu głównego</a>
        </div>
        </form>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Twoje uwagi do przychodu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#">
                        <label for="comment" class="form-label"></label>
                        <input type="text" id="comment" class="form-control" placeholder="Wpisz tekst">
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
</body>

</html>
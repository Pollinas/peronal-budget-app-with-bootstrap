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
    <title>Display Balance</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="display-balance.css">
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

    <div class="container-fluid main d-flex flex-column align-items-center justify-content-center mx-auto">
        <div class="container d-flex align-items-center justify-content-between p-5">
            <h1 class="display-6">Przeglądaj bilans</h1>
            <div class="dropdown">
                <button class="btn dropdown-toggle" type="button" id="time" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Wybierz okres
                </button>
                <ul class="dropdown-menu" aria-labelledby="time">
                    <li><button class="dropdown-item" type="button" value="this-month" name="time">Bieżący
                            miesiąc</button></li>
                    <li><button class="dropdown-item" type="button" name="time" value="last-moth">Ubiegły
                            miesiąc</button></li>
                    <li><button class="dropdown-item" type="button" name="time" value="last-year">Ubiegły rok</button>
                    </li>
                    <li><button type="button" name="time" class="dropdown-item btn" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">Niestandardowy</button> </li>
                </ul>
            </div>
        </div>



        <div class=" row">
            <div class="container col-md m-2">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center" scope="col" colspan="4">Przychody</th>
                        </tr>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Data</th>
                            <th scope="col">Kwota [PLN]</th>
                            <th scope="col">Kategoria</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="salary">
                            <th scope="row">1</th>
                            <td>1900/12/10</td>
                            <td>1000</td>
                            <td>Wynagrodzenie</td>
                        </tr>
                        <tr class="bank-interest">
                            <th scope="row">2</th>
                            <td>1900/12/10</td>
                            <td>1000</td>
                            <td>Odsetki bankowe</td>
                        </tr>
                        <tr class="allegro-sale">
                            <th scope="row">3</th>
                            <td>1900/12/10</td>
                            <td>1000</td>
                            <td>Sprzedaż na allegro</td>
                        </tr>
                        <tr class="other">
                            <th scope="row">4</th>
                            <td>1900/12/10</td>
                            <td>1000</td>
                            <td>Inne</td>
                        </tr>
                    </tbody>

                </table>
                <div class="pie-chart" id="income-pie-chart"></div>
            </div>

            <div class="container col-md m-2">

                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="4" class="text-center" scope="col">Wydatki</th>
                        </tr>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Data</th>
                            <th scope="col">Kwota [PLN]</th>
                            <th scope="col">Kategoria</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="food">
                            <th scope="row">1</th>
                            <td>1900/12/10</td>
                            <td>1000</td>
                            <td>Jedzenie</td>
                        </tr>
                        <tr class="rent">
                            <th scope="row">2</th>
                            <td>1900/12/10</td>
                            <td>1000</td>
                            <td>Mieszkanie</td>
                        </tr>
                        <tr class="transportation">
                            <th scope="row">3</th>
                            <td>1900/12/10</td>
                            <td>1000</td>
                            <td>Transport</td>
                        </tr>
                        <tr class="telecomunication">
                            <th scope="row">4</th>
                            <td>1900/12/10</td>
                            <td>1000</td>
                            <td>Telekomunikacja</td>
                        </tr>
                        <tr class="health-care">
                            <th scope="row">5</th>
                            <td>1900/12/10</td>
                            <td>1000</td>
                            <td>Opieka zdrowotna</td>
                        </tr>
                        <tr class="clothing">
                            <th scope="row">6</th>
                            <td>1900/12/10</td>
                            <td>1000</td>
                            <td>Ubrania</td>
                        </tr>
                        <tr class="hygiene">
                            <th scope="row">7</th>
                            <td>1900/12/10</td>
                            <td>1000</td>
                            <td>Higiena</td>
                        </tr>
                        <tr class="children">
                            <th scope="row">8</th>
                            <td>1900/12/10</td>
                            <td>1000</td>
                            <td>Dzieci</td>
                        </tr>
                        <tr class="entertainment">
                            <th scope="row">9</th>
                            <td>1900/12/10</td>
                            <td>1000</td>
                            <td>Rozrywka</td>
                        </tr>
                        <tr class="travel">
                            <th scope="row">10</th>
                            <td>1900/12/10</td>
                            <td>1000</td>
                            <td>Podróże</td>
                        </tr>
                        <tr class="schooling">
                            <th scope="row">11</th>
                            <td>1900/12/10</td>
                            <td>1000</td>
                            <td>Szkolenia</td>
                        </tr>
                        <tr class="books">
                            <th scope="row">12</th>
                            <td>1900/12/10</td>
                            <td>1000</td>
                            <td>Książki</td>
                        </tr>
                        <tr class="savings">
                            <th scope="row">13</th>
                            <td>1900/12/10</td>
                            <td>1000</td>
                            <td>Oszczędności</td>
                        </tr>
                        <tr class="retirement">
                            <th scope="row">14</th>
                            <td>1900/12/10</td>
                            <td>1000</td>
                            <td>Na emeryturę</td>
                        </tr>
                        <tr class="debt">
                            <th scope="row">15</th>
                            <td>1900/12/10</td>
                            <td>1000</td>
                            <td>Spłata długów</td>
                        </tr>
                        <tr class="donation">
                            <th scope="row">16</th>
                            <td>1900/12/10</td>
                            <td>4000</td>
                            <td>Darowizna</td>
                        </tr>
                        <tr class="other">
                            <th scope="row">17</th>
                            <td>1900/12/10</td>
                            <td>1000</td>
                            <td>Inne</td>
                        </tr>
                    </tbody>
                </table>

                <div class="pie-chart" id="expense-pie-chart"></div>

            </div>

        </div>

        <div class="alert alert-danger align-self-center" role="alert">
            <h4 class="alert-heading text-center">UWAGA!</h4>
            <hr>
            <p class="mb-0 balance-amount">Twój bilans z wybranego okresu wynosi: -16 000 PLN</p>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Wybierz przedział czasowy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#">

                        <label for="beginning">od</label>
                        <input class="input-control m-2" type="date" id="beginning">

                        <label for="end">do</label>
                        <input class="input-control m-2 " type="date" id="end">

                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal">Zamknij</button>
                    <button type="button" class="btn">Zapisz zmiany</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</body>

</html>
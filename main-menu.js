
let description = document.querySelector('#description');

// opis inspiracja
const blue = document.querySelector('.blue');
blue.onmouseover = inspirationMouseOver;
blue.onmouseout = inspirationMouseOut;


function inspirationMouseOver() {
    description.style.display = 'block';
    description.innerHTML = "Zainspiruj się!";
}

function inspirationMouseOut() {
    description.style.display = 'none';
}

//opis wyświetl bilans

const green = document.querySelector('.green');
green.onmouseover = balanceMouseOver;
green.onmouseout = balanceMouseOut;


function balanceMouseOver() {
    description.style.display = 'block';
    description.innerHTML = "Wyświetl bilans";
}

function balanceMouseOut() {
    description.style.display = 'none';
}

//opis dodaj wydatek

const red = document.querySelector('.red');
red.onmouseover = expenseMouseOver;
red.onmouseout = expenseMouseOut;


function expenseMouseOver() {
    description.style.display = 'block';
    description.innerHTML = "Dodaj wydatek";
}

function expenseMouseOut() {
    description.style.display = 'none';
}

//opis dodaj przychód

const income = document.querySelector('.purple');
income.onmouseover = incomeMouseOver;
income.onmouseout = incomeMouseOut;


function incomeMouseOver() {
    description.style.display = 'block';
    description.innerHTML = "Dodaj przychód";
}

function incomeMouseOut() {
    description.style.display = 'none';
}


//opis ustawienia

const settings = document.querySelector('.orange');
settings.onmouseover = settingsMouseOver;
settings.onmouseout = settingsMouseOut;


function settingsMouseOver() {
    description.style.display = 'block';
    description.innerHTML = "Ustawienia";
}

function settingsMouseOut() {
    description.style.display = 'none';
}

//opis wyloguj się
const logOut = document.querySelector('.lightblue');
logOut.onmouseover = logOutMouseOver;
logOut.onmouseout = logOutMouseOut;

function logOutMouseOver() {
    description.style.display = 'block';
    description.innerHTML = "Wyloguj się";
}

function logOutMouseOut() {
    description.style.display = 'none';
}
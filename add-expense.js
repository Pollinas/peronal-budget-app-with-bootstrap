
//document.getElementById('date').defaultvalue = new Date().toISOString().substring(0, 10);


function myFunction(e) {
    var date = document.querySelector("#date").value;
    var varDate = new Date(date);
    var today = new Date();


    if (varDate > today) {
        alert("Data wydatku nie może być późniejsza od dzisiejszej daty!");
        document.getElementById("date").valueAsDate = null;
    }

}

function checkAmount(e) {
    var amount = document.querySelector("#amount").value;

    if (amount <= 0) {
        alert("Kwota wydatku musi być większa od 0!");
        document.getElementById("#amount").value = null;
    }

}


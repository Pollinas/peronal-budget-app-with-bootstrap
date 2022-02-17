
document.getElementById('date').value = new Date().toISOString().substring(0, 10);

function myFunction(e) {
    var date = document.querySelector("#date").value;
    var varDate = new Date(date);

    if (varDate > today) {
        alert("Data wydatku nie może być późniejsza od dzisiejszej daty!");
        document.getElementById("date").valueAsDate = null;
    }
}


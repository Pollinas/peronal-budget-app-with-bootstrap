
Date.prototype.toDateInputValue = (function () {
    var local = new Date(this);
    local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
    return local.toJSON().slice(0, 10);
});

document.getElementById('date').value = new Date().toDateInputValue();

function myFunction(e) {
    var date = document.querySelector("#date").value;
    var varDate = new Date(date);
    var today = new Date();


    if (varDate > today) {
        alert("Data wydatku nie może być późniejsza od dzisiejszej daty!");
        document.getElementById("date").valueAsDate = null;
    }

}
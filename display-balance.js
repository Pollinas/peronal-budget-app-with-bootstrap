

function myFunction(e) {
    var option_value = document.getElementById("time").value;
    let none = document.querySelector(".none")
    if (option_value == "custom") {
        var myModal = new bootstrap.Modal(document.getElementById('exampleModal'), {});
        myModal.show();
    }
}



function checkEndDate(e) {
    var end = document.querySelector("#end").value;
    var begin = document.querySelector("#begin").value;

    var endDate = new Date(end);
    var beginDate = new Date(begin);
    var today = new Date();

    if (endDate > today) {
        alert("Data końcowa nie może być późniejsza od dzisiejszej daty!");
        document.getElementById("end").valueAsDate = null;
    }


    if (beginDate > endDate) {
        alert("Data końcowa bilansu nie może być wcześniejsza od jego daty początkowej!");
        document.getElementById("end").valueAsDate = null;
    }
}

function checkBeginDate(e) {

    var begin = document.querySelector("#begin").value;

    var beginDate = new Date(begin);
    var today = new Date();

    if (beginDate > today) {
        alert("Data początkowa nie może być późniejsza od dzisiejszej daty!");
        document.getElementById("begin").valueAsDate = null;
    }

}


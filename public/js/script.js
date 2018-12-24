var tagFormDiv = document.getElementById("tagForm");
var tagFormButton = document.getElementById("tagFormButton");

function showForm(){
    if (tagFormDiv.style.visibility === "visible") {
        tagFormDiv.style.visibility = "hidden";
        tagFormButton.innerHTML = "Add new todo";
    } else {
        tagFormDiv.style.visibility = "visible";
        tagFormButton.innerHTML = "Close form";
    }
}
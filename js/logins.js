document.getElementById("loginForm").addEventListener("submit", function(e){

e.preventDefault();

let usuario = document.getElementById("usuario").value.trim();
let password = document.getElementById("password").value.trim();
let error = document.getElementById("error");

if(usuario === "" || password === ""){
    error.style.display = "block";
    error.innerText = "Todos los campos son obligatorios";
}else{
    error.style.display = "none";
    window.location.href = "panel.php";
}

});
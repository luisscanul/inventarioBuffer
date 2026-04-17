document.getElementById("loginForm").addEventListener("submit", function(e){

e.preventDefault();

let usuario = document.getElementById("usuario").value;
let password = document.getElementById("password").value;

let formData = new FormData();

formData.append("usuario", usuario);
formData.append("password", password);

fetch("login.php", {

method: "POST",
body: formData

})

.then(response => response.text())

.then(data => {

console.log("Respuesta:", data); // IMPORTANTE

if(data.trim() === "OK"){

window.location.href = "panel.php";

}else{

document.getElementById("error").style.display = "block";

}

});

});
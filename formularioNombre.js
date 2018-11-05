document.getElementById('boton_cambiar').addEventListener("click",alertSeguridad);

document.getElementById('btn_cambiarNombre').addEventListener("click",openForm);


document.getElementById('btn_a').addEventListener("click",a);

function a() {
   alert("Aaaaaaaaaaaaaaaaaaaaaaaa");
}

function alertSeguridad() {
    if (confirm("Press a button!")) {
    } 
    else {
        location.href ="perfilPropioProf.php";
    }
}

function openForm() {
    document.getElementById("myForm").style.display = "block";
}

function closeForm() {
    document.getElementById("myForm").style.display = "none";
}
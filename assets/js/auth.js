const form = document.getElementById("loginForm");

form.addEventListener("submit", async function(e){

    e.preventDefault();

    const usuario = document.getElementById("usuario").value;
    const contrasena = document.getElementById("contrasena").value;

    try{

        const respuesta = await fetch(
            "http://localhost:8000/login",
            {
                method: "POST",

                headers:{
                    "Content-Type":"application/json"
                },

                body: JSON.stringify({
                    usuario: usuario,
                    contrasena: contrasena
                })
            }
        );

        const datos = await respuesta.json();

        if(datos.success){

            localStorage.setItem(
                "token",
                datos.token
            );

            localStorage.setItem(
                "usuario",
                datos.usuario
            );

            localStorage.setItem(
                "nombre",
                datos.nombre
            );

            window.location.href = "dashboard.php";

        }else{

            document.getElementById("mensaje").innerText =
                datos.mensaje;
        }

    }catch(error){

        document.getElementById("mensaje").innerText =
            "Error al conectar con el servidor.";

        console.error(error);
    }

});
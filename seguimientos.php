<!DOCTYPE html>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Seguimientos</title>

```
<link rel="stylesheet" href="assets/css/style.css">

<style>
    .container{
        max-width:1300px;
        margin:30px auto;
        padding:20px;
    }

    .top-bar{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    }

    table{
        width:100%;
        border-collapse:collapse;
        background:white;
    }

    th,td{
        border:1px solid #ddd;
        padding:10px;
        text-align:center;
    }

    th{
        background:#007bff;
        color:white;
    }

    .btn{
        padding:8px 12px;
        border:none;
        border-radius:5px;
        cursor:pointer;
        color:white;
    }

    .btn-primary{
        background:#007bff;
    }

    .btn-warning{
        background:#f0ad4e;
    }

    .btn-danger{
        background:#dc3545;
    }

    .btn-secondary{
        background:#6c757d;
    }

    #formulario{
        display:none;
        background:white;
        padding:20px;
        margin-bottom:20px;
        border-radius:10px;
        border:1px solid #ddd;
    }

    input, select{
        width:100%;
        padding:10px;
        margin-bottom:10px;
        box-sizing:border-box;
    }

    h1{
        margin:0;
    }
</style>
```

</head>

<body>

<div class="container">

```
<div class="top-bar">

    <h1>Seguimientos</h1>

    <div>

        <button class="btn btn-primary"
                onclick="mostrarFormulario()">
            Nuevo Seguimiento
        </button>

        <button class="btn btn-secondary"
                onclick="window.location='dashboard.php'">
            Volver
        </button>

    </div>

</div>

<div id="formulario">

    <input type="hidden" id="id">

    <input type="number"
           id="incapacidad_id"
           placeholder="ID Incapacidad"
           required>

    <input type="date"
           id="fecha_seguimiento"
           required>

    <input type="text"
           id="observaciones"
           placeholder="Observaciones">

    <input type="text"
           id="recomendaciones"
           placeholder="Recomendaciones">

    <input type="date"
           id="proxima_revision">

    <select id="estado">

        <option value="pendiente">
            Pendiente
        </option>

        <option value="en_revision">
            En revisión
        </option>

        <option value="aprobada">
            Aprobada
        </option>

        <option value="rechazada">
            Rechazada
        </option>

        <option value="finalizada">
            Finalizada
        </option>

    </select>

    <button class="btn btn-primary"
            onclick="guardar()">
        Guardar
    </button>

    <button class="btn btn-secondary"
            onclick="ocultarFormulario()">
        Cancelar
    </button>

</div>

<table>

    <thead>
        <tr>
            <th>ID</th>
            <th>Incapacidad</th>
            <th>Fecha</th>
            <th>Observaciones</th>
            <th>Recomendaciones</th>
            <th>Próxima revisión</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody id="tabla"></tbody>

</table>
```

</div>

<script>

const API = "http://localhost:8003";
const token = localStorage.getItem("token");

if(!token){
    window.location = "index.php";
}

listar();

async function listar(){

    try{

        const response = await fetch(API + "/seguimientos",{
            headers:{
                Authorization: "Bearer " + token
            }
        });

        const datos = await response.json();

        let html = "";

        datos.forEach(s => {

            html += `
            <tr>
                <td>${s.id}</td>
                <td>${s.incapacidad_id}</td>
                <td>${s.fecha_seguimiento || ""}</td>
                <td>${s.observaciones || ""}</td>
                <td>${s.recomendaciones || ""}</td>
                <td>${s.proxima_revision || ""}</td>
                <td>${s.estado || ""}</td>

                <td>

                    <button class="btn btn-warning"
                            onclick="editar(${s.id})">
                        Editar
                    </button>

                    <button class="btn btn-danger"
                            onclick="eliminarSeguimiento(${s.id})">
                        Eliminar
                    </button>

                </td>
            </tr>
            `;
        });

        document.getElementById("tabla").innerHTML = html;

    }catch(error){

        console.error(error);

        alert("Error cargando seguimientos");
    }
}

function mostrarFormulario(){

    document.getElementById("formulario").style.display = "block";
}

function ocultarFormulario(){

    document.getElementById("formulario").style.display = "none";

    document.getElementById("id").value = "";
    document.getElementById("incapacidad_id").value = "";
    document.getElementById("fecha_seguimiento").value = "";
    document.getElementById("observaciones").value = "";
    document.getElementById("recomendaciones").value = "";
    document.getElementById("proxima_revision").value = "";
    document.getElementById("estado").value = "pendiente";
}

async function guardar(){

    const id = document.getElementById("id").value;

    const datos = {

        incapacidad_id:
            document.getElementById("incapacidad_id").value,

        fecha_seguimiento:
            document.getElementById("fecha_seguimiento").value,

        observaciones:
            document.getElementById("observaciones").value,

        recomendaciones:
            document.getElementById("recomendaciones").value,

        proxima_revision:
            document.getElementById("proxima_revision").value,

        estado:
            document.getElementById("estado").value
    };

    if(!datos.fecha_seguimiento){

        alert("Debe ingresar una fecha");

        return;
    }

    try{

        if(id === ""){

            await fetch(API + "/seguimientos",{

                method:"POST",

                headers:{
                    "Content-Type":"application/json",
                    Authorization:"Bearer " + token
                },

                body: JSON.stringify(datos)
            });

        }else{

            await fetch(API + "/seguimientos/" + id,{

                method:"PUT",

                headers:{
                    "Content-Type":"application/json",
                    Authorization:"Bearer " + token
                },

                body: JSON.stringify(datos)
            });
        }

        ocultarFormulario();

        listar();

    }catch(error){

        console.error(error);

        alert("Error guardando seguimiento");
    }
}

async function editar(id){

    try{

        const response = await fetch(
            API + "/seguimientos/" + id,
            {
                headers:{
                    Authorization:"Bearer " + token
                }
            }
        );

        const s = await response.json();

        mostrarFormulario();

        document.getElementById("id").value =
            s.id || "";

        document.getElementById("incapacidad_id").value =
            s.incapacidad_id || "";

        document.getElementById("fecha_seguimiento").value =
            s.fecha_seguimiento || "";

        document.getElementById("observaciones").value =
            s.observaciones || "";

        document.getElementById("recomendaciones").value =
            s.recomendaciones || "";

        document.getElementById("proxima_revision").value =
            s.proxima_revision || "";

        document.getElementById("estado").value =
            s.estado || "pendiente";

    }catch(error){

        console.error(error);

        alert("Error cargando seguimiento");
    }
}

async function eliminarSeguimiento(id){

    if(!confirm("¿Desea eliminar este seguimiento?")){
        return;
    }

    try{

        await fetch(API + "/seguimientos/" + id,{

            method:"DELETE",

            headers:{
                Authorization:"Bearer " + token
            }
        });

        listar();

    }catch(error){

        console.error(error);

        alert("Error eliminando seguimiento");
    }
}

</script>

</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empleados</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .container{
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }

        .top-bar{
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td{
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th{
            background-color: #007bff;
            color: white;
        }

        .btn{
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: white;
        }

        .btn-primary{
            background-color: #007bff;
        }

        .btn-warning{
            background-color: #f0ad4e;
        }

        .btn-danger{
            background-color: #dc3545;
        }

        .btn-secondary{
            background-color: #6c757d;
        }

        #formulario{
            display: none;
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
        }

        input{
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }

        h1{
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="top-bar">
        <h1>Empleados</h1>

        <div>
            <button class="btn btn-primary" onclick="mostrarFormulario()">
                Nuevo Empleado
            </button>

            <button class="btn btn-secondary" onclick="window.location='dashboard.php'">
                Volver
            </button>
        </div>
    </div>

    <div id="formulario">

        <input type="hidden" id="empleadoId">

        <input type="text" id="nombres" placeholder="Nombres">

        <input type="text" id="apellidos" placeholder="Apellidos">

        <input type="text" id="documento" placeholder="Documento">

        <input type="email" id="correo" placeholder="Correo">

        <input type="text" id="telefono" placeholder="Teléfono">

        <input type="text" id="cargo" placeholder="Cargo">

        <input type="text" id="area" placeholder="Área">

        <input type="date" id="fecha_ingreso">

        <button class="btn btn-primary" onclick="guardarEmpleado()">
            Guardar
        </button>

        <button class="btn btn-secondary" onclick="ocultarFormulario()">
            Cancelar
        </button>

    </div>

    <table>

        <thead>
            <tr>
                <th>ID</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Documento</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Cargo</th>
                <th>Área</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody id="tablaEmpleados">
        </tbody>

    </table>

</div>

<script>

const API = "http://localhost:8001";

const token = localStorage.getItem("token");

if(!token){
    window.location.href = "index.php";
}

listarEmpleados();

async function listarEmpleados(){

    const respuesta = await fetch(
        API + "/empleados",
        {
            headers:{
                Authorization: token
            }
        }
    );

    const empleados = await respuesta.json();

    let html = "";

    empleados.forEach(emp => {

        html += `
        <tr>
            <td>${emp.id}</td>
            <td>${emp.nombres}</td>
            <td>${emp.apellidos}</td>
            <td>${emp.documento}</td>
            <td>${emp.correo}</td>
            <td>${emp.telefono}</td>
            <td>${emp.cargo}</td>
            <td>${emp.area}</td>
            <td>${emp.estado}</td>

            <td>

                <button
                    class="btn btn-warning"
                    onclick='editarEmpleado(${JSON.stringify(emp)})'>
                    Editar
                </button>

                <button
                    class="btn btn-danger"
                    onclick='eliminarEmpleado(${emp.id})'>
                    Desactivar
                </button>

            </td>
        </tr>
        `;
    });

    document.getElementById("tablaEmpleados").innerHTML = html;
}

function mostrarFormulario(){
    document.getElementById("formulario").style.display = "block";
}

function ocultarFormulario(){

    document.getElementById("formulario").style.display = "none";

    document.getElementById("empleadoId").value = "";

    document.querySelectorAll("#formulario input").forEach(input=>{
        input.value = "";
    });
}

async function guardarEmpleado(){

    const id = document.getElementById("empleadoId").value;

    const datos = {

        nombres: document.getElementById("nombres").value,

        apellidos: document.getElementById("apellidos").value,

        documento: document.getElementById("documento").value,

        correo: document.getElementById("correo").value,

        telefono: document.getElementById("telefono").value,

        cargo: document.getElementById("cargo").value,

        area: document.getElementById("area").value,

        fecha_ingreso: document.getElementById("fecha_ingreso").value,

        estado: "activo"
    };

    if(id === ""){

        await fetch(API + "/empleados",{

            method: "POST",

            headers:{
                "Content-Type":"application/json",
                Authorization: token
            },

            body: JSON.stringify(datos)
        });

    }else{

        await fetch(API + "/empleados/" + id,{

            method: "PUT",

            headers:{
                "Content-Type":"application/json",
                Authorization: token
            },

            body: JSON.stringify(datos)
        });
    }

    ocultarFormulario();

    listarEmpleados();
}

function editarEmpleado(emp){

    mostrarFormulario();

    document.getElementById("empleadoId").value = emp.id;

    document.getElementById("nombres").value = emp.nombres;

    document.getElementById("apellidos").value = emp.apellidos;

    document.getElementById("documento").value = emp.documento;

    document.getElementById("correo").value = emp.correo;

    document.getElementById("telefono").value = emp.telefono;

    document.getElementById("cargo").value = emp.cargo;

    document.getElementById("area").value = emp.area;

    document.getElementById("fecha_ingreso").value = emp.fecha_ingreso;
}

async function eliminarEmpleado(id){

    if(!confirm("¿Desea desactivar este empleado?")){
        return;
    }

    await fetch(API + "/empleados/" + id,{

        method: "DELETE",

        headers:{
            Authorization: token
        }
    });

    listarEmpleados();
}

</script>

</body>
</html>
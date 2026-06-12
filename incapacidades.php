<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Incapacidades</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .container{
            max-width: 1300px;
            margin: 30px auto;
            padding: 20px;
        }

        .top-bar{
            display:flex;
            justify-content:space-between;
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
        }

        input{
            width:100%;
            padding:10px;
            margin-bottom:10px;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="top-bar">

        <h1>Incapacidades</h1>

        <div>
            <button class="btn btn-primary" onclick="mostrarFormulario()">
                Nueva Incapacidad
            </button>

            <button class="btn btn-secondary"
                    onclick="window.location='dashboard.php'">
                Volver
            </button>
        </div>

    </div>

    <div id="formulario">

        <input type="hidden" id="id">

        <input type="number" id="empleado_id" placeholder="Empleado ID">

        <input type="date" id="fecha_inicio">

        <input type="date" id="fecha_fin">

        <select id="tipo">
            <option value="">Seleccione un tipo</option>
            <option value="enfermedad_general">Enfermedad general</option>
            <option value="accidente_laboral">Accidente laboral</option>
            <option value="licencia_medica">Licencia médica</option>
            <option value="incapacidad_temporal">Incapacidad temporal</option>
        </select>

        <input type="text"
               id="diagnostico_general"
               placeholder="Diagnóstico">

        <input type="text"
               id="entidad_medica"
               placeholder="Entidad médica">

        <input type="text"
               id="observaciones"
               placeholder="Observaciones">

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
                <th>Empleado</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Tipo</th>
                <th>Diagnóstico</th>
                <th>Entidad</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody id="tabla"></tbody>

    </table>

</div>

<script>

const API="http://localhost:8002";

const token=localStorage.getItem("token");

if(!token){
    window.location="index.php";
}

listar();

async function listar(){

    const response=await fetch(API+"/incapacidades",{
        headers:{
            Authorization:token
        }
    });

    const datos=await response.json();

    let html="";

    datos.forEach(i=>{

        html+=`
        <tr>
            <td>${i.id}</td>
            <td>${i.empleado_id}</td>
            <td>${i.fecha_inicio}</td>
            <td>${i.fecha_fin}</td>
            <td>${i.tipo}</td>
            <td>${i.diagnostico_general}</td>
            <td>${i.entidad_medica}</td>
            <td>${i.estado}</td>

            <td>

                <button class="btn btn-warning"
                        onclick='editar(${JSON.stringify(i)})'>
                    Editar
                </button>

                <button class="btn btn-danger"
                        onclick='finalizar(${i.id})'>
                    Finalizar
                </button>

            </td>
        </tr>
        `;
    });

    document.getElementById("tabla").innerHTML=html;
}

function mostrarFormulario(){

    document.getElementById("formulario").style.display="block";
}

function ocultarFormulario(){

    document.getElementById("formulario").style.display="none";

    document.querySelectorAll("#formulario input")
    .forEach(i=>i.value="");
}

async function guardar(){

    const id=document.getElementById("id").value;

    const datos={

        empleado_id:
            document.getElementById("empleado_id").value,

        fecha_inicio:
            document.getElementById("fecha_inicio").value,

        fecha_fin:
            document.getElementById("fecha_fin").value,

        tipo:
            document.getElementById("tipo").value,

        diagnostico_general:
            document.getElementById("diagnostico_general").value,

        entidad_medica:
            document.getElementById("entidad_medica").value,

        observaciones:
            document.getElementById("observaciones").value,

        estado:"registrada"
    };

    if(id===""){

        await fetch(API+"/incapacidades",{

            method:"POST",

            headers:{
                "Content-Type":"application/json",
                Authorization:token
            },

            body:JSON.stringify(datos)
        });

    }else{

        await fetch(API+"/incapacidades/"+id,{

            method:"PUT",

            headers:{
                "Content-Type":"application/json",
                Authorization:token
            },

            body:JSON.stringify(datos)
        });
    }

    ocultarFormulario();

    listar();
}

function editar(i){

    mostrarFormulario();

    document.getElementById("id").value=i.id;

    document.getElementById("empleado_id").value=i.empleado_id;

    document.getElementById("fecha_inicio").value=i.fecha_inicio;

    document.getElementById("fecha_fin").value=i.fecha_fin;

    document.getElementById("tipo").value=i.tipo;

    document.getElementById("diagnostico_general").value=
        i.diagnostico_general;

    document.getElementById("entidad_medica").value=
        i.entidad_medica;

    document.getElementById("observaciones").value=
        i.observaciones;
}

async function finalizar(id){

    if(!confirm("¿Finalizar incapacidad?")){
        return;
    }

    await fetch(API+"/incapacidades/"+id,{

        method:"DELETE",

        headers:{
            Authorization:token
        }
    });

    listar();
}

</script>

</body>
</html>
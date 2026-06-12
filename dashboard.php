<?php
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>

        .dashboard{
            max-width: 1000px;
            margin: 50px auto;
        }

        .cards{
            display: grid;

            grid-template-columns: repeat(auto-fit,minmax(200px,1fr));

            gap: 20px;
        }

        .card{
            background: white;

            padding: 30px;

            border-radius: 10px;

            text-align: center;

            box-shadow: 0 0 10px rgba(0,0,0,.1);
        }

        .card a{
            text-decoration: none;

            color: black;

            font-weight: bold;
        }

    </style>

</head>
<body>

<div class="dashboard">

    <h1 id="bienvenida"></h1>

    <br>

    <div class="cards">

        <div class="card">
            <a href="empleados.php">
                Gestión de Empleados
            </a>
        </div>

        <div class="card">
            <a href="incapacidades.php">
                Gestión de Incapacidades
            </a>
        </div>

        <div class="card">
            <a href="seguimientos.php">
                Gestión de Seguimientos
            </a>
        </div>

        <div class="card">
            <a href="logout.php">
                Cerrar Sesión
            </a>
        </div>

    </div>

</div>

<script>

    const token = localStorage.getItem("token");

    if(!token){
        window.location.href = "index.php";
    }

    document.getElementById("bienvenida").innerText =
        "Bienvenido, " + localStorage.getItem("nombre");

</script>

</body>
</html>
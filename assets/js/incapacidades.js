const API = "http://localhost:8002";

const token = localStorage.getItem("token");

if (!token) {
    window.location = "index.php";
}

listar();

async function listar() {

    const response = await fetch(API + "/incapacidades", {
        headers: {
            Authorization: token
        }
    });

    const datos = await response.json();

    let html = "";

    datos.forEach(i => {

        html += `
        <tr>
            <td>${i.id}</td>
            <td>${i.empleado_id}</td>
            <td>${i.fecha_inicio}</td>
            <td>${i.fecha_fin}</td>
            <td>${i.tipo || ""}</td>
            <td>${i.diagnostico_general}</td>
            <td>${i.entidad_medica}</td>
            <td>${i.estado || ""}</td>

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

    document.getElementById("tabla").innerHTML = html;
}

function mostrarFormulario() {
    document.getElementById("formulario").style.display = "block";
}

function ocultarFormulario() {

    document.getElementById("formulario").style.display = "none";

    document.querySelectorAll("#formulario input")
        .forEach(i => i.value = "");
}

async function guardar() {

    const id = document.getElementById("id").value;

    const datos = {
        empleado_id: document.getElementById("empleado_id").value,
        fecha_inicio: document.getElementById("fecha_inicio").value,
        fecha_fin: document.getElementById("fecha_fin").value,
        tipo: document.getElementById("tipo").value,
        diagnostico_general: document.getElementById("diagnostico_general").value,
        entidad_medica: document.getElementById("entidad_medica").value,
        observaciones: document.getElementById("observaciones").value,
        estado: "activa"
    };

    if (id === "") {

        await fetch(API + "/incapacidades", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Authorization: token
            },
            body: JSON.stringify(datos)
        });

    } else {

        await fetch(API + "/incapacidades/" + id, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                Authorization: token
            },
            body: JSON.stringify(datos)
        });
    }

    ocultarFormulario();
    listar();
}

function editar(i) {

    mostrarFormulario();

    document.getElementById("id").value = i.id;
    document.getElementById("empleado_id").value = i.empleado_id;
    document.getElementById("fecha_inicio").value = i.fecha_inicio;
    document.getElementById("fecha_fin").value = i.fecha_fin;
    document.getElementById("tipo").value = i.tipo || "";
    document.getElementById("diagnostico_general").value = i.diagnostico_general;
    document.getElementById("entidad_medica").value = i.entidad_medica;
    document.getElementById("observaciones").value = i.observaciones;
}

async function finalizar(id) {

    if (!confirm("¿Finalizar incapacidad?")) {
        return;
    }

    await fetch(API + "/incapacidades/" + id, {
        method: "DELETE",
        headers: {
            Authorization: token
        }
    });

    listar();
}
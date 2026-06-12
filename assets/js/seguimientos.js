const API = "http://localhost:8003";

const token = localStorage.getItem("token");

if (!token) {
    window.location = "index.php";
}

listar();

async function listar() {

    const response = await fetch(API + "/seguimientos", {
        headers: {
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
            <td>${s.fecha_seguimiento}</td>
            <td>${s.observaciones}</td>
            <td>${s.recomendaciones}</td>
            <td>${s.proxima_revision}</td>
            <td>${s.estado || ""}</td>

            <td>
                <button class="btn btn-warning"
                    onclick='editar(${JSON.stringify(s)})'>
                    Editar
                </button>

                <button class="btn btn-danger"
                    onclick='eliminar(${s.id})'>
                    Eliminar
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
        incapacidad_id: document.getElementById("incapacidad_id").value,
        fecha_seguimiento: document.getElementById("fecha_seguimiento").value,
        observaciones: document.getElementById("observaciones").value,
        recomendaciones: document.getElementById("recomendaciones").value,
        proxima_revision: document.getElementById("proxima_revision").value,
        estado: document.getElementById("estado").value
    };

    if (id === "") {

        await fetch(API + "/seguimientos", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Authorization: "Bearer " + token
            },
            body: JSON.stringify(datos)
        });

    } else {

        await fetch(API + "/seguimientos/" + id, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                Authorization: "Bearer " + token
            },
            body: JSON.stringify(datos)
        });
    }

    ocultarFormulario();
    listar();
}

function editar(s) {

    mostrarFormulario();

    document.getElementById("id").value = s.id;
    document.getElementById("incapacidad_id").value = s.incapacidad_id;
    document.getElementById("fecha_seguimiento").value = s.fecha_seguimiento;
    document.getElementById("observaciones").value = s.observaciones;
    document.getElementById("recomendaciones").value = s.recomendaciones;
    document.getElementById("proxima_revision").value = s.proxima_revision;
    document.getElementById("estado").value = s.estado || "";
}

async function eliminar(id) {

    if (!confirm("¿Desea eliminar este seguimiento?")) {
        return;
    }

    await fetch(API + "/seguimientos/" + id, {
        method: "DELETE",
        headers: {
            Authorization: "Bearer " + token
        }
    });

    listar();
}
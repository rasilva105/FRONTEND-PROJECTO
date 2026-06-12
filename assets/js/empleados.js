const API = "http://localhost:8001";

const token = localStorage.getItem("token");

if (!token) {
    window.location.href = "index.php";
}

listarEmpleados();

async function listarEmpleados() {

    const respuesta = await fetch(API + "/empleados", {
        headers: {
            Authorization: token
        }
    });

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
                <button class="btn btn-warning"
                    onclick='editarEmpleado(${JSON.stringify(emp)})'>
                    Editar
                </button>

                <button class="btn btn-danger"
                    onclick='eliminarEmpleado(${emp.id})'>
                    Desactivar
                </button>
            </td>
        </tr>
        `;
    });

    document.getElementById("tablaEmpleados").innerHTML = html;
}

function mostrarFormulario() {
    document.getElementById("formulario").style.display = "block";
}

function ocultarFormulario() {

    document.getElementById("formulario").style.display = "none";

    document.getElementById("empleadoId").value = "";

    document.querySelectorAll("#formulario input")
        .forEach(input => input.value = "");
}

async function guardarEmpleado() {

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

    if (id === "") {

        await fetch(API + "/empleados", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Authorization: token
            },
            body: JSON.stringify(datos)
        });

    } else {

        await fetch(API + "/empleados/" + id, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                Authorization: token
            },
            body: JSON.stringify(datos)
        });
    }

    ocultarFormulario();
    listarEmpleados();
}

function editarEmpleado(emp) {

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

async function eliminarEmpleado(id) {

    if (!confirm("¿Desea desactivar este empleado?")) {
        return;
    }

    await fetch(API + "/empleados/" + id, {
        method: "DELETE",
        headers: {
            Authorization: token
        }
    });

    listarEmpleados();
}
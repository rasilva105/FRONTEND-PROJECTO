const API = "http://localhost:8001";
const token = localStorage.getItem("token");

async function listarEmpleados() {

    const response = await fetch(API + "/empleados", {
        headers: {
            Authorization: "Bearer " + token
        }
    });

    return await response.json();
}

async function guardarEmpleado(datos) {

    return await fetch(API + "/empleados", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            Authorization: "Bearer " + token
        },
        body: JSON.stringify(datos)
    });
}

async function actualizarEmpleado(id, datos) {

    return await fetch(API + "/empleados/" + id, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            Authorization: "Bearer " + token
        },
        body: JSON.stringify(datos)
    });
}

async function eliminarEmpleado(id) {

    return await fetch(API + "/empleados/" + id, {
        method: "DELETE",
        headers: {
            Authorization: "Bearer " + token
        }
    });
}
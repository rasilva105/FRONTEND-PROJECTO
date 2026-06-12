const API = "http://localhost:8003";
const token = localStorage.getItem("token");

async function listarSeguimientos() {

    const response = await fetch(API + "/seguimientos", {
        headers: {
            Authorization: "Bearer " + token
        }
    });

    return await response.json();
}

async function guardarSeguimiento(datos) {

    return await fetch(API + "/seguimientos", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            Authorization: "Bearer " + token
        },
        body: JSON.stringify(datos)
    });
}

async function actualizarSeguimiento(id, datos) {

    return await fetch(API + "/seguimientos/" + id, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            Authorization: "Bearer " + token
        },
        body: JSON.stringify(datos)
    });
}

async function eliminarSeguimiento(id) {

    return await fetch(API + "/seguimientos/" + id, {
        method: "DELETE",
        headers: {
            Authorization: "Bearer " + token
        }
    });
}
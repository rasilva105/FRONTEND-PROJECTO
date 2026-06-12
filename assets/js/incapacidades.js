const API = "http://localhost:8002";
const token = localStorage.getItem("token");

async function listarIncapacidades() {

    const response = await fetch(API + "/incapacidades", {
        headers: {
            Authorization: "Bearer " + token
        }
    });

    return await response.json();
}

async function guardarIncapacidad(datos) {

    return await fetch(API + "/incapacidades", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            Authorization: "Bearer " + token
        },
        body: JSON.stringify(datos)
    });
}

async function actualizarIncapacidad(id, datos) {

    return await fetch(API + "/incapacidades/" + id, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            Authorization: "Bearer " + token
        },
        body: JSON.stringify(datos)
    });
}

async function finalizarIncapacidad(id) {

    return await fetch(API + "/incapacidades/" + id, {
        method: "DELETE",
        headers: {
            Authorization: "Bearer " + token
        }
    });
}
let vector = [];
let tamano = 0;

function definirTamano() {
    let input = document.getElementById("tamano").value.trim();
    let valor = Number(input);

    if (input === "" || !Number.isInteger(valor) || valor < 1 || valor > 50) {
        alert("El tamaño debe ser un número entero entre 1 y 50");
        return;
    }

    if (vector.length > 0 && !confirm("¿Deseas cambiar el tamaño y reiniciar el vector actual?")) {
        return;
    }

    tamano = valor;
    vector = [];
    document.getElementById("resultado").innerText = "Esperando que selecciones una opción...";
    actualizarEstado();
}

function agregarNumero() {
    if (tamano === 0) {
        alert("Define primero el tamaño");
        return;
    }

    if (vector.length >= tamano) {
        alert("El vector ya está lleno");
        return;
    }

    let campoNumero = document.getElementById("numero");
    let valorTexto = campoNumero.value.trim();

    if (valorTexto === "") {
        alert("Ingresa un número");
        return;
    }

    let num = Number(valorTexto);

    if (!Number.isInteger(num)) {
        alert("Solo se permiten números enteros");
        return;
    }

    if (num < 0) {
        alert("No se permiten números negativos");
        return;
    }

    if (num > 1000000) {
        alert("El número no puede ser mayor a 1,000,000");
        return;
    }

    vector.push(num);
    campoNumero.value = "";
    actualizarEstado();
}

function reiniciar() {
    vector = [];
    tamano = 0;
    document.getElementById("tamano").value = "";
    document.getElementById("numero").value = "";
    document.getElementById("resultado").innerText = "Esperando que selecciones una opción...";
    actualizarEstado();
}

function actualizarEstado() {
    document.getElementById("mostrarVector").innerText = "Vector: [" + vector.join(", ") + "]";
    
    if (tamano > 0) {
        document.getElementById("mensaje").innerText = vector.length + " de " + tamano;
    } else {
        document.getElementById("mensaje").innerText = "";
    }

    let estaCompleto = tamano > 0 && vector.length === tamano;
    let botonesOpcion = document.querySelectorAll(".btn-opcion");
    botonesOpcion.forEach(function(btn) {
        btn.disabled = !estaCompleto;
    });

    let btnAgregar = document.getElementById("btnAgregar");
    if (btnAgregar) {
        btnAgregar.disabled = tamano === 0 || vector.length >= tamano;
    }
}

async function procesarOpcion(opcion) {
    if (tamano === 0 || vector.length !== tamano) {
        alert("Debes llenar el vector por completo antes de seleccionar una opción");
        return;
    }

    const resultadoDiv = document.getElementById("resultado");
    resultadoDiv.innerText = "Calculando en el servidor PHP...";

    try {
        const respuesta = await fetch("api.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                opcion: opcion,
                vector: vector
            })
        });

        const data = await respuesta.json();
        if (data.error) {
            resultadoDiv.innerText = "Error: " + data.error;
        } else {
            resultadoDiv.innerHTML = data.resultado;
        }
    } catch (error) {
        console.error(error);
        resultadoDiv.innerText = "Error al conectar con PHP (¿Iniciaste el servidor con 'php -S localhost:8000'?)";
    }
}

function opcion1() { procesarOpcion(1); }
function opcion2() { procesarOpcion(2); }
function opcion3() { procesarOpcion(3); }
function opcion4() { procesarOpcion(4); }

actualizarEstado();


actualizarEstado();
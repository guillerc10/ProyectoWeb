/* 
   Programa de operaciones con vectores:
   1. Contar valores primos en posiciones Fibonacci múltiplos de 3.
   2. Contar valores primos en posiciones primas múltiplos de 3, 4, 5 o 7.
   3. Ordenar con burbuja de mayor a menor solo primos que sean de Fibonacci.
   4. Promedio del vector, mayores al promedio y menor con su posición.
*/

let vector = [];
let tamano = 0;

function definirTamano() {
    tamano = Number(document.getElementById("tamano").value);
    vector = [];
    document.getElementById("mostrarVector").innerText = "Vector: []";
    document.getElementById("mensaje").innerText = "Tamaño: " + tamano;
}

function agregarNumero() {
    if (tamano === 0) {
        alert("Define primero el tamaño");
        return;
    }
    if (vector.length < tamano) {
        let num = Number(document.getElementById("numero").value);
        vector.push(num);
        document.getElementById("numero").value = "";
        document.getElementById("mostrarVector").innerText = "Vector: [" + vector.join(", ") + "]";
        document.getElementById("mensaje").innerText = vector.length + " de " + tamano;
    } else {
        alert("El vector ya está lleno");
    }
}

function reiniciar() {
    vector = [];
    tamano = 0;
    document.getElementById("tamano").value = "";
    document.getElementById("numero").value = "";
    document.getElementById("mostrarVector").innerText = "Vector: []";
    document.getElementById("mensaje").innerText = "";
    document.getElementById("resultado").innerText = "";
}

// Envía los datos al backend en PHP para su procesamiento
async function procesarOpcion(opcion) {
    if (vector.length === 0) {
        alert("Primero debes ingresar elementos al vector");
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
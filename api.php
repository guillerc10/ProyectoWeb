<?php
header('Content-Type: application/json; charset=utf-8');

// Obtener datos JSON enviados desde fetch en JavaScript
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['opcion']) || !isset($data['vector'])) {
    echo json_encode(['error' => 'Datos inválidos o vector no recibido']);
    exit;
}

$opcion = (int)$data['opcion'];
$vector = $data['vector'];

// Funciones auxiliares
function esPrimo($n) {
    if ($n < 2) return false;
    for ($i = 2; $i * $i <= $n; $i++) {
        if ($n % $i === 0) return false;
    }
    return true;
}

function esFibonacci($n) {
    if ($n < 0) return false;
    $a = 0;
    $b = 1;
    while ($a < $n) {
        $temp = $a + $b;
        $a = $b;
        $b = $temp;
    }
    return $a === $n;
}

$respuesta = '';

switch ($opcion) {
    case 1:
        // Contar primos en posiciones Fibonacci múltiplos de 3
        $cont = 0;
        for ($i = 0; $i < count($vector); $i++) {
            if (esPrimo($vector[$i]) && esFibonacci($i) && ($i % 3 === 0)) {
                $cont++;
            }
        }
        $respuesta = "Cantidad de primos: " . $cont;
        break;

    case 2:
        // Contar primos en posiciones primas y múltiplos de 3, 4, 5 o 7
        $cont = 0;
        for ($i = 0; $i < count($vector); $i++) {
            $multiplo = ($i % 3 === 0 || $i % 4 === 0 || $i % 5 === 0 || $i % 7 === 0);
            if (esPrimo($vector[$i]) && esPrimo($i) && $multiplo) {
                $cont++;
            }
        }
        $respuesta = "Cantidad de primos: " . $cont;
        break;

    case 3:
        // Ordenar con burbuja de mayor a menor solo primos Fibonacci
        $lista = [];
        for ($i = 0; $i < count($vector); $i++) {
            if (esPrimo($vector[$i]) && esFibonacci($vector[$i])) {
                $lista[] = $vector[$i];
            }
        }

        // Método de la burbuja descendente
        $n = count($lista);
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n - 1; $j++) {
                if ($lista[$j] < $lista[$j + 1]) {
                    $aux = $lista[$j];
                    $lista[$j] = $lista[$j + 1];
                    $lista[$j + 1] = $aux;
                }
            }
        }
        $respuesta = "Primos Fibonacci ordenados: [" . implode(", ", $lista) . "]";
        break;

    case 4:
        // Promedio, mayores al promedio y menor con posición
        if (count($vector) === 0) {
            $respuesta = "El vector está vacío";
            break;
        }

        $suma = array_sum($vector);
        $promedio = $suma / count($vector);

        $mayores = [];
        $menor = $vector[0];
        $posMenor = 0;

        for ($i = 0; $i < count($vector); $i++) {
            if ($vector[$i] > $promedio) {
                $mayores[] = $vector[$i];
            }
            if ($vector[$i] < $menor) {
                $menor = $vector[$i];
                $posMenor = $i;
            }
        }

        $respuesta = "Promedio: " . number_format($promedio, 2) . "<br>" .
                     "Mayores al promedio: [" . implode(", ", $mayores) . "]<br>" .
                     "Menor: " . $menor . " (posición " . $posMenor . ")";
        break;

    default:
        $respuesta = "Opción no reconocida";
        break;
}

echo json_encode(['resultado' => $respuesta]);

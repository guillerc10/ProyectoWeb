<?php
header('Content-Type: application/json; charset=utf-8');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['opcion']) || !isset($data['vector']) || !is_array($data['vector'])) {
    echo json_encode(['error' => 'Datos inválidos o vector no recibido']);
    exit;
}

$opcion = (int)$data['opcion'];
if ($opcion < 1 || $opcion > 4) {
    echo json_encode(['error' => 'Opción no válida']);
    exit;
}

$vector = $data['vector'];

if (count($vector) === 0 || count($vector) > 50) {
    echo json_encode(['error' => 'El vector debe contener entre 1 y 50 elementos']);
    exit;
}

foreach ($vector as $val) {
    if (!is_int($val)) {
        echo json_encode(['error' => 'Todos los elementos deben ser números enteros']);
        exit;
    }
    if ($val < 0) {
        echo json_encode(['error' => 'No se permiten números negativos']);
        exit;
    }
    if ($val > 1000000) {
        echo json_encode(['error' => 'Los números no pueden superar 1,000,000']);
        exit;
    }
}

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
        $cont = 0;
        for ($i = 0; $i < count($vector); $i++) {
            if (esPrimo($vector[$i]) && esFibonacci($i) && ($i % 3 === 0)) {
                $cont++;
            }
        }
        $respuesta = "Cantidad de primos: " . $cont;
        break;

    case 2:
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
        $lista = [];
        for ($i = 0; $i < count($vector); $i++) {
            if (esPrimo($vector[$i]) && esFibonacci($vector[$i])) {
                $lista[] = $vector[$i];
            }
        }

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
                $mayores[] = $vector[$i] . " (posición " . $i . ")";
            }
            if ($vector[$i] < $menor) {
                $menor = $vector[$i];
                $posMenor = $i;
            }
        }

        $textoMayores = count($mayores) > 0 ? "[" . implode(", ", $mayores) . "]" : "Ninguno";

        $respuesta = "Promedio: " . number_format($promedio, 2) . "<br>" .
                     "Mayores al promedio: " . $textoMayores . "<br>" .
                     "Menor: " . $menor . " (posición " . $posMenor . ")";
        break;

    default:
        $respuesta = "Opción no reconocida";
        break;
}

echo json_encode(['resultado' => $respuesta]);

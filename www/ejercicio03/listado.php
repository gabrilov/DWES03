<?php
require_once "../conexion.php";

$log = obtenerLog();


?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * {
      color-scheme: light dark;
    }

    h1,
    h4 {
      text-align: center;
    }

    table {
      width: 50%;
      border-collapse: collapse;
      margin: 20px auto;
      font-family: Arial, sans-serif;
    }

    th,
    td {
      border: 1px solid black;
      padding: 8px;
      text-align: center;
    }
  </style>
  <title>Listado</title>
</head>

<body>
  <h1>Listado de modificaciones</h1>

  <?php if ($log): ?>
    <table>
      <tr>
        <th>Nº</th>
        <th>Producto</th>
        <th>Fecha modificación</th>
      </tr>
      <?php foreach ($log as $registro): ?>
        <tr>
          <td><?= $registro['numero'] ?></td>
          <td><?= $registro['id_producto'] ?></td>
          <td><?= (new DateTime($registro['f_cambio']))->format('d-m-Y') ?></td>

        </tr>
      <?php endforeach ?>
    </table>
  <?php endif ?>
  <h4><a href="/ejercicio01/tienda.php">Volver al inicio</a></h4>
</body>

</html>
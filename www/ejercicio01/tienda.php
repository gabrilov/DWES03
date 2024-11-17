<?php
// Cargo el archivo de conexión
require_once '../conexion.php';

$productos = null;
// Obtener las tiendas
$tiendas = $conexion->query("SELECT * FROM tiendas")->fetchAll(PDO::FETCH_ASSOC);

// Obtener los productos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tienda"]))
  $productos = obtenerProductosDe($_POST['tienda']);

// Mantiene la opción seleccionada en la consulta cuando se recarga la página con los resultados
function esTiendaSeleccionada($idtienda)
{
  return (isset($_POST['tienda']) && $_POST['tienda'] == $idtienda) ? 'selected' : '';
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tienda</title>
  <style>
    * {
      color-scheme: light dark;
    }
  </style>
</head>

<body>
  <h1>Tarea: Listado de productos de una tienda</h1>
  <form action="" method="post">
    <label for="tienda">Tienda:</label>
    <select name="tienda" id="tienda">
      <!-- Genero dinámicamente las opciones del desplegable -->
      <?php foreach ($tiendas as $row): ?>
        <option value="<?= htmlspecialchars($row["id"]) ?>" <?= esTiendaSeleccionada($row["id"]) ?>>
          <?= htmlspecialchars($row["nombre"]) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <input type="hidden" name="consulta">
    <button type="submit">Mostrar productos</button>
  </form>

  <?php if ($productos && count($productos) > 0): ?>
    <h2>Productos de la tienda:</h2>
    <?php foreach ($productos as $row): ?>
      <form action="../ejercicio02/cambios.php" method="post">
        <p>
          Producto <?= htmlspecialchars($row["nombre_corto"]) ?>:
          <?= htmlspecialchars($row["pvp"]) ?> €.
          <?= htmlspecialchars($row["unidades"]) ?> ud.
          ***<?= htmlspecialchars($row["familia"]) ?>***
          <input type="hidden" name="idProducto" value="<?= htmlspecialchars($row["id"]) ?>">
          <button id="btn-editar" type="submit">Editar</button>
        </p>
      </form>
    <?php endforeach; ?>
  <?php elseif (isset($_POST['consulta'])): ?>
    <h4>Esta tienda no dispone de productos</h4>
  <?php else: ?>
    <h4>Selecciona una tienda y pulsa el botón</h4>
  <?php endif; ?>
</body>

</html>
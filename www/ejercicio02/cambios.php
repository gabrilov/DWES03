<?php
require_once '../conexion.php';
$producto = null;
$consultaProducto = "SELECT * FROM productos WHERE id=:id";
$estaActualizado = false;
$mensajeResultado = "";

function salir()
{
  header("Location: /ejercicio01/tienda.php");
}

// Si recibimos un valor con clave "idProducto", se obtiene la información de ese producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idProducto"])) {
  $producto = obtenerProducto($_POST['idProducto']);
}

// Si la página recibe todos los valores del formulario analizamos el tipo
// de operación que debemos realizar sobre esos valores
if (
  $_SERVER["REQUEST_METHOD"] == "POST"
  && isset($_POST["boton"])
  && isset($_POST["id"])
  && isset($_POST["nombre"])
  && isset($_POST["nombre-corto"])
  && isset($_POST["descripcion"])
  && isset($_POST["pvp"])
) {
  switch ($_POST['boton']) {
      // Si se pulsa actualizar se inicia la transacción: actuazación y registro
      // del cambio
    case 'actualizar':
      $conexion->beginTransaction();
      $estaActualizado = actualizarProducto(
        $_POST['id'],
        $_POST['nombre'],
        $_POST['nombre-corto'],
        $_POST['descripcion'],
        $_POST['pvp']
      );
      registrarActualizacion($_POST['id'], date('Y-m-d H:i:s'));
      $conexion->commit();
      // Se intenta mostrar un mensaje
      if ($estaActualizado) $mensajeResultado = "CORRECTO Se han actualizado los datos de " . $_POST['nombre-corto'];
      $producto = obtenerProducto($_POST['id']);
      // Se redirije a otra página. Esto hace que el mensaje no se muestra, pero con PHP, al parecer
      // es imposible mostrar mensaje y redirigir, habría que emplear javascript en el lado del cliente
      header("Location: /ejercicio03/listado.php");
      break;
      //Si se cancela, se intenta mostrar mensaje y se redirige al inicio
    case 'cancelar':
      $mensajeResultado = "CANCELANDO...";
      $producto = obtenerProducto($_POST['id']);
      header("Location: /ejercicio01/tienda.php");
      break;
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cambios</title>
  <style>
    * {
      color-scheme: light dark;
      box-sizing: border-box;
    }

    body {
      max-width: 80%;
      margin: auto;
    }

    textarea {
      resize: none;
    }

    input[type="text"],
    textarea {
      width: 100%;
      padding: 8px;
      margin: 5px 0;
      box-sizing: border-box;
    }

    #pvp {
      width: 30%;
    }

    .botones {
      display: flex;
      justify-content: center;
      gap: 30px;
    }

    button {
      font-size: 1.2rem;
      padding: 10px;
    }
  </style>
</head>

<body>
  <form action="" method="post">
    <h1>Producto:</h1>

    <input type="hidden" name="id" value="<?= $producto['id'] ?>">

    <label for="nombre-corto">Nombre corto: </label>
    <input type="text" name="nombre-corto" id="nombre-corto" size="50" value="<?= $producto['nombre_corto'] ?>" />
    <br /><br />


    <label for="nombre">Nombre:</label><br /><br />
    <input type="text" name="nombre" id="nombre" value="<?= $producto['nombre'] ?>" />
    <br /><br />

    <label>Descripción:</label><br /><br />
    <textarea name="descripcion" id="descripcion" rows="10"><?= $producto['descripcion'] ?></textarea><br /><br />

    <label for="pvp">PVP:</label>
    <input type="text" name="pvp" id="pvp" value="<?= $producto['pvp'] ?>"><br /><br />

    <div class="botones">
      <button type="submit" name="boton" value="actualizar">Actualizar</button>
      <button type="submit" name="boton" value="cancelar">Cancelar</button>
    </div>
  </form>
  <h4><?= $mensajeResultado; ?></h4>
</body>

</html>
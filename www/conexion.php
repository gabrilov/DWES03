<?php
// Datos de conexión a la base de datos
$servername = "mariadb_dwes";
$username = "gestor";
$password = "secreto";
$dbname = "tarea3";
$charset = "utf8mb4";

// Configuración de la conexión PDO
$dsn = "mysql:host=$servername;dbname=$dbname;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
  // Crear la conexión PDO
  $conexion = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
  // Manejar errores de conexión
  die("Conexión fallida: " . $e->getMessage());
}


/**
 * obtenerLog
 * 
 * Realiza una consulta para todos los datos de la tabla "log"
 *
 * @return array
 */
function obtenerLog(): array
{
  global $conexion;
  $consultaLog = "SELECT * FROM log";
  try {
    $consultaPreparada = $conexion->prepare($consultaLog);
    $consultaPreparada->execute();
    return $consultaPreparada->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo "Error al obtener los productos de la tabla log: " . $e->getMessage();
  }
}



/**
 * obtenerProductosDe
 *
 * @param  mixed $tienda de la que se obtienen los productos y su stock
 * @return array Un array con el resultado de la consulta
 */
function obtenerProductosDe($tienda): array
{
  global $conexion;
  // Consulta para obtener los productos
  $consultaProductos = "SELECT p.id, p.nombre_corto, p.familia, p.pvp, s.unidades 
                      FROM productos p 
                      INNER JOIN stocks s ON p.id = s.producto 
                      WHERE s.tienda = :tienda";
  try {
    // Uso PDO para realizar la consulta, con el objeto $conexion
    $consultaPreparada = $conexion->prepare($consultaProductos);
    $consultaPreparada->execute([':tienda' => $tienda]);
    // Obtengo el resultado en forma de array asociativo
    return $consultaPreparada->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo "Error al obtener los productos de la tienda " . $tienda;
  }
}


/**
 * obtenerProducto
 * 
 * Obtiene los datos de un producto a partir del campo "id"
 *
 * @param  mixed $idProducto
 * @return array
 */
function obtenerProducto($idProducto): array
{
  global $conexion;
  $consultaProducto = "SELECT * FROM productos WHERE id=:id";
  try {
    // Preparo la consulta y la ejecuto
    $consultaPreparada = $conexion->prepare($consultaProducto);
    $consultaPreparada->execute([':id' => $idProducto]);
    // Obtengo el registro en forma de array asociativo, y me quedo con el primer
    // (y espero que único) elemento
    return ($consultaPreparada->fetchAll(PDO::FETCH_ASSOC))[0];
  } catch (PDOException $e) {
    echo "No se encontró el producto con ID: " . htmlspecialchars($_POST['idProducto']) . "<br>";
  }
}

/**
 * actualizarProducto
 * 
 * Actualiza un registro de la tabla Productos
 *
 * @param  mixed $id
 * @param  mixed $nombre
 * @param  mixed $nombreCorto
 * @param  mixed $descripcion
 * @param  mixed $pvp
 * @return bool
 */
function actualizarProducto($id, $nombre, $nombreCorto, $descripcion, $pvp): bool
{
  global $conexion;
  $consulta = "UPDATE productos SET nombre=:nombre, nombre_corto=:nombre_corto, descripcion=:descripcion, pvp=:pvp WHERE id=:id";
  try {
    $consultaPreparada = $conexion->prepare($consulta);
    $consultaPreparada->execute([
      ':nombre' => $nombre,
      ':nombre_corto' => $nombreCorto,
      ':descripcion' => $descripcion,
      ':pvp' => $pvp,
      ':id' => $id
    ]);
    return $consultaPreparada->rowCount() > 0;
  } catch (PDOException $e) {
    echo "Error en la actualización del producto: " . $e->getMessage();
    return false;
  }
}

function registrarActualizacion($id, $fecha)
{
  global $conexion;
  $consulta = "INSERT INTO log (id_producto, f_cambio) VALUES (:id, :fecha)";
  try {
    $consultaPreparada = $conexion->prepare($consulta);
    $consultaPreparada->execute([
      ':id' => $id,
      ':fecha' => $fecha
    ]);
  } catch (PDOException $e) {
    echo "Error en la inserción en el log de cambios: " . $e->getMessage();
  }
}

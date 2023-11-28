<?php 
/** Retorna un objeto de tipo Mysqli con la conexión a la base de datos
 * @return mysqli
 */
function conectarDB() : mysqli {
  // Los credenciales de tu base de datos aqui:
  $db = new mysqli("host", "user", "password", "database");
  if(!$db) {
    echo "Error. Conexion no permitida";
    exit;
  } else {
    return $db;
  }
}
?>
<?php 
  require "funciones.php";
  require "config/database.php";
  require __DIR__."/../vendor/autoload.php";

  // Conectarnos a la base de datos
  $db = conectarDB();

  // Crear referencia a la Base de Datos para las clases de ActiveRecord
  use Model\ActiveRecord;
  ActiveRecord::setDB($db);
?>

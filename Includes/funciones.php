<?php 
  /** Visualizar resultados formateados. La función muestra un resultado y detiene toda la ejecución del programa siguiente
   * @param mixed $var
   * @return void
  */
  function debuguear(mixed $var) : void {
    echo "<pre>";
    var_dump($var);
    echo "<pre>";
    exit;
  }

  /** Sanitiza la entrada de datos utilizando una función nativa de PHP para sanitizar el HTML y retorna los datos snitizados
   * @param string $html
   * @return string 
  */
  function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
  }

  /** Verificar si el usuario se encuentra autenticado
   * @return bool
   */
  function is_auth() : bool {
    session_start();
    return isset($_SESSION["auth"]) && !empty($_SESSION);
  }
  
  /** Validar que un usuario cuente con permisos de administrador
   * @return bool
   */
  function is_admin() : bool {
    session_start();
    return isset($_SESSION["admin"]) && !empty($_SESSION["admin"]);
  }
?>
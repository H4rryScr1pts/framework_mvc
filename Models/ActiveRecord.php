<?php 
namespace Model;

class ActiveRecord {
  // Database
  protected static $db;
  protected static $DBcolumns = []; 
  protected static $table = "";

  // Validation errors
  protected static $errors = [];

  /** Run a SQL query
   * @param string $query
   */
  public static function query(string $query) {
    $result = self::$db->query($query);
    return $result;
  }

  /** Asing database conection
   * @param $databse
   * @return void
   */
  public static function setDB($database) : void { 
    self::$db = $database;
  }

  /** Get all records of a table
   * @return array
  */
  public static function all() : array {
    $query = "SELECT * FROM " . static::$table;
    $result = self::consultarSQL($query);
    return $result;
  }

  /** Seacrh a record in the table by id
  * @return object
  * @param int $id
  */
  public static function find(int $id) {
    $query = "SELECT * FROM " . static::$table . " WHERE id = $id";
    $result = self::consultarSQL($query);
    return array_shift($result);
  }

  /** Get a specific number of records from a table.
   * @param int $number
   * @return array
   */
  public static function get(int $number) : array {
    $query = "SELECT * FROM " . static::$table . " LIMIT " . $number;
    $result = self::consultarSQL($query);
    return $result;
  }

  /** Save or update a record of the table
  * @return void
  */
  public function save() {
    if(!is_null($this->id)) {
      $this->update();
    } else {
      $this->create();
    }
  }

  /** Create a new record
  * @return void
  */
  public function create() : void { 
    // Sanitizacion de entrada de datos
    $data = $this->sanitizarDatos(); 

    // Consulta para insertar
    $query = "INSERT INTO " . static::$table . " (";
    $query .= join(', ', array_keys($data));
    $query .= " ) VALUES (' ";  
    $query .= join("', '", array_values($data));
    $query .= " '); ";

    $result = self::$db->query($query);

    if($result) {
      header("Location: /'your url?result=1'");
    }
  }

  /** Update a record
  * @return void
  */
  public function update() {
    // Saitizar los datos
    $atributes = $this->sanitizarDatos();
    $values = [];

    // Asignar los valores sanitizados a un arreglo para la consulta 
    foreach($atributes as $key => $value) {
      $values[] = "$key='$value'";
    }

    // Consulta para actualizar los datos de la propiedad
    $query = "UPDATE " . static::$table . " SET ";
    $query .= join(', ', $values );
    $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
    $query .= " LIMIT 1;";

    $result = self::$db->query($query);

    // Redireccion al usuario tras registrar propiedad
    if($result) {
      header("Location: '/your url?result=2'");
    }        
  }

  /** Delete a record
  * @return void
  */
  public function delete() {
    $query = "DELETE FROM " . static::$table . " WHERE id = " . self::$db->escape_string($this->id);
    $result = self::$db->query($query);

    // Redireccionamiento tras la ejecución de la consulta SQL
    if($result) {
      $this->borrarImagen();
      header("Location: ../admin?result=3");
    }
  }

  /** METODOS PARA IMAGENES **/

  /** Metodo para para asignar una imagen a la propiedad. Si se elimina una propiedad este metodo ejecuta un segundo metodo para eliminar la imagen ligada a la propiedad que se elimina. Ocurre lo mismo si se actualiza una propiedad con una imagen nueva. La imagen nueva remplaza a la anterior. Las imagenes son almacenadas en el servidor y la referencia se almacena en la base de datos.
   * @return void
   */
  public function setImage(string $imagen) : void {
    // Elimina la imagen previa
    if(!is_null($this->id)) {
      $this->borrarImagen();
    }

    // Asignar nombre de imagen al atributo de imagen
    if($imagen) {
      $this->imagen = $imagen;
    }
  }

  /** Elimina la imagen del la propiedad almacenada en el servidor
  * @return void
  */
  public function borrarImagen() {
    // Comprobar si existe archivo
    $archivo = file_exists("../public/imagenes/" . $this->imagen);
    if($archivo) {
      unlink("../public/imagenes/". $this->imagen);
    }
  }

    /** METODOS DE VALIDACIÓN **/

    /** Validate a form
    * @return array
    */
  public function validate() : array { 
    static::$errors = [];
    return static::$errors;
  }
     
  /** Get validation errors
  * @return array
  */
  public static function getErrores() : array {
    return static::$errors;
  }

  /** METODOS DE SANITIZACIÓN **/

  /** Sanitiza la entrada de datos a traves de un ciclo foreach
  * @return array
  */
  public function sanitizarDatos() : array { 
    // Mapeo de columnas y datos de la BD
    $atributos = $this->atributios();
    $sanitizado = [];

    // Sanitización de cada elemento del arreglo (Propiedades del objeto)
    foreach ($atributos as $key => $value) {
      $sanitizado[$key] = self::$db->escape_string($value); // Proceso de sanitización de atributos
    }
    return $sanitizado;
  }

  /** METODOS DE APOLLO **/

  /** Formateo de los datos de la tabla de la base de datos relacionada al objeto. Los datos son formateados en forma de arreglo asociativo.
  * @return array
  */
  public function atributios() : array { 
    $atributos = array();

    // Formateo de datos
    foreach(static::$columasDB as $columna) {
      if($columna === "id") continue; // Ignorar el Id en el arrglo de atributos
        $atributos[$columna] = $this->$columna;
      }
      return $atributos;
  }         

  /** Crea un arreglo de objetos a partir de los registros de la Base de Datos y lo retorna
  * @return array
  */
  public static function consultarSQL(string $query) : array {
    // Consultar la BD
    $resultado = self::$db->query($query);

    // Iterar resultados (Llena en arreglo con objetos)
    $array = [];
    while($registro = $resultado->fetch_assoc()) {
      $array[] = static::crearObjeto($registro); 
    }

    // Liberar memoria
    $resultado->free();

    // retornar resultados (arreglo de objetos)
    return $array;
  }

  /** Crea un objeto a partir de un arreglo asociativo y lo retorna
   *  @return object
   */
  protected static function crearObjeto($registro) : object {
    // Instancia nueva (Propiedad)
    $objeto = new static;
    // Asignar valores a las propiedades del objeto con base a los valores del arreglo asociativo
    foreach($registro as $key => $value) {
      if(property_exists($objeto, $key)) {
        $objeto->$key = $value;
      }
    }
    // Retorna el objeto con propiedades y valores
    return $objeto;
  }

  /** Sincroniza el objeto en memoria con los cambios realizados por el usuario y reescribe los datos de objeto por datos recibidos a traves de $_POST[]
   * @param array $datosNuevos
   * @return void
   */
  public function sincronizar($args = []) : void {
    foreach($args as $key => $value) { 
      if(property_exists($this, $key) && !is_null($value)) { // Revisar si existe una propiedad que coincida entre el objeto y el arreglo recibido por $_POST[]
        $this->$key = $value;
      }
    }
  }
}
?>
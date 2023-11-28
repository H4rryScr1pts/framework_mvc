<?php 
namespace MVC;
class Router {
  // Ruotes with methods $_POST[] and $_GET[]
  public $getRoutes = [];
  public $postRoutes = array();

  /** Method to define a function for a url with the $_GET[] method
  * @param string $url
  * @param array $function
  */
  public function get(string $url, array $fn) {
    $this->getRoutes[$url] = $fn;
  }

  /** Method to define a function for a url with the $_POST[] method
  * @param string $url
  * @param array $fn
  */
  public function post(string $url, array $fn) {
    $this->postRoutes[$url] = $fn;
  }

  /** Checks if a URL exists within the application. If the URL exists, execute the function associated with that route. If the route does not exist, it sends a 404 error message
   * @return void
   */
  public function checkRoutes() : void {
    session_start();
    $auth = $_SESSION["login"] ?? null;
    $protected_routes = [
      // Your privated routs here:
    ];

    $curentUrl = $_SERVER["PATH_INFO"] ?? "/";
    $method = $_SERVER["REQUEST_METHOD"];

    // check url's method
    if($method === "GET") {
      $fn = $this->getRoutes[$curentUrl] ?? null;
    } else {
      $fn = $this->postRoutes[$curentUrl] ?? null;
    }

    // Protect the routes
    if(in_array($curentUrl, $protected_routes) && !$auth ) {
      header("Location: ./");
    }
    
    // Check if the URL and the function associated with it exist
    if($fn) {
      call_user_func($fn, $this);
    } else {
      // Your configuration here:
      
    }
  }

  /** Call a view to display it through a controller. This function already includes a Main Layout. First, it stores the view that we placed in memory, then it assigns it to a variable called $content and clears the memory, and finally it includes the main page. (The impression of the view stored in the $content variable is already included on the main page). Likewise, the function creates a variable with visual content that we can show in the view
   * @param string $view
   * @param string $masterPage
   * @param array $data
   * @return void
   */
  public function render(string $masterPage = "MasterPage", string $view, $data = []) : void {
    foreach($data as $key => $value) {
      $$key = $value;
    }

    ob_start(); // Save the url in memory

    include __DIR__ . "/Views/$view.php"; // include view

    $content = ob_get_clean(); // clear memory

    include __DIR__ . "/Views/$masterPage.php"; // include master page
  }
}
?>
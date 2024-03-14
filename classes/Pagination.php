<?php 
namespace Classes;
class Pagination {
  public $current_page;
  public $records_per_page;
  public $total_records;

  public function __construct($current_page = 1, $records_per_page = 10, $total_records = 0) {
    $this->current_page = (int) $current_page;
    $this->records_per_page = (int) $records_per_page;
    $this->total_records = (int) $total_records;
  }
  // Metodos para paginación

  /** Calcular el numero de registros por página */
  public function offset() {
    return $this->records_per_page * ($this->current_page - 1);
  }   

  /** Calcular el total de páginas para la paginación */
  public function total_pages() {
    return ceil($this->total_records / $this->records_per_page);
  }


  public function former_page() {
    $former = $this->current_page - 1;
    return ($former > 0) ? $former : false;
  }


  public function next_page() {
    $next = $this->current_page + 1;
    return ($next <= $this->total_pages()) ? $next : false;
  }

  public function former_link() {
    $html = "";
    if($this->former_page()) {
      $html .= "<a class=\"paginacion__enlace paginacion__enlace--texto\" href=\"?page={$this->former_page()}\">&laquo; Anterior</a>";
    }

    return $html;
  }

  public function next_link() {
    $html = "";
    if($this->next_page()) {
      $html .= "<a class=\"paginacion__enlace paginacion__enlace--texto\" href=\"?page={$this->next_page()}\">Siguiente &raquo;</a>";
    }

    return $html;
  }

  public function page_numbers() {
    $html = "";
    for($i = 1; $i <= $this->total_pages(); $i++) {
      if($i === $this->current_page) {
        $html .= "<span class=\"paginacion__enlace paginacion__enlace--actual\">$i</span>";
      } else {
        $html .= "<a class=\"paginacion__enlace paginacion__enlace--numero\" href=\"?page=$i\">$i</a>";
      }
    }

    return $html;
  }

  public function page() {
    $html = "";
    if($this->total_records > 1) {
      $html .= '<div class="paginacion">';
      $html .= $this->former_link();
      $html .= $this->page_numbers();
      $html .= $this->next_link();
      $html .= '</div>';
    }

    return $html;
  }
}
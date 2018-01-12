<?php

namespace ElCaminas;
use \PDO;
use \ElCaminas\Producto;
class Carrito
{
    protected $connect;
    /** Sin parámetros. Sólo crea la variable de sesión
    */
    public function __construct()
    {
        global $connect;
        $this->connect = $connect;
        if (!isset($_SESSION['carrito'])){
            $_SESSION['carrito'] = array();
        }

    }
    public function addItem($id, $cantidad){
        $_SESSION['carrito'][$id] = $cantidad;
    }
    public function deleteItem($id){
      unset($_SESSION['carrito'][$id]);
    }
    public function empty(){
      unset($_SESSION['carrito']);
      self::__construct();
    }
    public function howMany(){
      return count($_SESSION['carrito']);
    }

    public function toHtml(){
      //NO USAR, de momento
      $str = <<<heredoc
      <table class="table">
        <thead> <tr> <th>#</th> <th>Producto</th> <th>Cantidad</th> <th>Precio</th> <th>Total</th></tr> </thead>
        <tbody>
heredoc;
      if ($this->howMany() > 0){
        $i = 0;
        $Total=0;
        foreach($_SESSION['carrito'] as $key => $cantidad){
          $producto = new Producto($key);
          $i++;
          $subtotal = $producto->getPrecioReal() * $cantidad;
          $subtotalTexto = number_format($subtotal , 2, ',', ' ') ;
          $str .=  "<tr><th scope='row'>$i</th><td><a href='" .  $producto->getUrl() . "'>" . $producto->getNombre() ."<a class='open-modal' title='Haga clic para ver el detalle del producto'
          href='" .  $producto->getUrl() . "'><span style='color:#000' class='fa fa-external-link'></span></a>" . "</a></td><td>$cantidad</td>";
          $str .= "<td>" .  $producto->getPrecioReal() ." €</td><td>$subtotalTexto €</td>";
          $str .= "<td><a class='btn btn-danger' onclick='return checkDelete();' href=' carro.php?action=delete&id=$key'>Borrar</a></td>";
          $str .="</tr>";
          $Total += $subtotal;
        }
      }
      $str .= <<<heredoc
        </tbody>
      </table>
heredoc;
$str .=  "<h3 class='pull-left'>Total: $Total €<h3>";
      return $str;
    }

  public function getTotal(){
    $Total=0;
    foreach($_SESSION['carrito'] as $key => $cantidad){
      $producto = new Producto($key);
      $subtotal = $producto->getPrecioReal() * $cantidad;
      $Total += $subtotal;
    }
    return $Total;
  }
}

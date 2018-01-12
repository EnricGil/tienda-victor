<?php
  session_start();
  include("./include/funciones.php");
  $connect = connect_db();

  $title = "Plantas el Caminàs -> ";
  include("./include/header.php");
  require './include/ElCaminas/Carrito.php';
  require './include/ElCaminas/Producto.php';
  require './include/ElCaminas/Productos.php';
  use ElCaminas\Carrito;
  $carrito = new Carrito();
?>

<div class="row carro">
  <h2 class='subtitle' style='margin:0'>Carrito de la compra</h2>
  <?php  echo $carrito->toHtml();?>
</div>

<div id="paypal-button-container"></div>

    <script>
        paypal.Button.render({

            env: 'sandbox', // sandbox | production

            // PayPal Client IDs - replace with your own
            // Create a PayPal app: https://developer.paypal.com/developer/applications/create
            client: {
                sandbox:    'AURtFahgo3cuV-8J35gOhzh0AhTk36fnkHRkuGs-ZBiDoRdzd4NGvRDFFvzkCqmoU3puoZ3FOyS2zkDX',
                production: '<insert production client id>'
            },

            // Show the buyer a 'Pay Now' button in the checkout flow
            commit: true,

            // payment() is called when the button is clicked
            payment: function(data, actions) {

                // Make a call to the REST api to create the payment
                return actions.payment.create({
                    payment: {
                        transactions: [
                            {
                                amount: { total: '<?php echo $carrito->getTotal(); ?>', currency: 'EUR' }
                            }
                        ]
                    }
                });
            },

            // onAuthorize() is called when the buyer approves the payment
            onAuthorize: function(data, actions) {

                // Make a call to the REST api to execute the payment
                return actions.payment.execute().then(function() {
                    window.alert('Gracias por su compra!');
                    document.location.href="gracias.php";
                });
            }

        }, '#paypal-button-container');

    </script>

<?php
  include("./include/footer.php");
?>

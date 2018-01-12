//El elemento DOM con el código HTML de la máscara de cargando
var mask = $("#mask");
//El elemento dom que contiene la información del producto
var infoProducto = $("#infoProducto");
function attachNavProductos(){
  //Aquí uso este selector css (podés usar el vuestro)
  $( ".thumbnail a:first-child" ).click(function(event) {
    event.preventDefault();
    //Busco el attributo href
    var href = $( this ).attr('href');
    //Cargo los datos
    loadNodeData(href);
  });
}
/**
  href : url a cargar del producto
*/
function loadNodeData(href){
  //Mostrar la máscara
  mask.show();
  //Añadir a la url el estado json
  hrefJson = href + "&state=json";
  //Crear el objeto ajax
  var jqxhr = $.get( hrefJson, function(data) {
    //Cuando devuelve los datos, hago un scroll animado para que la página se vea entera
    //De otra forma, la página se quedería con el scroll que tuviera en el momento de hacer la carga
    $('html, body').animate({scrollTop : 0},800);
    //Este timeout sólo lo hago porque de otra forma lo hace
    //tan rápido que no se nota el efecto. De hecho lo podéis quitar
    setTimeout(function(){
      //Parsear los datos json que me ha devuelto ajax
      jData = JSON.parse(data);
      //Cargar los datos
      setNodeData(jData);

      // El navegador asocia jData con este href, de tal forma que al hacer history back
      // le pasa estos datos al evento popstate
      history.pushState(jData, null, href);

      //Ocultar la máscara
       mask.hide();
     }, 500);
  })
  .fail(function() {
    alert( "error" );
    mask.hide();
  });
}
/**
* Esta función es una versión simplificada de loadNodeData. La hago otra vez para mayor claridad
*/
function loadNodeDataFromPopState(href){
  //Añadir a la url el estado json
  hrefJson = href + "&state=json";
  //Crear el objeto ajax
  var jqxhr = $.get( hrefJson, function(data) {
    //Cuando devuelve los datos, hago un scroll animado para que la página se vea entera
    //De otra forma, la página se quedería con el scroll que tuviera en el momento de hacer la carga
    $('html, body').animate({scrollTop : 0},800);
    //Este timeout sólo lo hago porque de otra forma lo hace
    //tan rápido que no se nota el efecto. De hecho lo podéis quitar

      //Parsear los datos json que me ha devuelto ajax
      jData = JSON.parse(data);
      //Cargar los datos
      setNodeData(jData);
  })
  .fail(function() {
    alert( "error" );
    mask.hide();
  });
}
/**
* Este método modifica los elementos dom del contenedor con la información de producto
*/
function setNodeData(data){

  $( infoProducto ).find( ".subtitle" ).text(data.nombre);
  $( infoProducto ).find( ".img-thumbnail" ).attr("src", data.HOME + "basededatos/img/600_"+data.foto);
  $( infoProducto ).find( ".caption p" ).text(data.descripcion);
  $( infoProducto ).find( "h4" ).html(data.precio);
  var hrefComprar = data.HOME +  "carro.php?action=add&id=" + data.id + "&cantidad=1&redirect=" + encodeURI(document.location.href);
  $( infoProducto ).find( "a" ).attr("href", hrefComprar);
}
attachNavProductos();
/**
Este evento va junto a pushState. Es una versión simplificada
*/
window.addEventListener('popstate', function(e) {
  $('html, body').animate({scrollTop : 0},800);
  //Cuando el usuario hace click en el botón de history, el navegador
  //pasa la información que previamente hemos añadido en pushState
  if (e.state !== null){
    //La información está ya almacenada previamente en pushState
    setNodeData(e.state);
  }else{
    //La primera carga de la página producto.php no la hacemos por ajax.
    //Es por eso que la tengo que recargar ahora.
    loadNodeDataFromPopState(document.location.href);
  }
});

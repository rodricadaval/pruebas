<ul id="etabs" class='nav nav-tabs'>
  <li><a data-toggle="tab" data-target="#tabs1" href="controlador/ProductosController.php" vista="agregar_monitor">Monitor</a></li>
  <li><a data-toggle="tab" data-target="#tabs2" href="controlador/ProductosController.php" vista="agregar_computadora">Computadoras</a></li>
  <li><a data-toggle="tab" data-target="#tabs3" href="controlador/ProductosController.php" vista="agregar_memoria">Memoria</a></li>
  <li><a data-toggle="tab" data-target="#tabs4" href="controlador/ProductosController.php" vista="agregar_disco">Disco</a></li>
  <li><a data-toggle="tab" data-target="#tabs5" href="controlador/ProductosController.php" vista="agregar_impresora">Impresora</a></li>
  <li><a data-toggle="tab" data-target="#tabs6" href="controlador/ProductosController.php" vista="agregar_router">Router</a></li>
  <li><a data-toggle="tab" data-target="#tabs7" href="controlador/ProductosController.php" vista="agregar_switch">Switch</a></li>
  <li><a data-toggle="tab" data-target="#tabs8" href="controlador/ProductosController.php" vista="agregar_tablet">Tablet</a></li>
  <li><a data-toggle="tab" data-target="#tabs9" href="controlador/ProductosController.php" vista="agregar_toner">Toner</a></li>

</ul>

<div class="tab-content">
  <div class="tab-pane" id="tabs1"></div>
  <div class="tab-pane" id="tabs2"></div>
  <div class="tab-pane" id="tabs3"></div>
  <div class="tab-pane" id="tabs4"></div>
  <div class="tab-pane" id="tabs5"></div>
  <div class="tab-pane" id="tabs6"></div>
  <div class="tab-pane" id="tabs7"></div>
  <div class="tab-pane" id="tabs8"></div>
  <div class="tab-pane" id="tabs9"></div>

</div>

<script>

  $(document).ready(function(){
    $("#etabs").tab();
    $("#etabs").bind("show" , function (e){
      var contentID  = $(e.target).attr("data-target");
      var contentURL = $(e.target).attr("href");
      if (typeof(contentURL != 'undefined')){
        $(contentID).load(contentURL , {action : $(e.target).attr("vista")} , function (){ $("#etabs").tab(); });
      } else {
        $(contentID).tab('show');
      }
    });

    $("#etabs a:first").tab('show');

  })
</script>
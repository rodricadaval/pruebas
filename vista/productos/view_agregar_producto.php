<ul id="etabs" class='nav nav-tabs'>
  <li><a data-toggle="tab" data-target="#tabs1" href="controlador/ProductosController.php" vista="agregar_monitor">Monitor</a></li>
  <li><a data-toggle="tab" data-target="#tabs2" href="controlador/ProductosController.php" vista="agregar_computadora">Computadoras</a></li>
  <li><a data-toggle="tab" data-target="#tabs3" href="controlador/ProductosController.php" vista="agregar_memoria">Memoria</a></li>
  <li><a data-toggle="tab" data-target="#tabs4" href="controlador/ProductosController.php" vista="agregar_disco">Disco</a></li>
  <li><a data-toggle="tab" data-target="#tabs5" href="controlador/ProductosController.php" vista="agregar_impresora">Impresora</a></li>
  
</ul>

<div class="tab-content">
  <div class="tab-pane" id="tabs1"><h2>Menu para agregar un Monitor</h2></div>
  <div class="tab-pane" id="tabs2"><h2>Menu para agregar una Computadora</h2></div>
  <div class="tab-pane" id="tabs3"><h2>Menu para agregar una Memoria</h2></div>
  <div class="tab-pane" id="tabs4"><h2>Menu para agregar un Disco</h2></div>
  <div class="tab-pane" id="tabs5"><h2>Menu para agregar una Impresora</h2></div>

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
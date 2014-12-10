<ul id="etabs" class='nav nav-tabs'>
  <li><a data-toggle="tab" data-target="#tabs1" href="controlador/ProductosController.php" vista="agregar_monitor">Monitor</a></li>
  <li><a data-toggle="tab" data-target="#tabs2" href="controlador/ProductosController.php" vista="agregar_computadora">Computadoras</a></li>
  <li><a data-toggle="tab" data-target="#tabs3" href="controlador/ProductosController.php" vista="agregar_memoria">Memoria</a></li>
  <li><a data-toggle="tab" data-target="#tabs4" href="controlador/ProductosController.php" vista="agregar_disco">Disco</a></li>
</ul>

<div class="tab-content">
  <div class="tab-pane" id="tabs1"><h2>Menu para agregar un monitor</h2></div>
  <div class="tab-pane" id="tabs2"><h2>Menu para agregar un memoria</h2></div>
  <div class="tab-pane" id="tabs3"><h2>Menu para agregar un dico</h2></div>
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
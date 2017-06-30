<ul id="etabs" class='nav nav-tabs'>
  <li><a data-toggle="tab" data-target="#tabs1_stock" href="controlador/StockController.php" vista="ver_monitores">Monitores</a></li>
  <li><a data-toggle="tab" data-target="#tabs2_stock" href="controlador/StockController.php" vista="ver_computadoras">Computadoras</a></li>
  <li><a data-toggle="tab" data-target="#tabs3_stock" href="controlador/StockController.php" vista="ver_memorias">Memorias</a></li>
  <li><a data-toggle="tab" data-target="#tabs4_stock" href="controlador/StockController.php" vista="ver_discos">Discos</a></li>
  <li><a data-toggle="tab" data-target="#tabs5_stock" href="controlador/StockController.php" vista="ver_impresoras">Impresoras</a></li>
  <li><a data-toggle="tab" data-target="#tabs6_stock" href="controlador/StockController.php" vista="ver_routers">Routers</a></li>
  <li><a data-toggle="tab" data-target="#tabs7_stock" href="controlador/StockController.php" vista="ver_switchs">Switchs</a></li>
  <li><a data-toggle="tab" data-target="#tabs8_stock" href="controlador/StockController.php" vista="ver_tablets">Tablets</a></li>
  <li><a data-toggle="tab" data-target="#tabs9_stock" href="controlador/StockController.php" vista="ver_toners">Toners</a></li>
  
</ul>

<div class="tab-content">
  <div class="tab-pane" id="tabs1_stock"></div>
  <div class="tab-pane" id="tabs2_stock"></div>
  <div class="tab-pane" id="tabs3_stock"></div>
  <div class="tab-pane" id="tabs4_stock"></div>
  <div class="tab-pane" id="tabs5_stock"></div>
  <div class="tab-pane" id="tabs6_stock"></div>
  <div class="tab-pane" id="tabs7_stock"></div>
  <div class="tab-pane" id="tabs8_stock"></div>
  <div class="tab-pane" id="tabs9_stock"></div>


</div>

<script>

  $(document).ready(function(){
    $("#etabs").tab();
    $("#etabs").bind("show" , function (e){
        $("#contenedorPpal").remove();
        jQuery('<div/>', {
            id: 'contenedorPpal',
            text: 'Texto por defecto!'
            }).appendTo('.realBody');
        $("#contenedorPpal").load("controlador/StockController.php", {vista: $(e.target).attr("vista")});
    });

      var contentURL = "controlador/StockController.php";
      var contentID = "";
      var vista = "{vista}";

      if("{vista}" == "nada"){
        vista = "ver_monitores";
        contentID = $("#etabs a[vista=ver_monitores]").attr("data-target");
      }
      else
      {
        contentID = $("#etabs a[vista="+"{vista}"+"]").attr("data-target");  
      }   
      
      $(contentID).load(contentURL , {action : vista} , function (){ $("#etabs").tab(); });
      $(contentID).addClass('active');
      $("#etabs a[data-target="+contentID+"]").parent().addClass("active");    
  });
</script>
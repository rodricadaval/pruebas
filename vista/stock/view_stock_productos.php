<ul id="etabs" class='nav nav-tabs'>
  <li><a data-toggle="tab" data-target="#tabs1_stock" href="controlador/StockController.php" vista="ver_monitores">Monitores</a></li>
  <li><a data-toggle="tab" data-target="#tabs2_stock" href="controlador/StockController.php" vista="ver_computadoras">Computadoras</a></li>
  <li><a data-toggle="tab" data-target="#tabs3_stock" href="controlador/StockController.php" vista="ver_memorias">Memorias</a></li>
  <li><a data-toggle="tab" data-target="#tabs4_stock" href="controlador/StockController.php" vista="ver_discos">Discos</a></li>
  <li><a data-toggle="tab" data-target="#tabs5_stock" href="controlador/StockController.php" vista="ver_impresoras">Impresoras</a></li>
  
</ul>

<div class="tab-content">
  <div class="tab-pane" id="tabs1_stock"></div>
  <div class="tab-pane" id="tabs2_stock"></div>
  <div class="tab-pane" id="tabs3_stock"></div>
  <div class="tab-pane" id="tabs4_stock"></div>
  <div class="tab-pane" id="tabs5_stock"></div>

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

    if("{vista}" == "ver_monitores"){
      $("#tabs1_stock").load("controlador/StockController.php",{action:"ver_monitores"}); 
    }
    else if("{vista}" == "ver_memorias"){
      $("#tabs3_stock").load("controlador/StockController.php",{action:"ver_memorias"}); 
    }
    else{
      $("#etabs a:first").tab('show');  
    }

  });
</script>
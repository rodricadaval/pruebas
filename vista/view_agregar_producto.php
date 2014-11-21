<ul id="etabs" class='nav nav-tabs'>
  <li><a data-toggle="tab" data-target="#tabs1" href="controlador/ProductosController.php">Monitor</a></li>
  <li><a data-toggle="tab" data-target="#tabs2" href="#tabs2">Memoria</a></li>
  <li><a data-toggle="tab" data-target="#tabs3" href="#tabs3">Disco</a></li>
</ul>

<div class="tab-content">
  <div class="tab-pane" id="tabs1"><h2>Menu para agregar un monitor</h2></div>
  <div class="tab-pane" id="tabs2"><h2>Menu para agregar un memoria</h2></div>
  <div class="tab-pane" id="tabs3"><h2>Menu para agregar un dico</h2></div>
</div>

<script>


/*

      $.post('controlador/ProductosController.php',
        {
          action:"view_agregar_monitor",
          tipo:"sel_marcas",
          queSos: "n_monitor"
        }
        ,function(data){
          $("#tabs1").html(data);
        }
      );
*/


  $(document).ready(function(){
    $("#etabs").tab();
    $("#etabs").bind("show" , function (e){
      var contentID  = $(e.target).attr("data-target");
      var contentURL = $(e.target).attr("href");
      if (typeof(contentURL != 'undefined')){
        $(contentID).load(contentURL , {action : 'view_agregar_monitor' , tipo : 'sel_marcas'} , function (){ $("#etabs").tab(); });
      } else {
        $(contentID).tab('show');
      }
    });

    $("#etabs a:first").tab('show');

  })
</script>
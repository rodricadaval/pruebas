<head>
<script src="http://code.jquery.com/jquery.js" type="text/javascript"></script>
<script src="lib/jquery.hashchange.js" type="text/javascript"></script>
<script src="lib/jquery.easytabs.js" type="text/javascript"></script>
<link href="css/jquery-easytabs.css" rel="stylesheet" type="text/css">

<script type="text/javascript">
    $(document).ready( function() {
      $('#tab-container').easytabs();
    });
  </script>
</head>
<body>


<div id="tab-container" class="tab-container">
  <ul class='etabs'>
    <li class='tab'><a href="#tabs1">Monitor</a></li>
    <li class='tab'><a href="#tabs2">Memoria</a></li>
    <li class='tab'><a href="#tabs3">Disco</a></li>
  </ul>

  <div id="tabs1">
    <h2>Menu para agregar un monitor</h2>
    <script type="text/javascript">

    	$.post('controlador/ProductosController.php',
				{
					action:"view_agregar_monitor",
					tipo:"sel_marcas"
				}
				,function(data){
					$("#tabs1").html(data);
				}
			);

    </script>
  </div>
  <div id="tabs2">
    <h2>Menu para agregar una memoria</h2>
    <!-- content -->
  </div>
  <div id="tabs3">
    <h2>Menu para agregar un disco</h2>
    <!-- content -->
  </div>
</div>
</body>
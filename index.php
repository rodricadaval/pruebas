<?php
require_once 'ini.php';
require_once 'config.php';
include 'logueo/chequeo_login.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" content="text/html" http-equiv="Content-Type">
        <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
        <script src="//cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
        <script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
        <script src="lib/jquery.hashchange.js" type="text/javascript"></script>
        <script src="lib/jquery.easytabs.js" type="text/javascript"></script>
        <link href="css/jquery-easytabs.css" rel="stylesheet" type="text/css">
        <link href="//cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css" rel=
        "stylesheet" type="text/css">
        <link href="css/styles.css" rel="stylesheet" type="text/css">
        <link href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css"
        rel="stylesheet">

            <title>Inicio</title>
            </head>
            <body>
                <div class="as_wrapper">
                    <h1>Bienvenido
                    <b><?php echo ucfirst($_SESSION['username']);?></b><span class=
                    "logout" id="logout">Desconectarse</span></h1>
                </div>
                <div class="realBody">
<?php require_once (TEMPLATES . '/panel_izq.html');?>
                <br>
                <div id="contenedorPpal"></div>
            	</div>
                <script type="text/javascript">
	                $("#logout").on('click',function(){
		                $.ajax({
			                url : 'logueo/logout.php',
			                method: 'get',
			                complete: function(){window.location = "logueo/login.php"}
			            });
		            });
	               	$(".test").on('click',function(event){
	                	event.preventDefault();
	                $("#contenedorPpal").load($(this).attr("href"));
	           		});
	            </script>
            </body>
        </html>
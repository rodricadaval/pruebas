<?php
require_once 'ini.php';
require_once 'config.php';
include 'logueo/chequeo_login.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" content="text/html" http-equiv="Content-Type">
    <meta name="Author" content="Rodrigo Cadaval" />
    <link rel="shortcut icon" href="http://programasumar.com.ar/favicon.ico">
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <script src="lib/jquery.hashchange.js" type="text/javascript"></script>
    <script src="lib/jquery.validate.js" type="text/javascript"></script>
    <!-- <script src="semantic-ui/dist/semantic.js" type="text/javascript"></script> -->
    <script src="semantic/dist/semantic.min.js" type="text/javascript"></script>
    <script src="lib/sstock.js" type="text/javascript"></script>
    <script src="lib/bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.66.0-2013.10.09/jquery.blockUI.min.js"></script>
    <!-- <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css"> -->
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="//cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css" rel=
    "stylesheet" type="text/css">
    <!-- <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.js"></script>-->
    <link href="css/semantic.css" rel="stylesheet" type="text/css">
    <!-- <link href="semantic/dist/semantic.min.css" rel="stylesheet" type="text/css"> -->
    <link href="css/styles.css" rel="stylesheet" type="text/css">
    <link href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css"
    rel="stylesheet">

    <title>Sistema Stock SUMAR</title>
</head>
<body>


    <div class="ui fixed inverted main menu" style="background-color: #0000000; color: rgba(0, 0, 0, 0.8);">
        <div class="title item">
            <span class="bienvenida_usuario" ><i class="user icon"></i></b><text style="color:#1E90FF; font-style:italic;"> <?php echo ucfirst($_SESSION['userRealName']);?></text></span>
            <span class="logout" id="logout">Desconectarse</span>
        </div>
    </div>
    <div class="container-fluid">
        <div class="ui segment">
            <div class="row-fluid">
                <div class="span2">
                    <?php require_once TEMPLATES.'/panel_izq.html';?>
                </div>
                <div class="span10">
                    <div class="realBody">
                        <div id="contenedorPpal">
                            <div class="row-fluid">
                                <div class="span4">
                                    <h2 class="ui header">
                                        <img src="images/computer-icon.png" class="ui circular image">
                                    </h2>
                                </div>
                                <div class="span8">
                                    <div class="ui raised segment" style="width:90%;">
                                      <h3 class="hometext">Bienvenido a la p&aacute;gina principal del sistema de stock del programa SUMAR! Clickee en los distintos men&uacute;s de su izquierda para acceder a los datos.</h3>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <div class="ui inverted black footer vertical segment">
      <div class="container">
        <div class="ui stackable inverted divided relaxed grid">
          <div class="eight wide column">
            <h3 class="ui inverted header"></h3>
            <!-- <p>Rodrigo Cadaval</p> -->
            <p>Área Sistemas Informáticos</p>
            <p>Programa SUMAR - Ministerio de Salud de La Nación</p>
            <p>Tel.: (011) 4331-5701 int 162</p>
        </div>
        <div class="eight wide column">
            <h5 class="ui inverted header">Sistema de Stock y Asignaci&oacute;n de productos.</h5>
            <div class="ui inverted link list">
              <a class="item" href="http://programasumar.com.ar">Web Programa SUMAR</a>
              <a class="item" href="http://programasumar.com.ar/uec/">Acceso a empleados</a>
          </div>
      </div>
  </div>
</div>
</div>

<script type="text/javascript">

    $.validator.addMethod("notEqual", function(value, element, param) {
     return this.optional(element) || value != param;
 });

    $.validator.addMethod("sinCpu",function (value,element){
     return value!=1;
 }, 'El usuario no tiene Cpu.');

    $.validator.addMethod('IP4Checker', function(value) {
        var ip = "^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$";
        if(value == ""){
            return true;
        }
        else{
            return value.match(ip);
        }
    }, 'Invalid IP address');

    $("#logout").on('click',function(){
      $.ajax({
       url : 'logueo/logout.php',
       method: 'get',
       complete: function(){window.location = "logueo/login.php"}
   });
  });
    var primeraVez = true;
    $("div#panel-lateral a").on('click',function(event){
      event.preventDefault();

      $.get('logueo/check_priority.php', function(permisos) {
        console.log(permisos);

                         /*   if(permisos == "Error"){
                                 window.location.replace("logueo/login.php");
                             }*/
                         });


      if($(this).attr("href") == "mis_productos"){
        console.log("Entro a ver los productos del usuario");


        $.get('logueo/check_user_id.php', function(id) {
            console.log("usuario: "+id);
            var usuario = id;

            $("#contenedorPpal").remove();
            jQuery('<div/>', {
                id: 'contenedorPpal',
                text: ''
            }).appendTo('.realBody');

            $.post( "vista/dialog_productos_usuario.php",
            {
                usuario : usuario,
                action : "ver_productos"
            }, function(data){
                jQuery('<div/>', {
                    id: 'dialogcontent_prod_usuario',
                    text: ''
                }).appendTo('#contenedorPpal');
                $("#contenedorPpal").html(data);
                                          /*  $("#dialogcontent_prod_usuario").dialog({
                                                                        title: "Mis Productos",
                                                                        show: {
                                                                        effect: "explode",
                                                                        duration: 200,
                                                                        modal:true
                                                                        },
                                                                        hide: {
                                                                        effect: "explode",
                                                                        duration: 200
                                                                        },
                                                                        width : 600,
                                                                        height : 630,
                                                                        close : function(){
                                                                            $(this).dialog("destroy").empty();
                                                                            $("#dialogcontent_prod_usuario").remove();
                                                                        },
                                                                        buttons :
                                                                        {
                                                                            "Aceptar" : function () {
                                                                                $(this).dialog("destroy").empty();
                                                                                $("#dialogcontent_prod_usuario").remove();
                                                                            }
                                                                        }
                                                                    });*/
                                                                }
                                                                );

        });
    }
    else{
        if(primeraVez){
         $("#contenedorPpal").load($(this).attr("href"));
         primeraVez = false;
     }
     else{
        $("#contenedorPpal").remove();
        jQuery('<div/>', {
            id: 'contenedorPpal',
            text: 'Texto por defecto!'
        }).appendTo('.realBody');
        $("#contenedorPpal").load($(this).attr("href"));
    }
}

});
</script>
</body>
</html>
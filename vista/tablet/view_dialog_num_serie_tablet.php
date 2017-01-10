<form id="form_num_serie_usuario" autocomplete="off">
    <table class="t_monitor">
       
        <tr>
            <ul class="nav nav-tabs">
                <li class="active" id="serie"><a data-toggle="tab" href="">Num serie</a></li>
                <li id="usuario"><a data-toggle="tab">Usuario</a></li>
                <li id="descripcion"><a data-toggle="tab">Descripcion</a></li>
            </ul>
        </tr>
        <tr>
            <tr type="hidden">
             <td><input type="hidden" name="id_tablet" id="id_tablet" value="{id_tablet}"></td>
         </tr>
          <tr class="desc">
            <td colspan="2">Descripcion:</td>   
        </tr>
        <tr class="desc">
            <td><textarea rows="4" cols="50" name="descripcion"></textarea></td>
        </tr>
         <tr class="serie">
            <td colspan="2">Num Serie:</td>   
        </tr>
        <tr class="serie">
            <td><textarea rows="4" cols="50" name="num_serie"></textarea></td>
        </tr>

        <tr class="usuario">
            <td>Usuario:</td>
            <td>
               <div id="multiple-datasets">
                 <input name="nombre_usuario" id="nombre_usuario" class="typeahead" type="text" placeholder="Nombre de usuario">
             </div>
         </td>
         <td id="usuario" value=""></td>
     </tr>
     <tr class="usuario">
      <td>Sector:</td>
      <td id="sector"><input type="hidden" name="sector"></td>
  </tr>
  <tr><td colspan="2"><div class="error text-error"></div></td></tr>  
</table>
</form>

<script>

    $(document).ready(function(){

        $(".usuario").hide();
        $(".desc").hide();

        $("#usuario").on("click",function () {
            $(".serie").hide();
            $(".desc").hide();
            $(".usuario").show();
        })

        $("#serie").on("click",function () {
            $(".usuario").hide();
            $(".desc").hide();
            $(".serie").show();            
        })

          $("#descripcion").on("click",function () {
            $(".usuario").hide();
            $(".serie").hide();            
            $(".desc").show();            
        })

        /*$("#form_num_serie").on('submit',function(event){
            event.preventDefault();

            var datosUrl = $("#form_num_serie").serialize();

            datosUrl += "&action=agregar_num_serie";

            console.log(datosUrl);

            $.ajax({
                url: 'controlador/TabletsController.php',
                type: 'POST',
                data: datosUrl,
                success: function(response){
                    if(response){
                        console.log("success");
                        alert("El num_serie ha sido modificado correctamente.");
                        $("#dialogcontent").dialog("destroy").empty();
                        $("#dialogcontent").remove();
                        $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                            id: 'contenedorPpal',
                            text: 'Texto por defecto!'
                        }).appendTo('.realBody');
                        $("#contenedorPpal").load("controlador/TabletsController.php");
                    }
                }
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
        }); */


        $("#nombre_usuario").on('focus', function(){
         this.select();
     })


        $("#nombre_usuario").typeahead({
            source : function (query , process) {
                $.ajax({
                    type         : 'post' ,
                    data         : {
                        term         : query
                    } ,
                    url          : 'lib/listado_usuarios.php' ,
                    dataType     : 'json' ,
                    success     : function (data) {
                        process (data);
                    }
                })
            } ,
            minLength : 3,
            updater: function(obj) { console.log(obj);
                if(obj != "Sin usuario"){
                    console.log('Entre a cambiar el area');

                    $.post('controlador/UsuariosController.php',
                    {
                        nombre_usuario : obj,
                        action : "nombre_sector"

                    }, function(nombre_sector) {
                        console.log("El area es: "+nombre_sector);
                        $("#sector").text(nombre_sector);
                    });
                }
                else{console.log("No entro");}
                $("#error").hide();

                return obj; }

            });

        $("#form_num_serie_usuario").validate({
            errorLabelContainer : ".error" ,
            wrapper : "li" ,
            onfocusout: false,
            onkeyup: false,
            onclick: false,
            onsubmit: true,
            rules : {
                nombre_usuario : {
                    required : true,
                    remote      : {
                        url     : 'lib/busca_usuario.php' ,
                        type     : 'post' ,
                        data     : {
                            nombre_usuario : function() {
                                return $("#nombre_usuario").val();
                            }
                        }
                    },
                    notEqual: "Sin usuario"
                }    
            } ,
            messages : {
                nombre_usuario : {
                    remote : 'El usuario no existe',
                    required: "El campo usuario es OBLIGATORIO",
                    notEqual: "No se puede asignar a Sin usuario. Si quieres quitar el usuario LIBERA la tablet"
                }
            } ,
            submitHandler : function (form) {
              console.log ("Formulario OK");

              console.log($("#form_num_serie_usuario").serialize());

              var datosUrl = $("#form_num_serie_usuario").serialize();

              var cambiarUsuario = datosUrl +  "&action=cambiar_usuario";
              
              var agregarNumSerie = datosUrl + "&action=agregar_num_serie";

              var cambiarDesc = datosUrl + "&action=agregar_desc";
              
              console.log(cambiarUsuario);
              console.log(agregarNumSerie);
              console.log(cambiarDesc);

              $.ajax({
                url: 'controlador/TabletsController.php',
                type: 'POST',
                data: agregarNumSerie,
                success: function(response){
                    if(response){
                        console.log("success");
                    }
                }
            })
              .fail(function() {
                console.log("error");
            })
              .always(function() {
                console.log("complete");
            });

              $.ajax({
                url: 'controlador/TabletsController.php',
                type: 'POST',
                data: cambiarDesc,
                success: function(response){
                    console.log(response);

                    if(response){
                        console.log("success");
                    }
                }
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });

              $.ajax({
                url: 'controlador/TabletsController.php',
                type: 'POST',
                data: cambiarUsuario,
                success : function(response){
                    console.log(response);
                    if(response){
                        console.log(response);
                        alert("Los datos han sido actualizados correctamente.");
                        $("#dialogcontent").dialog("destroy").empty();
                        $("#dialogcontent").remove();
                        $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                            id: 'contenedorPpal',
                            text: 'Texto por defecto!'
                        }).appendTo('.realBody');
                        $("#contenedorPpal").load("controlador/TabletsController.php");
                    }
                    else{
                       alert("Error en la query.");
                   }
               }
           })
              .fail(function() {
                console.log("error");
                alert("Algo no se registro correctamente");
            })
              .always(function() {
                console.log("complete");
            })

          } ,
          invalidHandler : function (event , validator) {
              console.log(validator);
          }
      });    
    });

</script>
<form id="form_computadora">
    <table class="t_cpu">
        <tr>
        <tr type="hidden">
           <td><input type="hidden" name="id_vinculo" id="id_vinculo" value="{id_vinculo}"></td>
        </tr>
        <tr>
          <td>Sector:</td>
 		  <td>{select_Areas}</td>
        </tr>
         <tr>
            <td>Usuario:</td>
            <td>
                   <div id="multiple-datasets">
                     <input name="nombre_usuario" id="nombre_usuario" class="typeahead" type="text" placeholder="Nombre de usuario" value="{nombre_apellido}">
                </div>
            </td>
        </tr>
        <tr><td></td><td><div class="error text-error"></div></td></tr>
        <tr>
          <td><input style="background-color:#D3D3D3" type="hidden" name="id_cpu" id="id_cpu" value="{id_computadora}" readonly></td>
        </tr>
   </table>
</form>

<script>

$(document).ready(function(){

	var validado = false;
    var fueRegalo = false;

 	if($('#select_areas option:selected').val() == 1){$("#nombre_usuario").attr('readonly','readonly');}

    $('#select_areas').removeAttr('disabled');

    $('#select_areas').on('change', function(){

        var id_sector = $(this).val();
        console.log('Entro al cambio de area');

        if (id_sector == 1) {

            // SI EL DEPOSITO ES STOCK, NO PERMITE INGRESAR USUARIO
            // SE PODRIA INFORMAR AL USUARIO DE QUE NO SE PERMITE INGRESAR UN USUARIO, PERO ES MEDIO MOLESTO
            fueRegalo = false;
            $('#nombre_usuario').val('Sin usuario');
            $("#nombre_usuario").attr('readonly','readonly');
            //$('#select_usuarios_monitor option:contains("Ninguno")').prop('selected', true);

        } else if (id_sector == 2) {

            // SI EL DEPOSITO ES REGALOS, INFORMA DE QUE SE DEBE INGRESAR UN USUARIO (NO IMPORTA SI EL USUARIO TIENE UN DEPOSITO ASOCIADO)
            // LA VALIDACION SE HACE CON JQUERYVALIDATOR AL MOMENTO DE ENVIAR EL FORMULARIO
            fueRegalo = true;
            $('#nombre_usuario').removeAttr('readonly');
            $('#alerta_regalo').dialog({
                buttons : [
                    {
                        text : 'Aceptar' ,
                        click : function () {
                            $(this).dialog("close");
                        }
                    }
                ]
            });
         } else {

         	if(fueRegalo){

                    if( $('#nombre_usuario').val() != "Sin usuario"){

                        $.post('controlador/UsuariosController.php',
                        {
                            nombre_usuario : $('#nombre_usuario').val(),
                            action : "buscar_area"

                        }, function(id_area) {

                                $('#select_areas').removeAttr('disabled');
                                $('#select_areas option[value='+id_area+']').attr('selected', 'selected');
                                $('#select_areas').attr('disabled', 'disabled');
                        });

                    }
                    fueRegalo = false;
            }
            else{
                console.log('seleccionando un area que no es stock');
            }

            // EL DEPOSITO NO ES STOCK NI REGALOS
            $('#nombre_usuario').removeAttr('readonly');

        }
    });

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

        						if(obj == "Sin usuario"){
        							   $('#select_areas').removeAttr('disabled');
        						}
                                else if(obj != "Sin usuario" && $('#select_areas option:selected').val() != 2){
                                    console.log('Entre a cambiar el area');

                                    $('#select_areas').removeAttr('disabled');

                                    $.post('controlador/UsuariosController.php',
                                    {
                                        nombre_usuario : obj,
                                        action : "buscar_area"

                                    }, function(id_area) {
                                    		console.log("El area es: "+id_area);

                                            $('#select_areas option[value='+id_area+']').attr('selected', 'selected');
                                            $('#select_areas').attr('disabled', 'disabled');
                                    });

                                }
                                else{console.log("No entro");}

                                return obj; }

    });

 $("#form_computadora").validate({
        errorLabelContainer : ".error" ,
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
                }
            }
        } ,
        messages : {
            nombre_usuario : {
                remote : 'El usuario no existe'
            }
        } ,
        submitHandler : function (form) {
          validado = true;
          console.log (validado);
          console.log ("Formulario OK");
        } ,
        invalidHandler : function (event , validator) {
          console.log(validator);
          validado = false;
        }
    });


	$("#form_computadora").on('submit',function(event){

 		event.preventDefault();

        console.log('Validado se encuentra en: ' + validado);
        if(validado == true){
        	console.log($("#form_computadora").serialize());

        	var datosUrl =    $("#form_computadora").serialize();
            if($("#select_areas option:selected").val() > 2)
            {
                datosUrl += "&area="+ $("#select_areas option:selected").val();
            }
            datosUrl += "&action=modificar";

            console.log(datosUrl);

            $.ajax({
                url: 'controlador/ComputadorasController.php',
                type: 'POST',
                data: datosUrl,
                success : function(response){
                    if(response){
	                    console.log(response);
	                    alert("Los datos han sido actualizados correctamente");
	                    $("#dialogcontent_cpu").dialog("destroy").empty();
	                    $("#contenedorPpal").load("controlador/ComputadorasController.php");
                	}
                	else{
                	alert("Error en la query.");
                	}
                }
            })
            .fail(function() {
                console.log("error");
                alert("Algo no se registro correctaente");
            })
            .always(function() {
                console.log("complete");
            })
        }
        else{console.log('No estan bien ingresados los datos, chequear validacion');}

    });
});




</script>


<form id="form_monitor">
    <table class="t_monitor">
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
        <tr>
            <td>Cpu Serie:</td>
            <td>
                   <div id="multiple-datasets">
                     <input name="cpu_serie" id="cpu_serie" class="typeahead" type="text" placeholder="Numero de serie cpu" value="{num_serie_cpu}">
                </div>
            </td>
        </tr>
        <tr><td></td><td><div class="error text-error"></div></td></tr>
  </table>
</form>

<div style="display:none">
    <div id="alerta_regalo" title="ATENCION!">Recuerde ingresar el usuario que solicit&oacute; el regalo</div>
    <div id="alerta_stock" title="ATENCION!"></div>
</div>

<script>

$(document).ready(function(){


    var validado = false;
    var fueRegalo             = false;

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
                                            //$('#select_areas').find('option[value='+id_area+']').attr('selected', 'selected');
                                            $('#select_areas').attr('disabled', 'disabled');
                                    });

                                }
                                else{console.log("No entro");}

                                return obj; }

    });

$("#cpu_serie").typeahead({
        source : function (query , process) {
            $.ajax({
                type         : 'post' ,
                data         : {
                    term         : query
                } ,
                url          : 'lib/listado_cpu_series.php' ,
                dataType     : 'json' ,
                success     : function (data) {
                    process (data);
                }
            })
        } ,
        minLength : 3,
        updater: function(obj) { console.log(obj);

        						if(obj != "Sin serie" && ($("#nombre_usuario").val() == "Sin usuario" || $("#nombre_usuario").val() == "")){
        							console.log('Entre a cambiar el area');

                                    $.post('controlador/ComputadorasController.php',
                                    {
                                        num_serie : obj,
                                        action : "buscar_area"

                                    }, function(id_area) {
                                    		console.log("El area es: "+id_area);

                                            $('#select_areas option[value='+id_area+']').attr('selected', 'selected');
                                            $('#select_areas').attr('disabled', 'disabled');
                                    });
              					}
                                else{console.log("No entro a cambiar el area por el cpu elegido");}
                                return obj; }

    });

    $("#form_monitor").validate({
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
            },
            cpu_serie : {
            	required : true,
            	remote :{
            		url : 'lib/busca_misma_area.php',
            		type: 'post',
            		data : {
            			cpu_serie : function(){
            				return $('#cpu_serie').val();
            			},
            			id_area : function(){
            				return $('#select_areas option:selected').val();
            			}
            		}
            	}
            }
        } ,
        messages : {
            nombre_usuario : {
                remote : 'El usuario no existe'
            },
            cpu_serie :{
            	remote: 'El sector propio del cpu no es el mismo que el del usuario o el numero de serie no existe. Ingrese otra cpu o asigne dicha cpu al usuario desde el ABM de Computadoras'
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


    $("#form_monitor").on('submit',function(event){

        event.preventDefault();

        console.log('Validado se encuentra en: ' + validado);
        if(validado == true){
        	console.log($("#form_monitor").serialize());

        	var datosUrl =    $("#form_monitor").serialize();
            if($("#select_areas option:selected").val() > 2)
            {
                datosUrl += "&area="+ $("#select_areas option:selected").val();
            }
            datosUrl += "&action=modificar";

            console.log(datosUrl);

            $.ajax({
                url: 'controlador/MonitoresController.php',
                type: 'POST',
                data: datosUrl,
                success : function(response){
                    if(response){
	                    console.log(response);
	                    alert("Los datos han sido actualizados correctamente");
	                    $("#dialogcontent_monitor").dialog("destroy").empty();
	                    $("#contenedorPpal").load("controlador/MonitoresController.php");
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
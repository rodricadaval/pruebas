<form id="form_memoria_mod_usuario">
    <table class="t_monitor">
        <tr>
            <tr type="hidden">
               <td><input type="hidden" name="id_vinculo" id="id_vinculo" value="{id_vinculo}"></td>
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
          <td>Sector:</td>
           <td>{select_Areas}</td>
        </tr>
        <tr>
            <td>{select_Computadoras}</td>
            <td><input name="capacidad_mem" id="capacidad_mem" type="text" value="{capacidad}"></td>
        </tr>
        <tr><td colspan="2"><div class="error text-error"></div></td></tr>
  </table>
</form>


<script>

$(document).ready(function(){

     $('#select_computadoras_memoria').hide();
     $("#capacidad_mem").hide();

     console.log($("#capacidad_mem").val());


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

                                if(obj != "Sin usuario" && $('#select_areas option:selected').val() != 2){
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

                                    $.post('controlador/ComputadorasController.php',
                                    {
                                        nombre_usuario : obj,
                                        action : "cpus_del_usuario",
                                        extra_id_select : "memoria"

                                    }, function(select) {
                                            console.log("El select es: "+select);

                                            $('#select_computadoras_memoria').replaceWith(select);
                                            $('#select_computadoras_memoria').hide();

                                    });



                                }
                                else{console.log("No entro");}

                                return obj; }

    });

    $.validator.addMethod("palabra",function (value,element){
          return value!="Sin usuario"; 
        }, 'No puede elegir este usuario. Si quiere liberar la memoria clickea en el boton LIBERAR');

/*     $.validator.addMethod("check_mem",function (value,element){
           
        }, 'No puede elegir este usuario. Si quiere liberar la memoria clickea en el boton LIBERAR');*/

    $("#form_memoria_mod_usuario").validate({
        errorLabelContainer : ".error" ,
        wrapper : "li" ,
        ignore: [],
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        onsubmit: true,
        rules : {
            id_computadora : {
                remote: {
                    url: "controlador/ComputadorasController.php",
                    type: "post",
                    data: {
                      id_cpu: function(){
                        return $("#select_computadoras_memoria option:selected").val();
                      },
                      action: function(){
                        return "chequear_slots";
                      }
                    }
                }
            },
            nombre_usuario: {
                palabra: true
            },
            capacidad_mem: {
                remote: {
                    url: "controlador/ComputadorasController.php",
                    type: "post",
                    data: {
                      id_cpu: function(){
                        return $("#select_computadoras_memoria option:selected").val();
                      },
                      action: function(){
                        return "chequear_espacio_mem";
                      },
                      capacidad: function(){
                        return $("capacidad_mem").val();
                      }
                    }
                }
            }
        } ,
        messages : {
            id_computadora : {
                remote: 'La CPU del usuario tiene los slots llenos. No puede asignarle mas memorias' 
            },
            capacidad_mem : {
                remote: 'No se puede asignar esta memoria en la computadora del usuario. No alcanza el espacio de memoria'
            }
        } ,
        submitHandler : function (form) {

            console.log ("Formulario OK");
            
            /*$("#capacidad_mem").attr("disabled","disabled");

            console.log($("#form_memoria_mod_usuario").serialize());
    
            var datosUrl =    $("#form_memoria_mod_usuario").serialize();
            datosUrl += "&area="+ $("#select_areas option:selected").val();
            
            datosUrl += "&action=modificar&asing_usr=yes";

            console.log(datosUrl);

            $.ajax({
                url: 'controlador/MemoriasController.php',
                type: 'POST',
                data: datosUrl,
                success : function(response){
                    if(response){
                        console.log(response);
                        alert("Los datos han sido actualizados correctamente. Al cambiar de usuario se reemplazará automáticamente el sector de la Cpu por el del usuario elegido.");
                        $("#dialogcontent_memoria").dialog("destroy").empty();
                        $("#dialogcontent_memoria").remove();
                        $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                        id: 'contenedorPpal',
                        text: 'Texto por defecto!'
                        }).appendTo('.realBody');
                        $("#contenedorPpal").load("controlador/MemoriasController.php");
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
            })*/
        } ,
        invalidHandler : function (event , validator) {
          console.log(validator);
        }
    });
});
</script>
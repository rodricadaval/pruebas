<form id="form_memoria_mod_usuario" autocomplete="off">
    <table class="t_monitor">
        <tr>
            <td><input type="hidden" name="id_vinculo" id="id_vinculo" value="{id_vinculo}"></td>
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
            <td><input name="id_memoria" id="id_memoria" type="hidden" value="{id_memoria}"></td>
        
        </tr>
        <tr><td colspan="2"><div class="error text-error"></div></td></tr>
  </table>
</form>


<script>

$(document).ready(function(){

     $('#select_computadoras_memoria').hide();
     console.log("El id de la memoria es "+{id_memoria});

     console.log("Dialogo de asignacion de una Memoria de: "+{capacidad}+" "+ "{unidad}");
     var id_memoria = {id_memoria};
     var id_cpu_orig = $("#select_computadoras_memoria option:selected").val();

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

                                $(".error").empty();
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
                                            if($("#select_computadoras_memoria").val() == 1){
                                                $("#id_memoria").val("");
                                            }
                                            else{
                                                $("#id_memoria").val({id_memoria});
                                            }
                                            $('#select_computadoras_memoria').hide();

                                    });



                                }
                                else{console.log("No entro");}

                                return obj; }

    });

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
                },
                sinCpu: true
            },
            nombre_usuario: {
                remote      : {
                        url     : 'lib/busca_usuario.php' ,
                        type     : 'post' ,
                        data     : {
                            nombre_usuario : function() {
                                return $("#nombre_usuario").val();
                            }
                        }
                    },
                notEqual: "Sin usuario",
                required: true
            },
            id_memoria: {
                required: true,
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
                      id_memoria : function(){
                        return $("#id_memoria").val();
                      }
                    }
                }
            }
        } ,
        messages : {
            id_computadora : {
                remote: 'La CPU del usuario tiene los slots llenos. No puede asignarle mas memorias',
                notEqual: 'La cpu del usuario es la misma que la inicial' 
            },
            id_memoria : {
                required: 'No se puede asignar',
                remote: 'No se puede asignar esta memoria en la computadora del usuario. No alcanza el espacio o el usuario no tiene pc'
            },
            nombre_usuario: {
                remote: 'El usuario no existe',
                notEqual: 'No se puede asignar a Sin usuario. Para liberar la memoria clickee el boton LIBERAR',
                required : 'El campo usuario es obligatorio'
            }
        } ,
        submitHandler : function (form) {

            console.log ("Formulario OK");
            
            $("#id_memoria").attr("disabled","disabled");

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
                        if("{viene}" == "normal"){
                            $("#contenedorPpal").load("controlador/MemoriasController.php");
                        }
                        else if("{viene}" == "stock"){
                            $("#contenedorPpal").load("controlador/StockController.php", {vista: "ver_memorias"});
                        }
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
        } ,
        invalidHandler : function (event , validator) {
          console.log(validator);
        }
    });
});
</script>
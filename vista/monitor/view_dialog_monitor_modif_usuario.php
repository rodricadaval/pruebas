<form id="form_monitor_mod_usuario" autocomplete="off">
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
        </tr>
        <tr><td></td><td><div class="error text-error"></div></td></tr>
  </table>
</form>

<script>

$(document).ready(function(){


    $('#select_computadoras_monitor').attr('style', 'display:none');     

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
                                        extra_id_select : "monitor"

                                    }, function(select) {
                                            console.log("El select es: "+select);

                                            $('#select_computadoras_monitor').replaceWith(select);
                                             $('#select_computadoras_monitor').attr('style','display:none');

                                    });



                                }
                                else{console.log("No entro");}

                                return obj; }

    });

    $("#form_monitor_mod_usuario").validate({
            errorLabelContainer : ".error" ,
            wrapper : "li" ,
            ignore: [],
            onfocusout: false,
            onkeyup: false,
            onclick: false,
            onsubmit: true,
            rules : {
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
                    notEqual: 'Sin usuario',
                    required: true
                }
            } ,
            messages : {
                nombre_usuario: {
                    remote: 'No existe dicho usuario',
                    notEqual: 'No se puede asignar a Sin usuario. Para liberar el monitor clickee el boton LIBERAR',
                    required: 'El campo usuario es OBLIGATORIO'
                }
            } ,
            submitHandler : function (form) {

                console.log ("Formulario OK");
                
                console.log($("#form_monitor_mod_usuario").serialize());
        
                var datosUrl =    $("#form_monitor_mod_usuario").serialize();
                datosUrl += "&area="+ $("#select_areas option:selected").val();
                
                datosUrl += "&action=modificar&asing_usr=yes";

                console.log(datosUrl);

                $.ajax({
                    url: 'controlador/MonitoresController.php',
                    type: 'POST',
                    data: datosUrl,
                    success : function(response){
                        if(response){
                            console.log(response);
                            alert("Los datos han sido actualizados correctamente. Al cambiar de usuario se reemplazará automáticamente el sector de la Cpu por el del usuario elegido.");
                            $("#dialogcontent_monitor").dialog("destroy").empty();
                            $("#dialogcontent_monitor").remove();
                            $("#contenedorPpal").remove();
                            jQuery('<div/>', {
                            id: 'contenedorPpal',
                            text: 'Texto por defecto!'
                            }).appendTo('.realBody');
                            if("{viene}" == "normal"){
                                $("#contenedorPpal").load("controlador/MonitoresController.php");
                            }
                            else if("{viene}" == "stock"){
                                $("#contenedorPpal").load("controlador/StockController.php", {vista: "ver_monitores"});
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
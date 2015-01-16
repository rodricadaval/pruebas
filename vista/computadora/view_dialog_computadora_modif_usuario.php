<form id="form_cambiar_usuario_computadora" autocomplete="off">
    <table class="t_monitor">
        <tr>
            <tr type="hidden">
               <td><input type="hidden" name="id_computadora" id="id_computadora" value="{id_computadora}"></td>
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
        <tr><td colspan="2"><label id="text_pregunta">¿Desea que todos los productos asignados a esta computadora continuén asignados luego de la modificación? Si no tiene productos asignados elija cualquiera.</label></td></tr>
        <tr>
                <td colspan="2" id="boton_radio">
                <label>                
                    <input type="radio" name="en_conjunto" value="SI" checked>SI
                    <input style="margin-left:10px;" type="radio" name="en_conjunto" value="NO">NO
                </label>
               </td>
        </tr>
        <tr><td colspan="2"><div class="error text-error"></div></td></tr>
  </table>
</form>

<script>

$(document).ready(function(){

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
                                }
                                else{console.log("No entro");}
                                $("#error").hide();

                                return obj; }

    });

     $("#form_cambiar_usuario_computadora").validate({
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
                notEqual: "No se puede asignar a Sin usuario. Si quieres quitar el usuario LIBERA la cpu"
            }
        } ,
        submitHandler : function (form) {
          console.log ("Formulario OK");

            console.log($("#form_cambiar_usuario_computadora").serialize());
    
            var datosUrl =    $("#form_cambiar_usuario_computadora").serialize();
            if($("#select_areas option:selected").val() > 1)
            {
                datosUrl += "&area="+ $("#select_areas option:selected").val();
            }
            datosUrl += "&action=modificar&cuestion=usuario";

            console.log(datosUrl);

            $.ajax({
                url: 'controlador/ComputadorasController.php',
                type: 'POST',
                data: datosUrl,
                success : function(response){
                    if(response){
                        console.log(response);
                        alert("Los datos han sido actualizados correctamente. Tenga en cuenta que al cambiar de usuario se reemplazará automáticamente la Cpu asignada por la del usuario elegido.");
                        $("#dialogcontent_cpu").dialog("destroy").empty();
                        $("#dialogcontent_cpu").remove();
                        $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                        id: 'contenedorPpal',
                        text: 'Texto por defecto!'
                        }).appendTo('.realBody');
                        if("{viene}" == "normal"){
                            $("#contenedorPpal").load("controlador/ComputadorasController.php");
                        }
                        else if("{viene}" == "stock"){
                            $("#contenedorPpal").load("controlador/StockController.php", {vista: "ver_computadoras"});
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
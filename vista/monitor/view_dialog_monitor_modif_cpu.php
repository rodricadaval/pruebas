<form id="form_monitor_mod_cpu">
    <table class="t_monitor">
        <tr type="hidden">
           <td><input type="hidden" name="id_vinculo" id="id_vinculo" value="{id_vinculo}"></td>
        </tr>
        <tr><p style="font-size:12px;">* Las computadoras que aparecen sólo son las del mismo sector del monitor. <br> Modifique el sector de la computadora primero si desea asignarle el monitor. </p></tr>
        <tr>
            <td>Usuario:</td>
            <td>
                   <div id="multiple-datasets">
                     <input name="nombre_usuario" id="nombre_usuario" type="text" placeholder="Nombre de usuario" value="{nombre_apellido}" readonly>
                </div>
            </td>
        </tr>
        <tr>
             <td>{select_Areas}</td>
        </tr>
        <tr>
            <td>Cpu Serie:</td>
            <td>{select_Computadoras}</td>
        </tr>
  </table>
</form>

<script>

$(document).ready(function(){


    $('#select_areas').hide();
    $('#select_areas').removeAttr('disabled');     
   
    console.log("id_area: "+$('#select_areas').val());
    console.log("{select_Computadoras}");

    $("#form_monitor_mod_cpu").on('submit',function(event){

        event.preventDefault();

        	console.log($("#form_monitor_mod_cpu").serialize());
    
        	var datosUrl =    $("#form_monitor_mod_cpu").serialize();
           
            datosUrl += "&action=modificar&asing_cpu=yes";

            console.log(datosUrl);

            $.ajax({
                url: 'controlador/MonitoresController.php',
                type: 'POST',
                data: datosUrl,
                success : function(response){
                    if(response){
	                    console.log(response);
	                    alert("Los datos han sido actualizados correctamente.");
	                    $("#dialogcontent_monitor").dialog("destroy").empty();
                        $("#dialogcontent_monitor").remove();
                        $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                        id: 'contenedorPpal',
                        text: 'Texto por defecto!'
                        }).appendTo('.realBody');
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
    });

});

</script>
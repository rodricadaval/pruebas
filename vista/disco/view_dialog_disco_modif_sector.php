<form id="form_disco_mod_sector">
    <table class="t_disco">
        <tr><p id="text_no_puede_cambiar">El Disco no puede ser cambiado de Sector ya que tiene un usuario o cpu asignado/s. Debe clickear en "liberar disco" si quiere modificar su sector. </p></tr>
        <tr type="hidden">
           <td><input type="hidden" name="id_vinculo" id="id_vinculo" value="{id_vinculo}"></td>
        </tr>
        <tr>
             <td>{select_Areas}</td>
        </tr>
    </table>
</form>

<script>

$(document).ready(function(){

    console.log("Esta libre? : {libre}");
    console.log("El id del vinculo es:"+$("#id_vinculo").val());

    if("{libre}" == 1){
        $("#text_no_puede_cambiar").hide();
        $("#select_areas").removeAttr("disabled");
    }
    else{
        $("#select_areas").hide();
        $("#id_vinculo").attr('disabled','disabled');
    }

    $("#form_disco_mod_sector").on('submit',function(event){

        event.preventDefault();

        if("{libre}" == 1){
        
        	console.log($("#form_disco_mod_sector").serialize());
    
        	var datosUrl =    $("#form_disco_mod_sector").serialize();
            
            datosUrl += "&action=modificar&asing_sector=yes";

            console.log(datosUrl);

            $.ajax({
                url: 'controlador/DiscosController.php',
                type: 'POST',
                data: datosUrl,
                success : function(response){
                    if(response){
	                    console.log(response);
	                    alert("Los datos han sido actualizados correctamente.");
	                    $("#dialogcontent_disco").dialog("destroy").empty();
                        $("#dialogcontent_disco").remove();
                        $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                        id: 'contenedorPpal',
                        text: 'Texto por defecto!'
                        }).appendTo('.realBody');
	                    $("#contenedorPpal").load("controlador/DiscosController.php");
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
        else{$("#dialogcontent_disco").dialog("destroy").empty();}
    });
});

</script>
<form id="form_impresora_mod_sector" autocomplete="off">
    <table class="t_impresora">
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

    console.log("El id del vinculo es:"+$("#id_vinculo").val());

    $("#select_areas").removeAttr("disabled");

    $("#form_impresora_mod_sector").on('submit',function(event){

        event.preventDefault();
        
        	console.log($("#form_impresora_mod_sector").serialize());
    
        	var datosUrl =    $("#form_impresora_mod_sector").serialize();
            
            datosUrl += "&action=modificar&asing_sector=yes";

            console.log(datosUrl);

            $.ajax({
                url: 'controlador/ImpresorasController.php',
                type: 'POST',
                data: datosUrl,
                success : function(response){
                    if(response){
	                    console.log(response);
	                    alert("Los datos han sido actualizados correctamente.");
	                    $("#dialogcontent_impresora").dialog("destroy").empty();
                        $("#dialogcontent_impresora").remove();
                        $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                        id: 'contenedorPpal',
                        text: 'Texto por defecto!'
                        }).appendTo('.realBody');
                        if("{viene}" == "normal"){
                            $("#contenedorPpal").load("controlador/ImpresorasController.php");
                        }
                        else if("{viene}" == "stock"){
                            $("#contenedorPpal").load("controlador/StockController.php", {vista: "ver_impresoras"});
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
    });
});

</script>
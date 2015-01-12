<form id="form_impresora_mod_ip">
    <table class="t_impresora">
        <tr>
            <tr type="hidden">
               <td><input type="hidden" name="id_impresora" id="id_impresora" value="{id_impresora}"></td>
            </tr>
        <tr>
            <td>IP<input style="margin-left:10px;" type="text" name="ip" id="ip" value="{ip}"></td>
        </tr>
  </table>
</form>

<script>

$(document).ready(function(){

    $("#form_impresora_mod_ip").on('submit',function(event){
        event.preventDefault();

        var datosUrl = $("#form_impresora_mod_ip").serialize();
        
        datosUrl += "&action=modif_ip";

        $.ajax({
            url: 'controlador/ImpresorasController.php',
            type: 'POST',
            data: datosUrl,
            success: function(response){
                if(response){
                    console.log("success");
                    alert("La descripcion ha sido modificada correctamente.");
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
            }
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
    })

     
});

</script>
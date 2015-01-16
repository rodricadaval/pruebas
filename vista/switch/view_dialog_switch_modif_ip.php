<form id="form_switch_mod_ip" autocomplete="off">
    <table class="t_switch">
        <tr>
            <tr type="hidden">
               <td><input type="hidden" name="id_switch" id="id_switch" value="{id_switch}"></td>
            </tr>
        <tr>
            <td>IP<input style="margin-left:10px;" type="text" name="ip" id="ip" value="{ip}"></td>
        </tr>
  </table>
</form>

<script>

$(document).ready(function(){

    $("#ip").on('focus', function(){
         this.select();
     })

    $("#form_switch_mod_ip").on('submit',function(event){
        event.preventDefault();

        var datosUrl = $("#form_switch_mod_ip").serialize();
        
        datosUrl += "&action=modif_ip";

        $.ajax({
            url: 'controlador/SwitchsController.php',
            type: 'POST',
            data: datosUrl,
            success: function(response){
                if(response){
                    console.log("success");
                    alert("La descripcion ha sido modificada correctamente.");
                    $("#dialogcontent_switch").dialog("destroy").empty();
                    $("#dialogcontent_switch").remove();
                    $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                        id: 'contenedorPpal',
                        text: 'Texto por defecto!'
                        }).appendTo('.realBody');
                    if("{viene}" == "normal"){               
                        $("#contenedorPpal").load("controlador/SwitchsController.php");
                    }
                    else if("{viene}" == "stock"){
                        $("#contenedorPpal").load("controlador/StockController.php", {vista: "ver_switchs"});
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
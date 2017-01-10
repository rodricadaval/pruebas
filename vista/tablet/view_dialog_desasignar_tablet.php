<form id="form_desasignar" autocomplete="off">
    <p>¿Esta seguro que desea desasignar la tablet?</p>
    <p>Volvera al stock.</p>
    <input type="hidden" name="id_tablet" id="id_tablet" value="{id_tablet}">
</form>
<script>

$(document).ready(function(){

    $("#form_desasignar").on('submit',function(event){
        event.preventDefault();

        var datosUrl = $("#form_desasignar").serialize();
        
        datosUrl += "&action=liberar";

        console.log(datosUrl);

        $.ajax({
            url: 'controlador/TabletsController.php',
            type: 'POST',
            data: datosUrl,
            success: function(response){
                console.log(response);
                if(response){
                    console.log("success");
                    alert("La tablet fue a stock.");
                    $("#dialogcontent").dialog("destroy").empty();
                    $("#dialogcontent").remove();
                    $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                        id: 'contenedorPpal',
                        text: 'Texto por defecto!'
                        }).appendTo('.realBody');
                    $("#contenedorPpal").load("controlador/TabletsController.php");
                }
            }
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
    });     
});

</script>
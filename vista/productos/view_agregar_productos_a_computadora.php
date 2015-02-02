<div id="tab_memorias_y_discos" id_cpu="{id_cpu}">
	<table border="0" style="border-top-color:#FFFFFF; margin-left:-20px; width:500px; text-align:center; background-color:#F0F8FF;">
		<tr>
			<td style="width:240px">
				<input type="button" class="btn btn-primary" id="agregar_memorias_computadora" value="+ Memorias">
			</td> 
			<td style="width:240px">  
			    <input type="button" class="btn btn-primary" id="agregar_discos_computadora" id_cpu="{id_cpu}" value="+ Discos">
    		</td>
    	</tr>
    </table>
</div>
<br>
<div id="agregar_productos_a_compu">
<p>Se ha agregado el producto correctamente</p>
<p>¿Desea agregar memorias o discos a la computadora creada?</p>
        <input type="button" class="btn btn-info" id="agregar_memorias_computadora" value="Agregar Memorias">
        <br>
        <input type="button" class="btn btn-info" id="agregar_discos_computadora" value="Agregar Discos">
        <br>
        <input type="hidden" id="id_cpu" value="{id_cpu}">
</div>

<script type="text/javascript">

		var primeraVez = true;

		$("#tab_memorias_y_discos").hide();

		$("#agregar_productos_a_compu").on('click',"#agregar_memorias_computadora",function(){

			if(primeraVez){
				$("#tab_memorias_y_discos").show();
				primeraVez = false;
			}
	    
	        $.post( "controlador/ProductosController.php",
	                {
	                    action : "agregar_memoria_a_computadora"
	                }, function(data){

	                	$("#agregar_productos_a_compu").remove();

	                	jQuery('<div/>', {
                            id: 'agregar_productos_a_compu',
                            text: ''
                        }).appendTo('#agregar_productos_a_computadora');

	                    $("#agregar_productos_a_compu").html(data);
	                }
	        );
		});

		$("#agregar_productos_a_compu").on('click',"#agregar_discos_computadora",function(){

			if(primeraVez){
				$("#tab_memorias_y_discos").show();
				primeraVez = false;
			}
	    
	        $.post( "controlador/ProductosController.php",
	                {
	                    action : "agregar_disco_a_computadora"
	                }, function(data){

	                    $("#agregar_productos_a_compu").remove();

	                	jQuery('<div/>', {
                            id: 'agregar_productos_a_compu',
                            text: ''
                        }).appendTo('#agregar_productos_a_computadora');

	                    $("#agregar_productos_a_compu").html(data);
	                }
	        );
		});

		$("#tab_memorias_y_discos").on('click',"#agregar_memorias_computadora",function(){
        
            $.post( "controlador/ProductosController.php",
                    {
                        action : "agregar_memoria_a_computadora"
                    }, function(data){

                        $("#agregar_productos_a_compu").remove();

                        jQuery('<div/>', {
                            id: 'agregar_productos_a_compu',
                            text: ''
                        }).appendTo('#agregar_productos_a_computadora');

                        $("#agregar_productos_a_compu").html(data);
                    }
            );
        });

	    $("#tab_memorias_y_discos").on('click',"#agregar_discos_computadora",function(){
	        
	            $.post( "controlador/ProductosController.php",
	                    {
	                        action : "agregar_disco_a_computadora"
	                    }, function(data){

	                        $("#agregar_productos_a_compu").remove();

	                        jQuery('<div/>', {
	                            id: 'agregar_productos_a_compu',
	                            text: ''
	                        }).appendTo('#agregar_productos_a_computadora');

	                        $("#agregar_productos_a_compu").html(data);
	                    }
	            );
	    });
</script>	

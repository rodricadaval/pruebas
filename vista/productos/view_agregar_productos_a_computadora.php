<div id="tab_memorias_y_discos" id_cpu="{id_cpu}">
	<table border="0" style="border-top-color:#FFFFFF; margin-left:-20px; width:500px; text-align:center; background-color:#F0F8FF;">
		<tr>
			<td>Memorias</td>

			<td style="width:40px"><a id="agregar_memorias_computadora" class="pointer_mon"><i class="green large plus outline icon" title="Agregar memoria"></i></a></td>

			<td style="width:40px"><a id="asignar_memorias_computadora" class="pointer_mon"><i class="green large edit outline icon" title="Asignar memoria"></i></a></td>

    		<td>Discos</td>

    		<td style="width:40px"><a id="agregar_discos_computadora" class="pointer_mon"><i class="green large plus outline icon" title="Agregar disco"></i></a></td>

			<td style="width:40px"><a id="asignar_discos_computadora" class="pointer_mon"><i class="green large edit outline icon" title="Asignar disco"></i></a></td>

    		<td>Monitores</td>

    		<td style="width:40px"><a id="asignar_monitores_computadora" class="pointer_mon"><i class="green large edit outline icon" title="Asignar monitor"></i></a></td>
    	</tr>
    </table>
    <input type="hidden" id="id_cpu" value="{id_cpu}">
</div>
<br>
<!-- <div id="agregar_productos_a_compu">
<p>Se ha agregado el producto correctamente</p>
<p>¿Desea agregar memorias o discos a la computadora creada?</p>
        <input type="button" class="btn btn-info" id="agregar_memorias_computadora" value="Agregar Memorias">
        <br>
        <input type="button" class="btn btn-info" id="asignar_memorias_computadora" value="Asignar Memorias">
        <br>
        <input type="button" class="btn btn-info" id="agregar_discos_computadora" value="Agregar Discos">
        <br>
        <input type="button" class="btn btn-info" id="asignar_discos_computadora" value="Asignar Discos">
        <br>
        <input type="hidden" id="id_cpu" value="{id_cpu}">
</div> -->

<script type="text/javascript">

		var primeraVez = true;

		//$("#tab_memorias_y_discos").hide();

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

		$("#agregar_productos_a_compu").on('click',"#asignar_memorias_computadora",function(){

			if(primeraVez){
				console.log("Voy a asignar memorias");
				$("#tab_memorias_y_discos").show();
				primeraVez = false;
			}
	        console.log($_POST['computadora']);
	            $.post( "controlador/ProductosController.php",
	                    {
	                        action : "asignar_memoria_a_computadora"
	                    }, function(data){

	                        $("#asignar_productos_a_compu").remove();

	                        jQuery('<div/>', {
	                            id: 'asignar_productos_a_compu',
	                            text: ''
	                        }).appendTo('#asignar_productos_a_computadora');

	                        $("#asignar_productos_a_compu").html(data);
	                    }
	            );
	    });

	    $("#agregar_productos_a_compu").on('click',"#asignar_discos_computadora",function(){

	    	if(primeraVez){
	    		console.log("Voy a asignar discos");
				$("#tab_memorias_y_discos").show();
				primeraVez = false;
			}
	        
	            $.post( "controlador/ProductosController.php",
	                    {
	                        action : "asignar_disco_a_computadora"
	                    }, function(data){

	                        $("#asignar_productos_a_compu").remove();

	                        jQuery('<div/>', {
	                            id: 'asignar_productos_a_compu',
	                            text: ''
	                        }).appendTo('#asignar_productos_a_computadora');

	                        $("#asignar_productos_a_compu").html(data);
	                    }
	            );
	    });

		$("#tab_memorias_y_discos").on('click',"#agregar_memorias_computadora",function(){


	    	console.log("asdddas");
        
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

	    $("#tab_memorias_y_discos").on('click',"#asignar_memorias_computadora",function(){

	    		console.log("asignar_memorias_computadora");

	            $.post( "controlador/ProductosController.php",
	                    {
	                        action : "asignar_memoria_a_computadora"
	                    }, function(data){
	                    	console.log("En asignar me llega esta data");
	                    	console.log(data);
	                        $("#agregar_productos_a_compu").remove();

	                        jQuery('<div/>', {
	                            id: 'agregar_productos_a_compu',
	                            text: ''
	                        }).appendTo('#agregar_productos_a_computadora');

	                        $("#agregar_productos_a_compu").html(data);

	                    }
	            );
	    });

	    $("#tab_memorias_y_discos").on('click',"#asignar_discos_computadora",function(){
	        
	            $.post( "controlador/ProductosController.php",
	                    {
	                        action : "asignar_disco_a_computadora"
	                    }, function(data){

	                        $("#asignar_productos_a_compu").remove();

	                        jQuery('<div/>', {
	                            id: 'agregar_productos_a_compu',
	                            text: ''
	                        }).appendTo('#asignar_productos_a_computadora');

	                        $("#asignar_productos_a_compu").html(data);
	                    }
	            );
	    });
</script>	

<table style="text-align:center" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable"></table>
<div id="dialogcontent_monitor" title="Modificar Asignacion Monitor">
<p>All form fields are required.</p>
</div>
 <script src="lib/multiple-usuarios.js" type="text/javascript"></script>
<script type="text/javascript">

	$(document).ready(function(event){
		$.ajax({
			url : 'metodos_ajax.php',
			method: 'post',
			data:{ clase: '{TABLA}',
				   metodo: 'listarCorrecto',
				   tipo: 'json'},
			dataType: 'json',
			success : function(data){
				$("#dataTable").dataTable({
   			 		"destroy" : true,
   			 		"bJQueryUI" : true,
					"aaData" : data,
					"aoColumns" :[
						{ "sTitle" : "ID" , "mData" : "id_monitor"},
						{ "sTitle" : "Nro de Serie" , "mData" : "num_serie"},
						{ "sTitle" : "Marca" , "mData" : "marca"},
						{ "sTitle" : "Modelo" , "mData" : "modelo"},
						{ "sTitle" : "Pulgadas" , "mData" : "pulgadas"},
						{ "sTitle" : "Sector" , "mData" : "sector"},
						{ "sTitle" : "Usuario" , "mData" : "usuario"},
						{ "sTitle" : "Cpu" , "mData" : "cpu_serie"},
						{ "sTitle": "Action", "mData" : "m" , "sDefaultContent":
										'<a class="ventana_area " href="">Modificar</a>'}
						]
    			})
			}

		});
	});

	$("#contenedorPpal").on('click' , '#modificar_monitor' , function(){

		console.log($(this).attr("id_monitor"));
		var id_monitor = $(this).attr("id_monitor");
		$.post( "vista/dialog_content.php",
			{
				TablaPpal : "Monitores",
				ID : id_monitor,
				select_Usuarios : "Usuarios",	//Clase de la cual quiero obtener el select
				select_Areas : "Areas", //Clase de la cual quiero sacar el select
				queSos : "monitor" //a quien le voy a generar la vista
			}, function(data){
				$("#dialogcontent_monitor").html(data);
				$("#dialogcontent_monitor").dialog({
											show: {
											effect: "blind",
											duration: 1000,
											modal:true
											},
											hide: {
											effect: "explode",
											duration: 200
											},
											width : 400,
											close : function(){
												$(this).dialog("destroy").empty();
											}
				});
			}
		);
	});

	$("#contenedorPpal").on('click' , '#eliminar_monitor' , function(){

		console.log($(this).attr("id_monitor"));
		var id_monitor = $(this).attr("id_monitor");
		datosUrl = "id_monitor="+id_monitor+"&action=eliminar";
		console.log(datosUrl);

		$.ajax({
			url: 'controlador/MonitoresController.php',
			type: 'POST',
			data: datosUrl,
		})
		.done(function(response) {
			if(response){
				console.log("success");
				alert("El monitor ha sido eliminado correctamente.");
				$("#contenedorPpal").load("controlador/MonitoresController.php");
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});

	});

</script>
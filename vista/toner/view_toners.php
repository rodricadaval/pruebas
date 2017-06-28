<div class="ui one column grid">
	<div class="column" id="tabla">
		<div class="ui raised segment">
			<a class="ui teal ribbon label">{TABLA}</a>
			<table  cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable"></table>
		</div>
	</div>
</div>

<script type="text/javascript">

	$(document).ready(function(event){

		cargando ();

		$.ajax({
			url : 'metodos_ajax.php',
			method: 'post',
			data:{ clase: '{TABLA}',
			metodo: 'listarCorrecto',
			tipo: 'json'},
			dataType: 'json',
			success : function(data){
				console.log(data);
				$.get('logueo/check_priority.php', function(permisos) {

					quitar_cargando ();

					if( permisos == 1 || permisos == 3) {
						$("#dataTable").dataTable({
							"destroy" : true,
							"aaData" : data,
							"bAutoWidth": false,
							"iDisplayLength": 100,
							"aoColumns" :[
							{ "sTitle" : "Impresora" , "mData" : "impresora"},
							{ "sTitle" : "Area" , "mData" : "area"},
							{ "sTitle" : "Cantidad" , "mData" : "cantidad"},
							{ "sTitle" : "Descripcion" , "mData" : "descripcion"},
							{ "sTitle" : "Fecha" , "mData": "fecha"},
							{ "sTitle": "Action", "mData" : "m","sWidth": "26%","sDefaultContent":
							'<a class="ventana_area " href="">Modificar</a>'}
							]
						});
					}
					else if (permisos == 2) {
						$("#dataTable").dataTable({
							"destroy" : true,
							"aaData" : data,
							"aoColumns" :[
							{ "sTitle" : "Impresora" , "mData" : "impresora"},
							{ "sTitle" : "Area" , "mData" : "area"},
							{ "sTitle" : "Cantidad" , "mData" : "cantidad"},
							{ "sTitle" : "Descripcion" , "mData" : "descripcion"},
							{ "sTitle" : "Fecha" , "mData": "fecha"}
						]
					});
					}
					else { window.location.href = "logueo/login.php";}
				});
			},
			error : function (data) {
				console.log(data);
				console.log("error");
			}

		});
	});	

	$("#contenedorPpal").on('click' , '#ver_area' , function(){

		console.log("Entro a cambiar numero de serie");
		console.log("id_toner: "+$(this).attr("id_toner"));
		var id_toner = $(this).attr("id_toner");

		$.post( "controlador/TabletsController.php",
		{
			ID : id_toner,
			action : "asignar_area"
		}, function(data){
			jQuery('<div/>', {
				id: 'dialogcontent',
				text: 'Texto por defecto!'
			}).appendTo('#contenedorPpal');
			$("#dialogcontent").html(data);
			$("#dialogcontent").dialog({
				title: "Area",
				show: {
					effect: "explode",
					duration: 200,
					modal:true,
				},
				hide: {
					effect: "explode",
					duration: 200
				},
				width : 360,
				height: 290,
				close : function(){
					$(this).dialog("destroy").empty();
					$("#dialogcontent").remove();
				},
				buttons :
				{
					"Cancelar" : function () {
						$(this).dialog("destroy").empty();
						$("#dialogcontent").remove();
					},
					"Guardar" : function(){
						$("#form_num_serie_usuario").submit();
					}
				}
			});
		}
		);
	});

	$("#contenedorPpal").on('click' , '#ver_descripcion' , function(){

		console.log("Entro a agregar descripcion");
		console.log("id_toner: "+$(this).attr("id_toner"));
		var id_toner = $(this).attr("id_toner");

		$.post( "controlador/TonersController.php",
		{
			ID : id_toner,
			action : "descripcion",
		}, function(data){
			jQuery('<div/>', {
				id: 'dialogcontent',
				text: 'Texto por defecto!'
			}).appendTo('#contenedorPpal');
			$("#dialogcontent").html(data);
			$("#dialogcontent").dialog({
				title: "Descripcion",
				show: {
					effect: "explode",
					duration: 200,
					modal:true,
				},
				hide: {
					effect: "explode",
					duration: 200
				},
				width : 360,
				height: 290,
				close : function(){
					$(this).dialog("destroy").empty();
					$("#dialogcontent").remove();
				},
				buttons :
				{
					"Cancelar" : function () {
						$(this).dialog("destroy").empty();
						$("#dialogcontent").remove();
					},
					"Guardar" : function(){
						$("#form_detalle_agregar_desc").submit();
						location.reload();
					}
				}
			});
		}
		);
	});

	$("#contenedorPpal").on('click' , '#generar_memorandum' , function(event){
		event.preventDefault();

		console.log("Entro a seleccionar los productos para el memorandum de la toner");
		console.log("id_toner: "+$(this).attr("id_toner"));
		var id_toner = $(this).attr("id_toner");

		window.open("controlador/TONERPDF.php?"+"id_toner="+id_toner);
	});

	$("#contenedorPpal").on('click' , '#liberar' , function(){

		console.log("Entro a desasignar toner");
		console.log("id_toner: "+$(this).attr("id_toner"));
		var id_toner = $(this).attr("id_toner");

		$.post( "controlador/TabletsController.php",
		{
			ID : id_toner,
			action : "liberar"
		}, function(data){
			console.log(data);
			jQuery('<div/>', {
				id: 'dialogcontent',
				text: 'Texto por defecto!'
			}).appendTo('#contenedorPpal');
			$("#dialogcontent").html(data);
			$("#dialogcontent").dialog({
				show: {
					effect: "explode",
					duration: 200,
					modal:true,
				},
				hide: {
					effect: "explode",
					duration: 200
				},
				width : 360,
				height: 290,
				close : function(){
					$(this).dialog("destroy").empty();
					$("#dialogcontent").remove();
				},
				buttons :
				{
					"Cancelar" : function () {
						$(this).dialog("destroy").empty();
						$("#dialogcontent").remove();
					},
					"Aceptar" : function(){
						$("#form_desasignar").submit();
					}
				}
			});
		}
		);
	});

	$("#contenedorPpal").on('click','#baja',function(event){
		event.preventDefault();
		console.log("Entro a ver detalles de la toner");
		console.log("id_toner: "+$(this).attr("id_toner"));
		var id_toner = $(this).attr("id_toner");

		$.post( "controlador/TabletsController.php",
		{
			ID : id_toner,
			action : "baja"
		}, function(data){
			console.log(data);
			jQuery('<div/>', {
				id: 'dialogcontent',
				text: ''
			}).appendTo('#contenedorPpal');
			$("#dialogcontent").html(data);
			$("#dialogcontent").dialog({
				title: "Componentes de la toner: ",
				show: {
					effect: "explode",
					duration: 200,
					modal:true,
				},
				hide: {
					effect: "explode",
					duration: 200
				},
				width : 360,
				height: 290,
				close : function(){
					$(this).dialog("destroy");
					$("#dialogcontent").remove();
				},
				buttons :
				{
					"Aceptar" : function () {
						$(this).dialog("destroy");
						$("#dialogcontent").remove();
					}
				}
			});
		}
		);
	});

</script>
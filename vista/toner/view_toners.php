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

		var id_toner = $(this).attr("id_toner");

		$.post( "controlador/TonersController.php",
		{
			ID : id_toner,
			action : "mostrar_area"
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
						$("#form_toner_modificar_area").submit();
					}
				}
			});
		}
		);
	});

	$("#contenedorPpal").on('click' , '#ver_descripcion' , function(){

		var id_toner = $(this).attr("id_toner");

		$.post( "controlador/TonersController.php",
		{
			ID : id_toner,
			action : "mostrar_descripcion",
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
					}
				}
			});
		}
		);
	});

	$("#contenedorPpal").on('click' , '#generar_memorandum' , function(event){
		event.preventDefault();
		var id_toner = $(this).attr("id_toner");
		window.open("controlador/TONERAPDF.php?"+"id_toner="+id_toner);
	});

	$("#contenedorPpal").on('click' , '#ver_liberar' , function(){

		var id_toner = $(this).attr("id_toner");

		$.post( "controlador/TonersController.php",
		{
			ID : id_toner,
			action : "mostrar_liberar"
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
						$("#form_liberar_toner").submit();
					}
				}
			});
		}
		);
	});

	$("#contenedorPpal").on('click','#ver_baja',function(event){
		event.preventDefault();
		var id_toner = $(this).attr("id_toner");

		$.post( "controlador/TonersController.php",
		{
			ID : id_toner,
			action : "mostrar_baja"
		}, function(data){
			jQuery('<div/>', {
				id: 'dialogcontent',
				text: ''
			}).appendTo('#contenedorPpal');
			$("#dialogcontent").html(data);
			$("#dialogcontent").dialog({
				title: "Dar de baja toners: ",
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
						$("#form_dar_baja").submit();
					}
				}
			});
		}
		);
	});

</script>
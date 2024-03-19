feather.replace()

$(document).ready(function () {
	document.title = "Reporte";
	// Inicializar DataTable
	const table = $("#example").DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
		},
		dom: '<"items-datatable"Bf><"clear">lirtp',
		paging: true,
		autoWidth: true,
		responsive: true,
		buttons: [
			{
				extend: 'colvis',
				text: '<img src="../resources/img/disguiseWhite.png" width="25" height="25" />',
				titleAttr: 'Ocutar columna',
				className: 'btn-info'
			},
			{
				extend: 'excelHtml5',
				text: '<img src="../resources/img/excelWhite.png" width="25" height="25" />',
				titleAttr: 'Exportar excel',
				className: 'btn-success',
				excelStyles:{
					cells: 'sD',
					
				}
			},

			{
				extend: 'print',
				text: '<img src="../resources/img/pdfWithe.png" width="25" height="25" />',
				titleAttr: 'Imprimir',
				className: 'btn-danger'
			}
		]
	});



	// Barra de busqueda.
	$("#example thead").on("keyup", "input", function () {
		table.column($(this).parent().index())
			.search(this.value)
			.draw();
	});
});

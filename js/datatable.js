// DataTable inicialización
$(document).ready(function () {
	document.title = "Reporte - Informes Moodle";
	
	// Verificar si existe la tabla
	if ($("#example").length === 0) return;
	
	// Inicializar DataTable
	const table = $("#example").DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
		},
		dom: '<"datatable-controls"Bf><"clear">lirtp',
		paging: true,
		autoWidth: true,
		responsive: true,
		pageLength: 25,
		buttons: [
			{
				extend: 'colvis',
				text: '👁️ Columnas',
				titleAttr: 'Mostrar/Ocultar columnas',
				className: 'btn btn-secondary'
			},
			{
				extend: 'excelHtml5',
				text: '📊 Excel',
				titleAttr: 'Exportar a Excel',
				className: 'btn',
				excelStyles: {
					cells: 'sD',
				}
			},
			{
				extend: 'csvHtml5',
				text: '📄 CSV',
				titleAttr: 'Exportar a CSV',
				className: 'btn btn-secondary'
			},
			{
				extend: 'print',
				text: '🖨️ Imprimir',
				titleAttr: 'Imprimir reporte',
				className: 'btn btn-ghost'
			}
		]
	});

	// Barra de búsqueda por columna
	$("#example thead").on("keyup", "input", function () {
		table.column($(this).parent().index())
			.search(this.value)
			.draw();
	});
});

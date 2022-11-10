feather.replace()

$(document).ready(function () {
    // const date = toLocaleDateString();
	document.title = "Reporte";
    // DataTable 
	const table = $("#example").DataTable({
        "language": {
			"url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
		},
		dom: '<"dt-buttons"Bf><"clear">lirtp',
		paging: true,
		autoWidth: true,
		buttons: [
			"colvis",
			"excelHtml5",
			"print"
		],
		initComplete: function (settings, json) {
            const footer = $("#example tfoot tr");
			$("#example thead").append(footer);
		}
	});
    
	// Barra de busqueda.
	$("#example thead").on("keyup", "input", function () {
        table.column($(this).parent().index())
        .search(this.value)
        .draw();
	});
});
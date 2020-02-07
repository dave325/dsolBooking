/**
 * Javascript code needed to build tables in WordPress with jQuery DataTables.
 *
 * @author  Peter Schulz
 * @since   1.0.0
 */

var wpda_details = 'Row details'; // Default label value.

wpda_script_url = document.currentScript.src; // Save script url.
jQuery( document.currentScript ).ready(
	function() {
		// Check for translations.
		if (jQuery.url( wpda_script_url ).data.param.query.details) {
			wpda_details = jQuery.url( wpda_script_url ).data.param.query.details;
		}
	}
);

function wpda_datatables_ajax_call(
	columnsvar, database, table_name, columns,
	responsive, responsive_popup_title, responsive_type, responsive_icon,
	pub_format, language, sql_where, sql_orderby,
	table_options_searching, table_options_ordering, table_options_paging, table_options_advanced,
	pub_id
) {
	pub_format = pub_format || '';

	/*
	* display possible values:
	* childrow = user toggled
	* childrowimmediate = show
	* modal = show details in modal window
	*/

	/*
	* type possible values:
	* column = no control element
	* inline = show control element
	*/

	var responsive_control_type = "inline";
	if (responsive_icon !== "yes") {
		responsive_control_type = "column";
	}

	var childrow = {
		details: {
			display: jQuery.fn.dataTable.Responsive.display.childRow,
			type: responsive_control_type
		}
	};

	var childrowimmediate = {
		details: {
			display: jQuery.fn.dataTable.Responsive.display.childRowImmediate,
			type: responsive_control_type
		}
	};

	var modal = {
		details: {
			display: jQuery.fn.dataTable.Responsive.display.modal(
				{
					header: function (row) {
						return responsive_popup_title == '' ? wpda_details : responsive_popup_title;
					}
				}
			),
			renderer: function (api, rowIdx, columns) {
				var data = jQuery.map(
					columns, function (col, i) {
							return '<tr>' +
							'<td>' + col.title + '</td>' +
							'<td><strong>' + col.data + '</strong></td>' +
							'</tr>';
					}
				).join( '' );
				var datatable = '<table class="display dataTable">' + data + '</table>';
				var footer    = '<tr><td style="padding-top:10px; text-align: center"><div>' +
					'<input type="button" value="Close" class="button dtr-modal-close" onclick="jQuery(\'.dtr-modal\').remove()"/>' +
					'</div></td></tr>';
				var table     = '<tr><td>' + datatable + '</td></tr>' + footer;

				return jQuery( '<table/>' ).append( table );
			},
			type: responsive_control_type
		}

	};

	var responsive_value = false;
	if (responsive === 'yes') {
		switch (responsive_type) {
			case "modal":
				responsive_value = modal;
				break;
			case "expanded":
				responsive_value = childrowimmediate;
				break;
			default:
				/* collaped */
				responsive_value = childrow;
		}
	}

	if (language===undefined) {
		language = 'English';
	}

	orderby = [];
	if ( sql_orderby != '') {
		sql_orderby.split("|").forEach(function (item) {
			orderby_array = item.split(",");
			orderby.push(orderby_array);
		});
	}

	var jQueryDataTablesUserOptions = {
		searching: table_options_searching,
		ordering: table_options_ordering,
		paging: table_options_paging
	};
	if (!table_options_paging) {
		jQueryDataTablesUserOptions.serverSide = false;
	}

	var jQueryDataTablesDefaultOptions = {
		responsive: responsive_value,
		processing: true,
		serverSide: true,
		stateSave: true,
		bAutoWidth: false,
		columnDefs: columnsvar,
		order: orderby,
		ajax: {
			url: wpda_ajax.wpda_ajaxurl,
			data: {
				action: "wpda_datatables",
				database: database,
				table_name: table_name,
				columns: columns,
				pub_format: pub_format,
				sql_where: sql_where
			}
		},
		language: {
			url: "https://cdn.datatables.net/plug-ins/1.10.20/i18n/" + language + ".json"
		}
	};

	var jQueryDataTablesOptions = Object.assign(jQueryDataTablesDefaultOptions, jQueryDataTablesUserOptions);

	if (table_options_advanced!='') {
		try {
			var advancedOptions = JSON.parse(jQuery("<textarea/>").html(table_options_advanced).text());
			for (var prop in advancedOptions) {
				if (typeof advancedOptions[prop]=='string') {
					if (advancedOptions[prop].substr(0,8)=='function') {
						fnc = advancedOptions[prop];
						delete advancedOptions[prop];
						var f = new Function("return " + fnc);
						advancedOptions[prop] = f();
					}
				}
			}
			jQueryDataTablesOptions = Object.assign(jQueryDataTablesOptions, advancedOptions);
		}
		catch(err) {
			console.log("WP Data Access ERROR: Invalid table options (advanced)"+table_name);
		}
	}

	jQuery( "#" + table_name + pub_id ).DataTable(jQueryDataTablesOptions);

}

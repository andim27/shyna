var admin_base_url;
var ajax_admin_path;
var image_loading;
var symbols = new Array(' ', '/', '?', '.', ',', '>', '<', '=', '-', '_', ')', '(', '*', '&', '^', '%', '$', '#', '@', '!', '~', '`', '|', "'");		 

function set_admin_base_url(url, handler){
	admin_base_url = url;
	ajax_admin_path = admin_base_url + 'admin/' + handler+ "/ajax_actions";
	image_loading = '<img id="loading" border="0" src="' + admin_base_url + 'images/add-note-loader.gif" alt="loading..." />';
}

function actions(option_name) {
	var option;
	
	if(option_name == undefined || option_name == '') {
		option = $('#actions option:selected').val();
	} else {
		option = option_name;
	}
	
	var checked = new Array();
	var i = 0;
	
	$('div[id^="row_"]').each(function () {		
		var record = $(this).attr('id').split("_");
		var record_id = record[1];
				
		if($('#ch_'+ record_id).attr('checked') == 1) {		
			checked[i] = record_id;
		}		
		i++;
	});
	var recordObj = new Object();
	recordObj.brand_list_name = $('#brands_list option:selected').val();
	recordObj.width_list_name = $('#width_list option:selected').val();
	recordObj.height_list_name = $('#height_list option:selected').val();
	recordObj.radius_list_name = $('#radius_list option:selected').val();
	recordObj.load_list_name = $('#load_list option:selected').val();
	recordObj.speed_list_name = $('#speed_list option:selected').val();
	
	checked['column_names'] = recordObj;
	checked['delete_tmp_table'] = parseInt($("input[name='delete_tmp_table']:checked").val());
	
	switch(option) {		
		case "show_all":
			$('div[id^="row_"]').each(function () {
				$(this).show();
			});			
		break;
		
		case "show_founded":
		case "show_not_founded":
			$('div[id^="row_"]').each(function () {
				var brand_val = $('#' + $(this).attr('id')).children('.colBrandName').html();
				var width_val = $('#' + $(this).attr('id')).children('.colWidth').html();
				var profile_val = $('#' + $(this).attr('id')).children('.colProfile').html();
				var diameter_val = $('#' + $(this).attr('id')).children('.colDiameter').html();
				var model_val = $('#' + $(this).attr('id')).children('.colModel').html();
				var loadIndex_val = $('#' + $(this).attr('id')).children('.colLoadIndex').html();
				var speedIndex_val = $('#' + $(this).attr('id')).children('.colSpeedIndex').html();
				
				if(option == 'show_founded') {
					if(brand_val == '' || width_val == '' || profile_val == '' || diameter_val == '' || model_val == '' || 
																					loadIndex_val == '' || speedIndex_val == '')						
						$(this).hide();
					else
						$(this).show();
				} 
				else if(option == 'show_not_founded') {
					if(brand_val == '' || width_val == '' || profile_val == '' || diameter_val == '' || model_val == '' || 
																					loadIndex_val == '' || speedIndex_val == '')
						$(this).show();
					else
						$(this).hide();
				}
			});
		break;
		
		case "apply_founded":
			if(confirm('Вы уверены, что хотите занести найденные записи в таблицу прайсов?')) {					
				$('div[id^="row_"]').each(function () {
					var brand_val = $('#' + $(this).attr('id')).children('.colBrandName').html();
					var width_val = $('#' + $(this).attr('id')).children('.colWidth').html();
					var profile_val = $('#' + $(this).attr('id')).children('.colProfile').html();
					var diameter_val = $('#' + $(this).attr('id')).children('.colDiameter').html();
					var model_val = $('#' + $(this).attr('id')).children('.colModel').html();
					var loadIndex_val = $('#' + $(this).attr('id')).children('.colLoadIndex').html();
					var speedIndex_val = $('#' + $(this).attr('id')).children('.colSpeedIndex').html();
					
					if(brand_val != '' && width_val != '' && profile_val != '' && diameter_val != '' && model_val != '' && 
																				loadIndex_val != '' && speedIndex_val != '') {
						var record = $(this).attr('id').split("_");
						var record_id = record[1];
						$('#row_'+record_id).remove();
					}
				});
				action_option(serialize(checked), 'apply');
			}
		break;
		
		case "delete_all":
			delete_price();
		break;
		
		default:
			actions('show_all');
		break;
	}
}
/****************   AJAX FUNCTIONS   *********************/
function action_option(elements, option) {	
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "html",
		data: {
			'action':'action_option',
			'option': option,
			'elements': elements,
			'vendor_id': $('#vendor_id').val()
		},
		beforeSend: function() {
			$("#actions :first").attr("selected", "selected");
			actions();
		},
		success: function(data){
			alert('Записи были успешно внесены');
			get_price_rows(1);			
		},
		error: function(data) {}
	});
}
function get_price_rows(page) {
	var is_parsed = $('#is_parsed').val();
	var vendor_id = parseInt($('#vendor_id').val());
	
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "json",
		data: {
			'action':'get_price_rows',
			'page': page,
			'is_parsed': is_parsed,
			'vendor_id': vendor_id
		},
		beforeSend: function() {
			$('.paginator').html(image_loading);
		},
		success: function(data){
			$("#actions :first").attr("selected", "selected");
			actions();
			
			if(data.tmp_data == '' && data.diff_data == '') {
				window.location.href = admin_base_url + 'admin/vendors/profile/' + vendor_id;
				
			} else {
				$('#priceTableBody').html(data.tmp_data);
				if(is_parsed == true) {
					$('#priceDiffTableBody').html(data.diff_data);
				}
				$('.paginator').html(data.paginate);
				$('#count_not_null').html(data.statistics.count_not_null);
				$('#count_null').html(data.statistics.count_null);
			}
		},
		error: function(data) {}
	});
}
function delete_price() {
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "html",
		data: {
			'action':'delete_price',
			'vendor_id': $('#vendor_id').val()
		},
		beforeSend: function() {
			$("#actions :first").attr("selected", "selected");
			actions();
		},
		success: function(data){
			alert('Данные были успешно удалены');
			get_price_rows(1);			
		},
		error: function(data) {}
	});
}
/****************** DICTIONARY *****************/
function get_dicts(div1, div2) {
	var type_id = $('#' + div1 +' option:selected').val();
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "json",
		data: {
			'action': 'get_dicts',
			'type1': div1,
			'type2': div2,
			'type_id': type_id
		},
		beforeSend: function() {
			$('.elementLoading').show();
		},
		success: function(data){
			$('.elementLoading').hide();			
			if(type_id == 'all') {
				// init all need selects
				$('#' + div1).empty();				
				$('#' + div2).empty();
				$('#' + div1 + '_syn').empty();
				$('#' + div2 + '_syn').empty();
				
				// set type1 and clear synonyms of type1
				var options_str = '<option value="all">показать все</option>';
				if(data.type1_obj !== null && data.type1_obj.length != null) {
					var i;
					for(i=0; i < data.type1_obj.length; i++) {
						options_str += '<option value=' + data.type1_obj[i].id + '>' + data.type1_obj[i].name + '</option>';
					}
				} else {
					options_str = '<option value="0">-------</option><option value="all">показать все</option>';
				}
				$('#' + div1).html(options_str);
				
				// set type2 and clear synonyms of type2
				var options_str = '<option value="all">показать все</option>';
				if(data.type2_obj !== null && data.type2_obj.length != null) {
					var i;
					for(i=0; i < data.type2_obj.length; i++) {
						options_str += '<option value=' + data.type2_obj[i].id + '>' + data.type2_obj[i].name + '</option>';
					}
				} else {
					options_str = '<option value="0">-------</option><option value="all">показать все</option>';
				}
				$('#' + div2).html(options_str);				
			} 
			else {
				// init all need selects
				$('#' + div1 + '_syn').empty();
				$('#' + div2 + '_syn').empty();
				$('#' + div2).empty();				
				
				// set synonyms of type1
				var options_str = '';
				if(data.type1_syns !== null && data.type1_syns.length != null) {					
					var i;
					for(i=0; i < data.type1_syns.length; i++) {
						options_str += '<option value=' + data.type1_syns[i].id + '>' + data.type1_syns[i].name + '</option>';
					}					
				}
				$('#' + div1 + '_syn').html(options_str);
				
				// set type2
				var options_str = '<option value="all">показать все</option>';						
				if(data.type2_obj !== null && data.type2_obj.length > 0) {
					var i;
					var selected;
					for(i=0; i < data.type2_obj.length; i++) {
						selected = (i==0) ? 'selected' : '';
						options_str += '<option ' + selected + ' value=' + data.type2_obj[i].id + '>' + data.type2_obj[i].name + '</option>';
					}
				} else {
					options_str = '<option value="0">-------</option><option value="all">показать все</option>';
				}
				$('#' + div2).html(options_str);
	
				// set synonyms of type2
				var options_str = '';
				if(data.type2_syns !== null && data.type2_syns.length > 0) {
					var i;
					for(i=0; i < data.type2_syns.length; i++) {
						options_str += '<option value=' + data.type2_syns[i].id + '>' + data.type2_syns[i].name + '</option>';
					}
				}
				$('#' + div2 + '_syn').html(options_str);
			}			
		},
		error: function(data) {}
	});
}
function add_brand() {
	var type = 'brands';
	
	var keyword = $('#' + type +'_keyword').val();
	if(keyword == '') {
		alert('Введите значение!'); return;
	}	
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "json",
		data: {
			'action': 'add_type',
			'type': type,
			'keyword': keyword
		},
		beforeSend: function() {
			$('.elementLoading').show();
		},
		success: function(data) {
			$('.elementLoading').hide();
			
			if(data.type_exists == true) {
				alert('Такое имя уже существует! Введите другое имя');
				$('#' + type + '_keyword').val('');
			}
			if(data.type_exists == false) {
				$('#' + type + '_syn').empty();
				$('#models').empty();
				$('#models_syn').empty();
				
				var options_str = '';
				options_str += '<option value=' + data.type_id + '>' + keyword + '</option>';
				$('#' + type).prepend(options_str);
				$('#' + type + ' option:[value=' + data.type_id + ']').attr('selected', 'selected');				
				
				var options_str = '';
				options_str += '<option value=' + data.type_syn_id + '>' + keyword + '</option>';				
				$('#' + type + '_syn').prepend(options_str);
				$('#' + type + '_syn option:[value=' + data.type_syn_id + ']').attr('selected', 'selected');
				
				$('#' + type + '_keyword').val('');
				$('#' + type + '_new').hide();
			}
		},
		error: function(data) {
			$('.elementLoading').hide();			
		}
	});
}
function add_model() {
	var type = 'models';
	
	var keyword = $('#' + type +'_keyword').val();
	if(keyword == '') {
		alert('Введите значение!'); return;
	}	
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "json",
		data: {
			'action': 'add_type',
			'type': type,
			'keyword': keyword,
			'brand_id': $('#brands option:selected').val(),					// if adding a model
			'model_season': $('#model_season option:selected').val(),		// if adding a model
			'model_car': $('#model_car option:selected').val()				// if adding a model
		},
		beforeSend: function() {
			$('.elementLoading').show();
		},
		success: function(data) {
			$('.elementLoading').hide();
			
			if(data.type_exists == true) {
				alert('Такое имя уже существует!Введите другое имя');
			}
			if(data.type_exists == false) {
				var options_str = '';
				options_str += '<option value=' + data.type_id + '>' + keyword + '</option>';
				$('#' + type).prepend(options_str);
				$('#' + type + ' option:[value=' + data.type_id + ']').attr('selected', 'selected');
				$('#' + type + '_syn').empty();
				
				var options_str = '';
				options_str += '<option value=' + data.type_syn_id + '>' + keyword + '</option>';
				$('#' + type + '_syn').prepend(options_str);
				$('#' + type + '_syn option:[value=' + data.type_syn_id + ']').attr('selected', 'selected');
				
				$('#' + type + '_keyword').val('');
				$('#' + type + '_new').hide();
			}
		},
		error: function(data) {
			$('.elementLoading').hide();
			
		}
	});
}
function add_synonym(synonym_type) {
	var keyword = $('#' + synonym_type +'_syn_keyword').val();
	if(keyword == '') {
		alert('Введите значение!'); return;
	}
	
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "json",
		data: {
			'action': 'add_synonym',
			'type': synonym_type,
			'keyword': keyword,
			'type_id': $('#' + synonym_type +' option:selected').val()
		},
		beforeSend: function() {
			$('.elementLoading').show();
		},
		success: function(data) {
			$('.elementLoading').hide();
			$('#' + synonym_type + '_syn_keyword').val('');
			$('#' + synonym_type + '_syn_new').hide();
			
			keyword = str_replace(symbols, '', keyword);			
			
			var options_str = '';
			options_str += '<option value=' + data.type_syn_id + '>' + keyword + '</option>';
			$('#' + synonym_type + '_syn').prepend(options_str);
			$('#' + synonym_type + '_syn option:[value=' + data.type_syn_id + ']').attr('selected', 'selected');			
		},
		error: function(data) {
			$('.elementLoading').hide();
		}
	});
}
function edit_type(type) {
  	var type_id = $('#' + type +' option:selected').val();
	if(type_id == 'all' || type_id == 'undefined') {
		alert('Вам необходимо выбрать элемент'); return;
	}
    editFormSet(type);
    actionForm(type+'_edit', '');
}
function delete_type(type) {
	var type_id = $('#' + type +' option:selected').val();
	if(type_id == 'all' || type_id == 'undefined') {
		alert('Вам необходимо выбрать элемент'); return;
	}
	
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "json",
		data: {
			'action': 'delete_type',
			'type': type,
			'type_id': type_id
		},
		beforeSend: function() {
			$('.elementLoading').show();
		},
		success: function(data){
			$('.elementLoading').hide();			
			$('#' + type +' option:selected').remove();
			if(data.synonyms !== null && data.synonyms.length > 0) {
				for(var i=0; i < data.synonyms.length; i++) {
					$('#' + type + '_syn option:[value=' + data.synonyms[i].id + ']').remove();
				}
			}
		},
		error: function(data) {}
	});
}
function edit_synonym(type) {
  	type += '_syn';
	var type_id = $('#' + type +' option:selected').val();
	if(type_id == 'all' || type_id == 'undefined') {
		alert('Вам необходимо выбрать элемент'); return;
	}
    editFormSet(type);
    actionForm(type+'_edit', '');
}
function delete_synonym(type) {
	type += '_syn';
	var type_id = $('#' + type +' option:selected').val();
	if(type_id == 'all' || type_id == 'undefined') {
		alert('Вам необходимо выбрать элемент'); return;
	}
	
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "html",
		data: {
			'action': 'delete_synonym',
			'type': type,
			'type_id': type_id
		},
		beforeSend: function() {
		},
		success: function(data){
			$('#' + type +' option:selected').remove();
		},
		error: function(data) {}
	});
}
/**************** VENDORS  ************************/
function edit_vendor(vendor_id){
	if(vendor_id == '' || vendor_id == undefined) {
		alert('Номер поставщика не опредлен!');
		return false;
	}
	var vendor_name = $('#edit_vendor_name').val();
	if(vendor_name == '' || vendor_name == undefined) {
		alert('Поле для имени поставщика не может быть пустым!');
		return false;
	}	
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "json",
		data: {
			'action': 'update_vendor',
			'vendor_id': vendor_id,
			'vendor_name': vendor_name,
			'vendor_name_short': $('#edit_vendor_name_short').val(),
			'vendor_city': $('#edit_vendor_city').val(),
			'vendor_city_short': $('#edit_vendor_city_short').val(),
			'vendor_phone': $('#edit_vendor_phone').val(),
			'vendor_fax': $('#edit_vendor_fax').val(),
			'vendor_email': $('#edit_vendor_email').val(),
			'vendor_www': $('#edit_vendor_www').val()
			
		},
		beforeSend: function() {
			$('#vendor_edit_error').val('');
			$('#vendor_edit_person_error').val('');
		},
		success: function(data){
			if(data.error == true) $('#vendor_edit_error').html('Это имя поставщика уже используется! Попробуйте ввести другое имя.');
			else if(data.error_person == true) $('#vendor_edit_person_error').html('Это имя для контактного лица уже используется! Попробуйте ввести другое имя.');
			else {				
				$('#vendor_name').html($('#edit_vendor_name').val());
				$('#vendor_name_short').html($('#edit_vendor_name_short').val());
				$('#vendor_city').html($('#edit_vendor_city').val());
				$('#vendor_city_short').html($('#edit_vendor_city_short').val());
				$('#vendor_phone').html($('#edit_vendor_phone').val());
				$('#vendor_fax').html($('#edit_vendor_fax').val());
				$('#vendor_email').html($('#edit_vendor_email').val());
				$('#vendor_www').html($('#edit_vendor_www').val());
				$('#vendor_edit').hide('slow');
			}
		},
		error: function(data) {}
	});
}
/***************   PERSON    ****************/
function set_person(vendor_id) {
	var person_name = $('#new_person_name').val();
	if(person_name == '' || person_name == undefined) {
		alert('Поле для имени поставщика не может быть пустым!');
		return false;
	}
	
	ajax_admin_path = admin_base_url + 'admin/persons/ajax_actions';
	
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "json",
		data: {
			'action': 'set_person',
			'vendor_id': vendor_id,
			'person_name': person_name,
			'person_posada': $('#new_person_posada').val(),
			'person_phone': $('#new_person_phone').val(),
			'person_email': $('#new_person_email').val()
		},
		beforeSend: function() {
			$('#vendor_edit_person_error').val('');
			$('#new_vendor_edit_person_error').val('');
		},
		success: function(data){
			if(data.error == true) $('#new_vendor_edit_person_error').html('Это имя для контактного лица уже используется! Попробуйте ввести другое имя.');
			else {
                alert("Добавлено!");
                $('#newContact').hide('slow');
				
				$("#vendor_person").prepend('<option value="' + data.result + '">'+ person_name +'</option>');
				$("#vendor_person :first").attr("selected", "selected");
								
				$('#edit_person_name').val(person_name);
				$('#new_person_name').val('');
				
				$('#edit_person_posada').val($('#new_person_posada').val());
				$('#new_person_posada').val('');
				
				$('#edit_person_phone').val($('#new_person_phone').val());
				$('#new_person_phone').val('');
				
				$('#edit_person_email').val($('#new_person_email').val());
				$('#new_person_email').val('');
			}
		},
		error: function(data) {}
	});
}
function get_person(p_id) {
    $("#edit_person_name").val(eval("person_"+p_id+".name"));
    $("#edit_person_posada").val(eval("person_"+p_id+".posada"));
    $("#edit_person_phone").val(eval("person_"+p_id+".phone"));
    $("#edit_person_email").val(eval("person_"+p_id+".email"));
    return;
   /*
    ajax_admin_path = admin_base_url + 'admin/persons/ajax_actions';
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "html",
		data: {
			'action': 'get_person',
			'vendor_id': vendor_id,
			'person_id': $('#vendor_person option:selected').val()
		},
		beforeSend: function() {

		},
		success: function(data){
			$('#personsBlocks').html(data);
		},
		error: function(data) {}
	});*/
}
function get_cur_vendor_person_id(){
  return $('#vendor_person option:selected').val();
}
function delete_person(vendor_id){
  	var person_id = $('#vendor_person option:selected').val();
	var person_name = $('#vendor_person option[value='+person_id+']').text();
    if (confirm("Удалять контактное лицо:"+person_name+"?")==false) {return;}

    ajax_admin_path = admin_base_url + 'admin/persons/ajax_actions';
	if(person_name == '' || person_name == undefined) {
		alert('Удвлять некого !');
		return false;
	}
    $.post(""+ajax_admin_path+"",
       {
       'action': 'delete_person',
	   'vendor_id': vendor_id,
	   'person_id': person_id
       },
       function(data)
	   {
        	if(data.error == true) $('#vendor_edit_person_error').html('Ошибка удаления! Попробуйте через некоторое время опять.');
			else {
				//$('#vendor_person option:[value=' + person_id + ']').text(person_name);
                alert("Контактное лицо удалено!");
                window.location.reload();
			}
	   }

    )

    return;

}
function edit_person(vendor_id){
	ajax_admin_path = admin_base_url + 'admin/persons/ajax_actions';

	var person_name = $('#edit_person_name').val();
	if(person_name == '' || person_name == undefined) {
		alert('Поле для имени поставщика не может быть пустым!');
		return false;
	}
	var person_id = $('#vendor_person option:selected').val();

	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "json",
		data: {
			'action': 'update_person',
			'vendor_id': vendor_id,
			'person_id': person_id,
			'person_name': person_name,
			'person_posada': $('#edit_person_posada').val(),
			'person_phone': $('#edit_person_phone').val(),
			'person_email': $('#edit_person_email').val()
		},
		beforeSend: function() {
			$('#vendor_edit_person_error').val('');
		},
		success: function(data){
			if(data.error == true) $('#vendor_edit_person_error').html('Это имя для контактного лица уже используется! Попробуйте ввести другое имя.');
			else {
				$('#vendor_person option:[value=' + person_id + ']').text(person_name);
			}
		},
		error: function(data) {}
	});
}
/***************   ACCOUNT    ****************/
function add_account(vendor_id) {
	ajax_admin_path = admin_base_url + 'admin/accounts/ajax_actions';
	
	var account_email = $('#new_account_email').val();
	var account_password = $('#new_account_password').val();
	if(account_email == '' || account_email == undefined) {
		alert('Поле для электронного адреса нового аккаунта не должно быть пустым!');
		return false;
	}
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "html",
		data: {
			'action': 'add_account',
			'vendor_id': vendor_id,
			'account_email': account_email
		},
		beforeSend: function() {
		},
		success: function(data){
			if(data == 2) {
				alert('Такой email уже используется!');
				return;				
			} else {
				var accountBox_count = parseInt($('div[id^="accountBox_"]').length)+1;
				var account_data = '<div id="accountBox_' + accountBox_count + '" class="accountBox"><div class="vendorNewSection"><span>Email:</span><input type="text" id="edit_account_email_' + accountBox_count + '" name="account_email" value="' + account_email + '" /></div></div>';			
				$('#accountBoxes').prepend(account_data);
				$('#newAccount').hide('slow');
				$('#new_account_email').val('');
			}
		},
		error: function(data) {}
	});
}
function edit_accounts(vendor_id) {
	ajax_admin_path = admin_base_url + 'admin/accounts/ajax_actions';

	var boxes_count = $('div[class^="accountBox"] input').length;
	var accounts = new Array();	
	var i = 1;
	var error = false;
	$('div[class^="accountBox"] input').each(function () {
		if($(this).val() == '') {
			alert('Поле Email '+ i +' не может быть пyстым!');
			error = true;
		} 
		else {
			var accountsObj = new Object();
			accountsObj.account_id = $(this).attr('id');
			accountsObj.account_email =  $(this).val();
			accounts[i] = accountsObj;
			i++;
		}		
	});
	if(error == false && accounts.length > 0) {
		$.ajax({
			type: "POST",
			url: ajax_admin_path,
			dataType: "json",
			data: {
				'action': 'edit_accounts',
				'vendor_id': vendor_id,
				'accounts': serialize(accounts)
			},
			beforeSend: function() {
			},
			success: function(data){
				if(data.error_type != '' && data.error_option != '') {
					if(data.error_type == 2) {
						alert('Email с именем '+ data.error_option +' уже существует либо вы пытаетесь внести адреса с одинаковым именем!');
					}
				}
			},
			error: function(data) {}
		});
	}
}

/**************** FORM ACTION FUNCTIONS *********************/
function saveEdit(type) {
ajax_admin_path = admin_base_url + 'admin/dictionary/ajax_actions';
var n_name=$("#id_edit_"+type).val();
var type_id=$("#"+type+" option:selected").val();

var cur_type=type;
$.ajax({
	type: "POST",
	url: ajax_admin_path,
	dataType: "json",
	data: {
		'action': 'save_edit',
		'n_name': n_name,
		'type': type,
        'type_id': type_id
	},
	beforeSend: function() {
	},
	success: function(data){

		  alert(data.mes);
          if (data.res == true) {
             $("#id_form_edit_"+cur_type).hide();
             $("#"+cur_type+" option:[value=" + type_id + "]").text(n_name);
          }

	},
	error: function(data) {}
});
}
function controlKeyEdit(obj, event)
{
        var event = event || window.event;
        var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : null;
        if (keyCode == 13) {
            isPressEnter = true;
        }
}
function editFormSet(type) {

 cur_name=$("#"+type+" option:selected").text();
 type_str=String(type);
 var form_str="<div id='id_form_edit_"+type+"'><br><div class='elementLabel'>Название</div>&nbsp;<input type='text' size='33' id='id_edit_"+type+"' value='"+cur_name+"'  onkeydown='controlKeyEdit(this, event);' ></><input type='button' value='Сохранить' onclick='javascript:saveEdit(\""+type+"\")'/>&nbsp;<input type='button' value='Отмена' onclick='javascript:$(this.parentNode).css(\"display\",\"none\");' /></div>";
 $("#"+type+"_edit").html(form_str);
}
function actionForm(form_id, speed) {
	var el = document.getElementById(form_id).style.display;	
	if(speed == undefined) speed = 'slow';
	
	if(el == 'none') {
		$('#'+form_id).show(speed);
	}
	else {
		$('#'+form_id).hide(speed);
	}
}
function actionImage(form_id) {
	var src = $('#'+ form_id +'_img').attr('src').split('icons/');
	var src_new = admin_base_url + 'images/icons/';
	
	if(src[1] == 'selector-down.png') {
		src_new += 'selector-left.png';
		$('#'+ form_id +'_img').attr('src', src_new);
	}
	else if(src[1] == 'selector-left.png') {
		src_new += 'selector-down.png';
		$('#'+ form_id +'_img').attr('src', src_new);
	}
}
function add_form(element_id){
	var element_display = document.getElementById("new_"+element_id+"_block").style.display;
	
	if(element_display == 'none') {
		$("#new_"+element_id+"_block").show();
	} else {
		$("#new_"+element_id+"_block").hide();
	}
}
function str_replace2(haystack, needle, replacement) { 
	var temp = haystack.split(needle); 
	return temp.join(replacement); 
}
function str_replace(search, replace, subject, count) {
    // *     example 1: str_replace(' ', '.', 'Kevin van Zonneveld');
    // *     returns 1: 'Kevin.van.Zonneveld'
    // *     example 2: str_replace(['{name}', 'l'], ['hello', 'm'], '{name}, lars');
    // *     returns 2: 'hemmo, mars'

    var i = 0, j = 0, temp = '', repl = '', sl = 0, fl = 0,
            f = [].concat(search),
            r = [].concat(replace),
            s = subject,
            ra = r instanceof Array, sa = s instanceof Array;
    s = [].concat(s);
    if (count) {
        this.window[count] = 0;
    }

    for (i=0, sl=s.length; i < sl; i++) {
        if (s[i] === '') {
            continue;
        }
        for (j=0, fl=f.length; j < fl; j++) {
            temp = s[i]+'';
            repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
            s[i] = (temp).split(f[j]).join(repl);
            if (count && s[i] !== temp) {
                this.window[count] += (temp.length-s[i].length)/f[j].length;}
        }
    }
    return sa ? s : s[0];
}
function serialize (mixed_value) {
  var _getType = function (inp) {
        var type = typeof inp, match;
        var key;
        if (type == 'object' && !inp) {            return 'null';
        }
        if (type == "object") {
            if (!inp.constructor) {
                return 'object';            }
            var cons = inp.constructor.toString();
            match = cons.match(/(\w+)\(/);
            if (match) {
                cons = match[1].toLowerCase();            }
            var types = ["boolean", "number", "string", "array"];
            for (key in types) {
                if (cons == types[key]) {
                    type = types[key];                    break;
                }
            }
        }
        return type;    };
    var type = _getType(mixed_value);
    var val, ktype = '';
    
    switch (type) {        case "function": 
            val = ""; 
            break;
        case "boolean":
            val = "b:" + (mixed_value ? "1" : "0");            break;
        case "number":
            val = (Math.round(mixed_value) == mixed_value ? "i" : "d") + ":" + mixed_value;
            break;
        case "string":            mixed_value = this.utf8_encode(mixed_value);
            val = "s:" + encodeURIComponent(mixed_value).replace(/%../g, 'x').length + ":\"" + mixed_value + "\"";
            break;
        case "array":
        case "object":            val = "a";
            /*
            if (type == "object") {
                var objname = mixed_value.constructor.toString().match(/(\w+)\(\)/);
                if (objname == undefined) {                    return;
                }
                objname[1] = this.serialize(objname[1]);
                val = "O" + objname[1].substring(1, objname[1].length - 1);
            }            */
            var count = 0;
            var vals = "";
            var okey;
            var key;            for (key in mixed_value) {
                ktype = _getType(mixed_value[key]);
                if (ktype == "function") { 
                    continue; 
                }                
                okey = (key.match(/^[0-9]+$/) ? parseInt(key, 10) : key);
                vals += this.serialize(okey) +
                        this.serialize(mixed_value[key]);
                count++;            }
            val += ":" + count + ":{" + vals + "}";
            break;
        case "undefined": // Fall-through
        default: // if the JS object has a property which contains a null value, the string cannot be unserialized by PHP            val = "N";
            break;
    }
    if (type != "object" && type != "array") {
        val += ";";    }
    return val;
}

function utf8_encode ( argString ) {
    // Encodes an ISO-8859-1 string to UTF-8  
    // 
    // version: 909.322
    // discuss at: http://phpjs.org/functions/utf8_encode    // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: sowberry
    // +    tweaked by: Jack
    // +   bugfixed by: Onno Marsman    // +   improved by: Yves Sucaet
    // +   bugfixed by: Onno Marsman
    // +   bugfixed by: Ulrich
    // *     example 1: utf8_encode('Kevin van Zonneveld');
    // *     returns 1: 'Kevin van Zonneveld'    
    var string = (argString+''); // .replace(/\r\n/g, "\n").replace(/\r/g, "\n");
 
    var utftext = "";
    var start, end;
    var stringl = 0; 
    start = end = 0;
    stringl = string.length;
    for (var n = 0; n < stringl; n++) {
        var c1 = string.charCodeAt(n);        var enc = null;
 
        if (c1 < 128) {
            end++;
        } else if (c1 > 127 && c1 < 2048) {            enc = String.fromCharCode((c1 >> 6) | 192) + String.fromCharCode((c1 & 63) | 128);
        } else {
            enc = String.fromCharCode((c1 >> 12) | 224) + String.fromCharCode(((c1 >> 6) & 63) | 128) + String.fromCharCode((c1 & 63) | 128);
        }
        if (enc !== null) {            if (end > start) {
                utftext += string.substring(start, end);
            }
            utftext += enc;
            start = end = n+1;        }
    }
 
    if (end > start) {
        utftext += string.substring(start, string.length);    }
 
    return utftext;
}

function dump(obj, step) {
	if (typeof step == 'undefined') {
		step = -1;
	}
	step++;
	var pad = new Array(2*step).join('   ');
	var str = typeof(obj)+":\n";
	for(var p in obj){
		if (typeof obj[p] == 'object') {
			str += pad+'   ['+p+'] = '+dump(obj[p], step);
		} else {
			str += pad+'   ['+p+'] = '+obj[p]+"\n";
		}
	}
	return str;
}

/***************   USER  ****************/
function add_new_user() {
   $("#id_new_user").css("display","inline");
}
function setNewUserPsw() {
  $("#id_new_user_psw").val($("#id_new_user_name").val());
}
function cancel_add_new_user(){
   $("#id_new_user").css("display","none");
   $("#id_new_user_name").val("");
   $("#id_new_user_psw").val("");
}
function admin_add_new_user() {
  user_name=$("#id_new_user_name").val();
  if (user_name =="") {
     alert("Введите имя!");
     return;
  }
  $.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "json",
		data: {
		    'action': 'admin_add_new_user',
            'user_name': user_name,
            'user_psw': $("#id_new_user_psw").val(),
            'user_email': $("#id_new_user_email").val(),
		},
        success:
            function(data)
              {
                if(data.error == true) {
                  alert('Ошибка добавления!\n'+data.mes);
                }
            	else {
                     if (confirm("Пользователь добавлен.\nРедактировать его данные?")) {
                            window.location.href=""+admin_base_url+""+"admin/users/profile/"+data.result;
                          } else {
                             $("#id_new_user").css("display","none");
                          }

            	}
              }
    }
    );
  //----------------------------
}
function admin_user_del(user_id) {
   if ( confirm("Удалять пользователя?")) {
       $.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "json",
		data: {
		    'action': 'admin_del_user',
            'user_id': user_id
		},
        success:
            function(data)
              {
                if(data.error == true) {
                  alert('Ошибка Удаления!\n'+data.mes);
                }
            	else {
                     alert("Пользователь удален!");
                     window.location.reload(true);
            	}
              }
    }
    );
   }
}
function admin_user_block(user_id) {
   if ( confirm("Блокировать пользователя?")) {

   $.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "json",
		data: {
		    'action': 'admin_block_user',
            'user_id': user_id
		},
        success:
            function(data)
              {
                if ((data==null) || (data.error == true)) {
                  alert('Ошибка блокировки!\n'+data.mes);
                }
            	else {
                     alert("Пользователь заблокирован!");
                     $("#id_tr_"+user_id).css("background","#FFCCFF");
                     $("#id_img_unblock_"+user_id).show();
                     $("#id_img_block_"+user_id).hide();
            	}
              }
    }
    );

   }
}
function admin_user_unblock(user_id) {
     if ( confirm("Разблокировать пользователя?")) {
        $.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "json",
		data: {
		    'action': 'admin_unblock_user',
            'user_id': user_id
		},
        success:
            function(data)
              {
                if((data==null) || (data.error == true)) {
                  alert('Ошибка разблокировки!\n');
                }
            	else {
                     alert("Пользователь разаблокирован!");
                     $("#id_tr_"+user_id).css("background","");
                     $("#id_img_block_"+user_id).show();
                     $("#id_img_unblock_"+user_id).hide();
            	}
              }
    }
    );
       $("#id_tr_"+user_id).css("background","#FFCCFF");
   }
}
function edit_user(user_id){
	//ajax_admin_path = admin_base_url + 'admin/users/ajax_actions';

	var user_login = $('#edit_user_login').val();
	if(user_login == '' || user_login == undefined) {
		alert('Поле для имени поставщика не может быть пустым!');
		return false;
	}
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "json",
		data: {
			'action': 'update_user',
			'user_id': user_id,
			'user_login': $('#edit_user_login').val(),
			'user_city': $('#edit_user_city').val(),
			'user_tel': $('#edit_user_tel').val(),
			'user_company': $('#edit_user_company').val(),
			'user_fio': $('#edit_user_fio').val(),
			'user_aedate': $('#edit_user_aedate').val(),
			'user_group': $('#edit_user_group').val(),
			'user_email': $('#edit_user_email').val()
			
		},
		beforeSend: function() {
			$('#user_edit_error').val('');
			$('#user_edit_aedata_error').val('');
		},
		success: function(data){
			if(data.error == true) $('#user_edit_error').html('Это имя  уже используется! Попробуйте ввести другое имя.');
			else if(data.error_aedata == true) $('#user_edit_aedata_error').html('Неверный формат даты! Введите дату в формате гггг-мм-дд.');
			else {				
				$('#user_login').html($('#edit_user_login').val());
				$('#user_email').html($('#edit_user_email').val());
				$('#user_city').html($('#edit_user_city').val());
				$('#user_tel').html($('#edit_user_tel').val());
				$('#user_company').html($('#edit_user_company').val());
				$('#user_fio').html($('#edit_user_fio').val());
				$('#user_aedate').html($('#edit_user_aedate').val());
				$('#user_group').html($('#edit_user_group').val());
				$('#user_edit').hide('slow');
			}
		},
		error: function(data) {}
	});
}
/**********************SHEETS*********************/
function edit_sheets(sheet_id,id,isheet){
	ajax_admin_path = admin_base_url + 'admin/vendors/ajax_actions';
	var sheet_active = $('#'+sheet_id+' option:selected').val();
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "json",
		data: {
			'action': 'update_sheet',
			'sheet_id': sheet_id,
            'id': id,			
			'sheet_active': sheet_active

		},
		success: function(data){
			//$('#sheet_active').html($('#active'+isheet+'option:selected').val());
		},
		error: function(data) {}
	});
}

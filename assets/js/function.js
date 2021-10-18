
function call_view_ajax(el)
{
	var a = base_url + el.attr("data-uri");
	var b = {
		id: el.attr("data-id")
	};
	var d = '';
	$.ajax({
		method: "post",
		async:false,
		data: b,
		url: a,
		success: function(a) {
			d = $.parseJSON(a);
		}
	});
	return d;
}

function call_update_ajax(el)
{
	remove_validation();
	var a = base_url + el.attr("data-uri");
	var b = el.attr("data-id");
	var c = {
		id: b
	};
	var d = '';
	$.ajax({
		method: "post",
		async:false,
		data: c,
		url: a,
		success: function(a) {
			d = $.parseJSON(a);
		}
	});
	return d;
}

function call_ajax(data,url)
{
	var d = '';
	$.ajax({
		method: "post",
		async:false,
		data: data,
		url: url,
		success: function(a) {
			d = $.parseJSON(a);
		}
	});
	return d;
}

function getMonthName(a) {
    var b = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
    return b[a];
}

function apply_validation(a, b) {
    a.addClass("border-danger");
    a.focus();
    a.after('<span class="text-danger validation-error-temp">' + b + "</span>");
}

function remove_validation() {
    $(".form-control").removeClass("border-danger");
    $(".validation-error-temp").remove();
}

function getTodaysDate()
{
	var today = new Date();
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
	var yyyy = today.getFullYear();

	today = dd + '-' + mm + '-' + yyyy;
	return today;
}
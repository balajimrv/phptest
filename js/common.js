// Validate Required
function validateRequired(field, msg, min, max){
	var test = "pass";
	if(field.value.length == 0) {
		test = "fail";
	}else if(min && field.value.length < min) {
		msg = msg + "\nMin Length should be " + min;
		test = "fail";
	}else if(max && field.value.length > max) {
		msg = msg + "\nMax Length should be " + max;
		test = "fail";
	}
	
	if(test == "fail"){
		if (msg) alert(msg);
		field.focus();
		field.select();
		return false;
	}
	return true;
}

// Validate Extension
function CheckExtension(field, msg){
	
	var ext = field.value.split(".")
	if(ext[1] == 'doc'){
		return true;
	}
	else {
		if (msg != ''){
			alert(msg);
		}
		field.focus();
		return false;
	}

}

//Validate Extension
function CheckImgExtension(field, msg){
	
	var ext = field.value.split(".")
	if((ext[1] == 'jpg') || (ext[1] == 'jpeg') || (ext[1] == 'gif')){
		return true;
	}
	else {
		if (msg != ''){
			alert(msg);
		}
		field.focus();
		return false;
	}

}


// Validate word count
function count_words(field, msg, min, max)
{
    var test = "pass";
	var no_words = field.value.split(" ");
	
	if(field.value.length == 0) {
		test = "fail";
	}else if(min && no_words.length < min) {
		msg = msg + "\nMin Word should be " + min;
		test = "fail";
	}else if(max && no_words.length > max) {
		msg = msg + "\nMax Word should be " + max;
		test = "fail";
	}
	
	if(test == "fail"){
		if (msg) alert(msg);
		field.focus();
		field.select();
		return false;
	}
	return true;
}

// Validate Number
function validateNumber(field, msg, min, max){
	if (!min) { min = 0 }
	if (!max) { max = 255 }

	if ( (parseInt(field.value) != field.value) ||
             field.value.length < min ||
             field.value.length > max) {
		alert(msg);
		field.focus();
		field.select();
		return false;
	}

	return true;
}
//Validate mobileNumber
function validateMobileNumber(field, msg, min, max){
	if (!min) { min = 0 }
	if (!max) { max = 12 }

	if ( (parseInt(field.value) != field.value) ||
             field.value.length < min ||
             field.value.length > max) {
		alert(msg);
		field.focus();
		field.select();
		return false;
	}

	return true;
}

// Validate Mail IDs
function validateEmail(field, msg){
	if (!field.value) {
		return true;
	}

	var re_mail = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
	if (!re_mail.test(field.value)) {
		if (msg != '') { alert(msg); }
		field.focus();
		field.select();
		return false;
	}

	return true;
}

// Validate Url
function validateUrl(field, msg){
	if (!field.value) {
		return true;
	}

	var re_url = /^(ht|f)tps?:\/\/[a-z0-9-\.]+\.[a-z]{2,4}\/?([^\s<>\#%"\,\{\}\\|\\\^\[\]`]+)?$/;
	if (!re_url.test(field.value)) {
		if (msg != '') { alert(msg); }
		field.focus();
		field.select();
		return false;
	}

	return true;
}

// Validate Alpha-Numeric
function validateAlphaNumeric(field, msg){
	var numaric = field.value;
	for(var j=0; j<numaric.length; j++) {
		var alphaa = numaric.charAt(j);
		var hh = alphaa.charCodeAt(0);
		
		if(!(hh > 47 && hh<59) || (hh > 64 && hh<91) || (hh > 96 && hh<123)){
			if (msg != ''){
				alert(msg);
			}
			field.focus();
			field.select();
			return false;
		}
	}
	return true;
}

// Validate Combo
function validateCombo(field, msg){
	
	if(field.selectedIndex == 0){
		if (msg != ''){
			alert(msg);
		}
		field.focus();
		return false;
	}

	return true;
}

// Validate Checkbox
function validateCheckbox(field, msg){
	
	if(field.checked == false){
		if (msg != ''){
			alert(msg);
		}
		field.focus();
		return false;
	}

	return true;
}

// Validate at least one textbox
function validateAtleastOne(field1, field2, msg){
	
	if((field1.value == "") && (field2.value == "")) {
		if (msg != ''){
			alert(msg);
		}
		field1.focus();
		return false;
	}

	return true;
}

// Validate at least one Combo
function validateAtleastOneCombo(field1, field2, msg){
	
	if((field1.selectedIndex == 0) && (field2.selectedIndex == 0)) {
		if (msg != ''){
			alert(msg);
		}
		field1.focus();
		return false;
	}

	return true;
}


function ValidateAnyOne(field1, field2, msg, msg1) {
	if(field1.value || field2.value) {
		if (field1.value != ''){
			if(parseInt(field1.value) != field1.value) {
				alert(msg);
				return false;
			}
			
		}
		return true	;
	}
	alert(msg1);
	return false;
}

function compareFields(field1, field2, msg) {
	if(field1.value != field2.value) {
		if (msg != ''){
			alert(msg);
		}
		return false;
	}

	return true;
}
function compareFields2(field1, field2, msg) {
	if(field1.value == field2.value) {
		if (msg != ''){
			alert(msg);
		}
		return false;
	}

	return true;
}

// to Radio button
function ValidateRadio(field, msg) {
	for(i=0; i<field.length; i++)
	{
		if(field[i].checked) {
			return true;
		}
	}
	if (msg != ''){
			alert(msg);	
		}
		return false;	
}

function validatePhoneno(field,msg) {
		if((field.value==null) ||(field.value=="")) {
			alert('Please enter your phone number');
			field.focus();
			return false;
		}
		/*//else if(field.value.search(/^[0-9]+$/ == -1) {*/
		else if((field.value.search(/\d{3}\-\d{8}/) ==-1) || (field.value.search(/\d{3}\ \d{8}/) ==-1)) {
			alert("Phone Number Should be xxx-xxxxxxxx or xxx xxxxxxxx");
			fiels.focus();
			return false;
		}
}

function confirmAlert(msg) {
	return confirm(msg);
}


function numbersonly(myfield, e, dec) {
  var key;
  var keychar;

  if (window.event)
    key = window.event.keyCode;
  else if (e)
    key = e.which;
  else
    return true;
  keychar = String.fromCharCode(key);

  // control keys
  if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
    return true;

  // numbers
  else if ((("+0123456789").indexOf(keychar) > -1))
    return true;

  // decimal point jump
  else if (dec && (keychar == ".")) {
    myfield.form.elements[dec].focus();
    return false;
  } else
    return false;
}
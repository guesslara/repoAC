// JavaScript Document
function objetoAjax(){
	var xmlhttp2=false;
	try {
		xmlhttp2 = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
		   xmlhttp2 = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp2 = false;
  		}
	}

	if (!xmlhttp2 && typeof XMLHttpRequest!='undefined') {
		xmlhttp2 = new XMLHttpRequest();
	}
	return xmlhttp2;
}

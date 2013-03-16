function setBookmark(title,url){
	if(title=='')title=url;
	if(document.all)window.external.AddFavorite(url,title); 		// IE
	else if(window.sidebar)window.sidebar.addPanel(title,url,"");	// firefox
	else if(window.opera && window.print){ 							// opera
		var elem = document.createElement('a');
		elem.setAttribute('href',url);
		elem.setAttribute('title',title);
		elem.setAttribute('rel','sidebar');
		elem.click();
	}
	else alert('Press CTRL and D to add a bookmark to:\n"'+url+'".');
}

var contadorMax = 500;

function replace(string) {
	var strLength = string.length;
	if (strLength == 0) return string;
    var newStr = "";
	var i;
	for(i=0; i<strLength; i++){
		if(string.charAt(i) != "\r"){
			newStr += string.charAt(i);
		}
	}
	return newStr;
}

function contador(input,counterInput){
	var charLeft = contadorMax;
	if(input.value){
		var str = replace(input.value);
		charLeft = charLeft - str.length;
		if (charLeft<0){
		input.value = str.slice(0,contadorMax);
		charLeft = 0;
		}
	}
	counterInput.value = charLeft;
}

function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}
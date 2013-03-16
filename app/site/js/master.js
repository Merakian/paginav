function setBookmark(title,url){
	if(title===''){title=url;}
	if(document.all){ window.external.AddFavorite(url,title); } 		// IE
	else if(window.sidebar){ window.sidebar.addPanel(title,url,""); }	// firefox
	else if(window.opera && window.print){ 							// opera
		var elem = document.createElement('a');
		elem.setAttribute('href',url);
		elem.setAttribute('title',title);
		elem.setAttribute('rel','sidebar');
		elem.click();
	}
	else { alert('Press CTRL and D to add a bookmark to:\n"'+url+'".'); }
}

function clearText(thefield){
	if (thefield.defaultValue==thefield.value)
	thefield.value = ""
}
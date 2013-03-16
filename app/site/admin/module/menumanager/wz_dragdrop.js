/* This notice must be untouched at all times.

wz_dragdrop.js    v. 4.62 (modified)
The latest version is available at
http://www.walterzorn.com
or http://www.devira.com
or http://www.walterzorn.de

Copyright (c) 2002-2003 Walter Zorn. All rights reserved.
Created 26. 8. 2002 by Walter Zorn (Web: http://www.walterzorn.com )
Last modified: 2. 6. 2005

This DHTML & Drag&Drop Library adds Drag&Drop functionality
to the following types of html-elements:
- images, even if not positioned via layers,
  nor via stylesheets or any other kind of "hard-coding"
- relatively and absolutely positioned layers (DIV elements).
Moreover, it provides extended DHTML abilities.

LICENSE: LGPL

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License (LGPL) as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

For more details on the GNU Lesser General Public License,
see http://www.gnu.org/copyleft/lesser.html
*/


// PATH TO THE TRANSPARENT 1*1 PX IMAGE (required by NS 4 as spacer)
var spacer = 'transparentpixel.gif';

// WB+  Functions added -------------------------------------------------
  

function sprintf()
{
	if (!arguments || arguments.length < 1 || !RegExp)
	{
		return;
	}
	var str = arguments[0];
	var re = /([^%]*)%('.|0|\x20)?(-)?(\d+)?(\.\d+)?(%|b|c|d|u|f|o|s|x|X)(.*)/;
	var a = b = [], numSubstitutions = 0, numMatches = 0;
	while (a = re.exec(str))
	{
		var leftpart = a[1], pPad = a[2], pJustify = a[3], pMinLength = a[4];
		var pPrecision = a[5], pType = a[6], rightPart = a[7];
		
		//alert(a + '\n' + [a[0], leftpart, pPad, pJustify, pMinLength, pPrecision);

		numMatches++;
		if (pType == '%')
		{
			subst = '%';
		}
		else
		{
			numSubstitutions++;
			if (numSubstitutions >= arguments.length)
			{
				alert('Error! Not enough function arguments (' + (arguments.length - 1) + ', excluding the string)\nfor the number of substitution parameters in string (' + numSubstitutions + ' so far).');
			}
			var param = arguments[numSubstitutions];
			var pad = '';
			       if (pPad && pPad.substr(0,1) == "'") pad = leftpart.substr(1,1);
			  else if (pPad) pad = pPad;
			var justifyRight = true;
			       if (pJustify && pJustify === "-") justifyRight = false;
			var minLength = -1;
			       if (pMinLength) minLength = parseInt(pMinLength);
			var precision = -1;
			       if (pPrecision && pType == 'f') precision = parseInt(pPrecision.substring(1));
			var subst = param;
			       if (pType == 'b') subst = parseInt(param).toString(2);
			  else if (pType == 'c') subst = String.fromCharCode(parseInt(param));
			  else if (pType == 'd') subst = parseInt(param) ? parseInt(param) : 0;
			  else if (pType == 'u') subst = Math.abs(param);
			  else if (pType == 'f') subst = (precision > -1) ? Math.round(parseFloat(param) * Math.pow(10, precision)) / Math.pow(10, precision): parseFloat(param);
			  else if (pType == 'o') subst = parseInt(param).toString(8);
			  else if (pType == 's') subst = param;
			  else if (pType == 'x') subst = ('' + parseInt(param).toString(16)).toLowerCase();
			  else if (pType == 'X') subst = ('' + parseInt(param).toString(16)).toUpperCase();
		}
		str = leftpart + subst + rightPart;
	}
	return str;
}
// WB+  Enc New Functions ----------------------------------------------


//window.onerror = new Function('return true;');


// Optional commands passed to SET_DHTML() on the html-page (g: may be applied globally, i: individually)
var CLONE            = 'C10nE';   // i  img      clone image
var COPY             = 'C0pY';    // i  img      create copies
var DETACH_CHILDREN  = 'd37aCH';  // i  lyr      detach images
var HORIZONTAL       = 'H0r1Z';   // i  img,lyr  horizontally draggable only
var MAXHEIGHT        = 'm7x8I';   // i  img,lyr  maximum height limit, "
var MAXOFFBOTTOM     = 'm7xd0wN'; // i  img,lyr  downward offset limit
var MAXOFFLEFT       = 'm7x23Ft'; // i  img,lyr  leftward offset limit
var MAXOFFRIGHT      = 'm7x0Ff8'; // i  img,lyr  rightward offset limit
var MAXOFFTOP        = 'm7xu9';   // i  img,lyr  upward offset limit
var MAXWIDTH         = 'm7xW1';   // i  img,lyr  maximum width limit, use with resizable or scalable
var MINWIDTH         = 'm1nw1';   // i  img,lyr  minimum width limit, "
var MINHEIGHT        = 'm1n8I';   // i  img,lyr  minimum height limit, "
var NO_ALT           = 'no81T';   // gi img      disable alt and title attributes
var NO_DRAG          = 'N0d4Ag';  // i  img,lyr  disable draggability
var RESET_Z          = 'r35E7z';  // gi img,lyr  reset z-index when dropped
var RESIZABLE        = 'r5IZbl';  // gi img,lyr  resizable if <ctrl> or <shift> pressed
var SCALABLE         = 'SCLbl';   // gi img,lyr  scalable           "
var SCROLL           = 'sC8lL';   // gi img,lyr  enable auto scroll functionality
var TRANSPARENT      = 'dIApHAn'; // gi img,lyr  translucent while dragged
var VERTICAL         = 'V3Rt1C';  // i  img,lyr  vertically draggable only

var dd_cursors = new Array(
	'c:default',
	'c:crosshair',
	'c:e-resize',
	'c:hand',
	'c:help',
	'c:move',
	'c:n-resize',
	'c:ne-resize',
	'c:nw-resize',
	'c:s-resize',
	'c:se-resize',
	'c:sw-resize',
	'c:text',
	'c:w-resize',
	'c:wait'
);
var dd_i = dd_cursors.length; while(dd_i--)
	eval('var CURSOR_' + (dd_cursors[dd_i].substring(2).toUpperCase().replace('-', '_')) + ' = "' + dd_cursors[dd_i] + '";');
var dd_u = "undefined";


function WZDD()
{
	this.elements = new Array(0);
	this.obj = null;
	this.n = navigator.userAgent.toLowerCase();
	this.db = (document.compatMode && document.compatMode.toLowerCase() != "backcompat")?
		document.documentElement
		: (document.body || null);
	this.op = !!(window.opera && document.getElementById);
	this.op6 = !!(this.op && !(this.db && this.db.innerHTML));
	if (this.op && !this.op6) document.onmousedown = new Function('e',
		'if (((e = e || window.event).target || e.srcElement).tagName == "IMAGE") return false;');
	this.ie = !!(this.n.indexOf("msie") >= 0 && document.all && this.db && !this.op);
	this.iemac = !!(this.ie && this.n.indexOf("mac") >= 0);
	this.ie4 = !!(this.ie && !document.getElementById);
	this.n4 = !!(document.layers && typeof document.classes != dd_u);
	this.n6 = !!(typeof window.getComputedStyle != dd_u && typeof document.createRange != dd_u);
	this.w3c = !!(!this.op && !this.ie && !this.n6 && document.getElementById);
	this.ce = !!(document.captureEvents && document.releaseEvents);
	this.px = (this.n4 || this.op6)? '' : 'px';
	this.tiv = this.w3c? 40 : 10;
}
var dd = new WZDD();

dd.Int = function(d_x, d_y)
{
	return isNaN(d_y = parseInt(d_x))? 0 : d_y;
};

dd.getWndW = function()
{
	return dd.Int(
		(dd.db && !dd.op && !dd.w3c && dd.db.clientWidth)? dd.db.clientWidth
		: (window.innerWidth || 0)
	);
};

dd.getWndH = function()
{
	return dd.Int(
		(dd.db && !dd.op && !dd.w3c && dd.db.clientHeight)? dd.db.clientHeight
		: (window.innerHeight || 0)
	);
};

dd.getScrollX = function()
{
	return dd.Int(window.pageXOffset || (dd.db? dd.db.scrollLeft : 0));
};

dd.getScrollY = function()
{
	return dd.Int(window.pageYOffset || (dd.db? dd.db.scrollTop : 0));
};

dd.getPageXY = function(d_o)
{
	if (dd.n4 && d_o)
	{
		dd.x = d_o.pageX || 0;
		dd.y = d_o.pageY || 0;
	}
	else
	{
		dd.x = dd.y = 0; //global helper vars
		while (d_o)
		{
			dd.x += dd.Int(d_o.offsetLeft);
			dd.y += dd.Int(d_o.offsetTop);
			d_o = d_o.offsetParent || null;
		}
	}
};

dd.getCssXY = function(d_o)
{
	if (d_o.div)
	{
		if (dd.n4)
		{
			d_o.cssx = d_o.div.x;
			d_o.cssy = d_o.div.y;
		}
		else if (dd.ie4)
		{
			d_o.cssx = d_o.css.pixelLeft;
			d_o.cssy = d_o.css.pixelTop;
		}
		else
		{
			d_o.css.left = d_o.css.top = 0 + dd.px;
			dd.getPageXY(d_o.div);
			d_o.cssx = d_o.x - dd.x;
			d_o.cssy = d_o.y - dd.y;
			d_o.css.left = d_o.cssx + dd.px;
			d_o.css.top = d_o.cssy + dd.px;
		}
	}
	else
	{
		d_o.cssx = 0;
		d_o.cssy = 0;
	}
};

dd.getImgW = function(d_o)
{
	return d_o? dd.Int(d_o.width) : 0;
};

dd.getImgH = function(d_o)
{
	return d_o? dd.Int(d_o.height) : 0;
};

dd.getDivW = function(d_o)
{
	return dd.Int(
		dd.n4? (d_o.div? d_o.div.clip.width : 0)
		: d_o.div? (d_o.div.offsetWidth || d_o.css.pixelWidth || d_o.css.width || 0)
		: 0
	);
};

dd.getDivH = function(d_o)
{
	return dd.Int(
		dd.n4? (d_o.div? d_o.div.clip.height : 0)
		: d_o.div? (d_o.div.offsetHeight || d_o.css.pixelHeight || d_o.css.height || 0)
		: 0
	);
};

dd.getWH = function(d_o)
{
	d_o.w = dd.getDivW(d_o);
	d_o.h = dd.getDivH(d_o);
	if (d_o.css)
	{
		d_o.css.width = d_o.w + dd.px;
		d_o.css.height = d_o.h + dd.px;
		d_o.dw = dd.getDivW(d_o)-d_o.w;
		d_o.dh = dd.getDivH(d_o)-d_o.h;
		d_o.css.width = (d_o.w-d_o.dw) + dd.px;
		d_o.css.height = (d_o.h-d_o.dh) + dd.px;
	}
	else d_o.dw = d_o.dh = 0;
};

dd.getCssProp = function(d_o, d_pn6, d_pstyle, d_pn4)
{
	if (d_o && dd.n6) return ''+window.getComputedStyle(d_o, null).getPropertyValue(d_pn6);
	if (d_o && d_o.currentStyle) return ''+eval('d_o.currentStyle.'+d_pstyle);
	if (d_o && d_o.style) return ''+eval('d_o.style.'+d_pstyle);
	if (d_o && dd.n4) return ''+eval('d_o.'+d_pn4);
	return '';
};

dd.getDiv = function(d_x, d_d)
{
	d_d = d_d || document;
	if (dd.n4)
	{
		if (d_d.layers[d_x]) return d_d.layers[d_x];
		for (var d_i = d_d.layers.length; d_i--;)
		{
			var d_y = dd.getDiv(d_x, d_d.layers[d_i].document);
			if (d_y) return d_y;
		}
	}
	if (dd.ie) return d_d.all[d_x] || null;
	if (d_d.getElementById) return d_d.getElementById(d_x) || null;
	return null;
};

dd.getImg = function(d_o, d_nm, d_xy, d_w)
{
	d_w = d_w || window;
	var d_img;
	if (document.images && (d_img = d_w.document.images[d_nm]) && d_img.name == d_nm)
	{
		if (d_xy)
		{
			if (dd.n4)
			{
				dd.getPageXY(d_w);
				d_o.defx = d_img.x + dd.x;
				d_o.defy = d_img.y + dd.y;
			}
			else
			{
				dd.getPageXY(d_img);
				d_o.defx = dd.x;
				d_o.defy = dd.y;
			}
		}
		return d_img;
	}
	if (dd.n4) for (var d_i = d_w.document.layers.length; d_i--;)
	{
		var d_y = dd.getImg(d_o, d_nm, d_xy, d_w.document.layers[d_i]);
		if (d_y) return d_y;
	}
	return null;
};

dd.getParent = function(d_o, d_p)
{
	if (dd.n4)
	{
		for (d_p, d_i = dd.elements.length; d_i--;)
		{
			if (!((d_p = dd.elements[d_i]).is_image) && d_p.div && (d_p.div.document.layers[d_o.name] || d_o.oimg && d_p.div.document.images[d_o.oimg.name]))
				d_p.addChild(d_o, d_p.detach, 1);
		}
	}
	else
	{
		d_p = d_o.is_image? dd.getImg(d_o, d_o.oimg.name) : (d_o.div || null);
		while (d_p && !!(d_p = d_p.offsetParent || d_p.parentNode || null))
		{
			if (d_p.ddObj)
			{
				d_p.ddObj.addChild(d_o, d_p.ddObj.detach, 1);
				break;
			}
		}
	}
};

dd.getCmd = function(d_o, d_cmd, d_cmdStr)
{
	var d_i = d_o.id.indexOf(d_cmd), d_j,
	d_y = (d_i >= 0)*1;
	if (d_y)
	{
		d_j = d_i+d_cmd.length;
		if (d_cmdStr) d_o.cmd += d_o.id.substring(d_i, d_j);
		d_o.id = d_o.id.substring(0, d_i) + d_o.id.substring(d_j);
	}
	return d_y;
};

dd.getCmdVal = function(d_o, d_cmd, d_cmdStr, int0)
{
	var d_i = d_o.id.indexOf(d_cmd), d_j,
	d_y = (d_o.id.indexOf(d_cmd) >= 0)? dd.Int(d_o.id.substring(d_o.id.indexOf(d_cmd)+d_cmd.length)) : int0? -1 : 0;
	if (!int0 && d_y || int0 && d_y >= 0)
	{
		d_j = d_i+d_cmd.length+(""+d_y).length;
		if (d_cmdStr) d_o.cmd += d_o.id.substring(d_i, d_j);
		d_o.id = d_o.id.substring(0, d_i) + d_o.id.substring(d_j);
	}
	return d_y;
};

dd.addElt = function(d_o, d_p)
{
	dd.elements[d_o.name] = dd.elements[d_o.index = dd.elements.length] = d_o;
	if (d_p) d_p.copies[d_o.name] = d_p.copies[d_p.copies.length] = d_o;
};

dd.mkWzDom = function()
{
	var d_o, d_i = dd.elements.length; while(d_i--) dd.getParent(dd.elements[d_i]);
	d_i = dd.elements.length; while(d_i--)
	{
		d_o = dd.elements[d_i];
		if (d_o.children && !d_o.parent)
		{
			var d_j = d_o.children.length; while(d_j--)
				d_o.children[d_j].setZ(d_o.z+d_o.children[d_j].z, 1);
		}
	}
};

dd.addProps = function(d_o)
{
	var d_i, d_c;
	if (d_o.is_image)
	{
		d_o.div = dd.getDiv(d_o.id);
		d_o.css = (d_o.div && typeof d_o.div.style != dd_u)? d_o.div.style : null;
		d_o.nimg = (dd.n4 && d_o.div)? d_o.div.document.images[0] : (document.images[d_o.id+'NImG'] || null);
		if (!d_o.noalt && !dd.noalt && d_o.nimg && d_o.oimg)
		{
			d_o.nimg.alt = d_o.oimg.alt || '';
		    if (d_o.oimg.title) d_o.nimg.title = d_o.oimg.title;
		}
		d_o.bgColor = '';
	}
	else
	{
		d_o.bgColor = dd.getCssProp(d_o.div, 'background-color','backgroundColor','bgColor').toLowerCase();
		if (dd.n6 && d_o.div)
		{
			if ((d_c = d_o.bgColor).indexOf('rgb') >= 0)
			{
				d_c = d_c.substring(4, d_c.length-1).split(',');
				d_o.bgColor = '#';
				for (d_i = 0; d_i < d_c.length; d_i++) d_o.bgColor += parseInt(d_c[d_i]).toString(0x10);
			}
			else d_o.bgColor = d_c;
		}
	}
	if (dd.scalable) d_o.scalable = d_o.resizable^1;
	else if (dd.resizable) d_o.resizable = d_o.scalable^1;
	d_o.setZ(d_o.defz);
	d_o.cursor = d_o.cursor || dd.cursor || 'auto';
	d_o._setCrs(d_o.nodrag? 'auto' : d_o.cursor);
	d_o.diaphan = d_o.diaphan || dd.diaphan || 0;
	d_o.opacity = 1.0;
	if (dd.ie && !dd.iemac && d_o.div && d_o.div.style)
		d_o.div.style.filter = "Alpha(opacity=100)";
	d_o.visible = true;
};

dd.initz = function()
{
	if (!(dd && (dd.n4 || dd.n6 || dd.ie || dd.op || dd.w3c))) return;
	if (dd.op6) WINSZ(2);
	else if (dd.n6 || dd.ie || dd.op && !dd.op6 || dd.w3c) dd.recalc(1);
	var d_drag = (document.onmousemove == DRAG),
	d_resize = (document.onmousemove == RESIZE);
	if (dd.loadFunc) dd.loadFunc();
	if (d_drag && document.onmousemove != DRAG) dd.setEvtHdl(1, DRAG);
	else if (d_resize && document.onmousemove != RESIZE) dd.setEvtHdl(1, RESIZE);
	if ((d_drag || d_resize) && document.onmouseup != DROP) dd.setEvtHdl(2, DROP);
	dd.setEvtHdl(0, PICK);
};

dd.finlz = function()
{
	if (dd.ie && dd.elements)
	{
		var d_i = dd.elements.length; while (d_i--)
			dd.elements[d_i].del();
	}
};

dd.setEvtHdl = function(d_typ, d_func)
{
	if (!d_typ)
	{
		if (document.onmousedown != d_func) dd.downFunc = document.onmousedown || null;
		document.onmousedown = d_func;
	}
	else if (d_typ&1)
	{
		if (document.onmousemove != d_func) dd.moveFunc = document.onmousemove || null;
		document.onmousemove = d_func;
	}
	else
	{
		if (document.onmouseup != d_func) dd.upFunc = document.onmouseup || null;
		document.onmouseup = d_func;
	}
	if (dd.ce)
	{
		var d_e = (!d_typ)? Event.MOUSEDOWN : (d_typ&1)? Event.MOUSEMOVE : Event.MOUSEUP;
		d_func? document.captureEvents(d_e) : document.releaseEvents(d_e);
	}
};

dd.evt = function(d_e)
{
	this.but = (this.e = d_e || window.event).which || this.e.button || 0;
	this.button = (this.e.type == 'mousedown')? this.but
		: (dd.e && dd.e.button)? dd.e.button
		: 0;
	this.src = this.e.target || this.e.srcElement || null;
	this.src.tag = ("" + (this.src.tagName || this.src)).toLowerCase();
	this.x = dd.Int(this.e.pageX || this.e.clientX || 0);
	this.y = dd.Int(this.e.pageY || this.e.clientY || 0);
	if (dd.ie)
	{
		this.x += dd.getScrollX() - (dd.ie && !dd.iemac)*1;
		this.y += dd.getScrollY() - (dd.ie && !dd.iemac)*1;
	}
	this.modifKey = this.e.modifiers? this.e.modifiers&Event.SHIFT_MASK : (this.e.shiftKey || false);
};

dd.recalc = function(d_x)
{
	var d_o, d_i = dd.elements.length;
	while(d_i--)
	{
		if (!(d_o = dd.elements[d_i]).is_image && d_o.div)
		{
			dd.getWH(d_o);
			if (d_o.div.pos_rel)
			{
				dd.getPageXY(d_o.div);
				var d_dx = dd.x - d_o.x, d_dy = dd.y - d_o.y;
				d_o.defx += d_dx;
				d_o.x += d_dx;
				d_o.defy += d_dy;
				d_o.y += d_dy;
				var d_p, d_j = d_o.children.length; while(d_j--)
				{
					if (!(d_p = d_o.children[d_j]).detached && (d_o != d_p.defparent || !(d_p.is_image && dd.getImg(d_p, d_p.oimg.name, 1))))
					{
						d_p.defx += d_dx;
						d_p.defy += d_dy;
						d_p.moveBy(d_dx, d_dy);
					}
				}
			}
		}
		else if (d_o.is_image && !dd.op6 && !dd.n4)
		{
			if (dd.n6 && d_x && !d_o.defw) d_o.resizeTo(d_o.defw = dd.getImgW(d_o.oimg), d_o.defh = dd.getImgH(d_o.oimg));
			var d_defx = d_o.defx, d_defy = d_o.defy;
			if (!(d_o.parent && d_o.parent != d_o.defparent) && (d_x || !d_o.detached || d_o.horizontal || d_o.vertical) && dd.getImg(d_o, d_o.oimg.name, 1))
				d_o.moveBy(d_o.defx-d_defx, d_o.defy-d_defy);
		}
	}
};



function WINSZ(d_x)
{
	if (d_x)
	{
		if (dd.n4 || dd.op6 && d_x&2)
		{
			dd.iW = innerWidth;
			dd.iH = innerHeight;
			if (dd.op6) setTimeout("WINSZ()", 0x1ff);
		}
		window.onresize = new Function('WINSZ();');
	}
	else if ((dd.n4 || dd.op6) && (innerWidth != dd.iW || innerHeight != dd.iH)) location.reload();
	else if (dd.op6) setTimeout("WINSZ()", 0x1ff);
	else if (!dd.n4) setTimeout('dd.recalc()', 0xa);
}
//WB-WINSZ(1);



function DDObj(d_o, d_i)
{
	this.id = d_o;
	this.cmd = '';
	this.cpy_n = dd.getCmdVal(this, COPY);
	this.maxoffb = dd.getCmdVal(this, MAXOFFBOTTOM, 0, 1);
	this.maxoffl = dd.getCmdVal(this, MAXOFFLEFT, 0, 1);
	this.maxoffr = dd.getCmdVal(this, MAXOFFRIGHT, 0, 1);
	this.maxofft = dd.getCmdVal(this, MAXOFFTOP, 0, 1);
	var d_j = dd_cursors.length; while(d_j--)
		if (dd.getCmd(this, dd_cursors[d_j], 1)) this.cursor = dd_cursors[d_j].substring(2);
	this.clone = dd.getCmd(this, CLONE, 1);
	this.detach = dd.getCmd(this, DETACH_CHILDREN);
	this.scalable = dd.getCmd(this, SCALABLE, 1);
	this.horizontal = dd.getCmd(this, HORIZONTAL);
	this.noalt = dd.getCmd(this, NO_ALT, 1);
	this.nodrag = dd.getCmd(this, NO_DRAG);
	this.scroll = dd.getCmd(this, SCROLL, 1);
	this.resizable = dd.getCmd(this, RESIZABLE, 1);
	this.re_z = dd.getCmd(this, RESET_Z, 1);
	this.diaphan = dd.getCmd(this, TRANSPARENT, 1);
	this.vertical = dd.getCmd(this, VERTICAL);
	this.maxw = dd.getCmdVal(this, MAXWIDTH, 1, 1);
	this.minw = Math.abs(dd.getCmdVal(this, MINWIDTH, 1, 1));
	this.maxh = dd.getCmdVal(this, MAXHEIGHT, 1, 1);
	this.minh = Math.abs(dd.getCmdVal(this, MINHEIGHT, 1, 1));

	this.name = this.id + (d_i || '');
	this.oimg = dd.getImg(this, this.id, 1);
	this.is_image = !!this.oimg;
	this.copies = new Array();
	this.children = new Array();
	this.parent = this.original = null;
	if (this.oimg)
	{
		this.id = this.name + 'div';
		this.w = dd.getImgW(this.oimg);
		this.h = dd.getImgH(this.oimg);
		this.dw = this.dh = 0;
		this.defz = dd.Int(dd.getCssProp(this.oimg, 'z-index','zIndex','zIndex')) || 1;
		this.defsrc = this.src = this.oimg.src;
		this.htm = '<img name="' + this.id + 'NImG"'+
			' src="' + this.oimg.src + '" '+
			'width="' + this.w + '" height="' + this.h + '">';
		this.t_htm = '<div id="' + this.id +
			'" style="position:absolute;'+
			'left:' + (this.cssx = this.x = this.defx) + 'px;'+
			'top:' + (this.cssy = this.y = this.defy) + 'px;'+
			'width:' + this.w + 'px;'+
			'height:' + this.h + 'px;">'+
			this.htm + '<\/div>';
	}
	else
	{
		if (!!(this.div = dd.getDiv(this.id)) && typeof this.div.style != dd_u) this.css = this.div.style;
		dd.getWH(this);
		if (this.div)
		{
			this.div.ddObj = this;
			this.div.pos_rel = ("" + (this.div.parentNode? this.div.parentNode.tagName : this.div.parentElement? this.div.parentElement.tagName : '').toLowerCase().indexOf('body') < 0);
		}
		dd.getPageXY(this.div);
		this.defx = this.x = dd.x;
		this.defy = this.y = dd.y;
		dd.getCssXY(this);
		this.defz = dd.Int(dd.getCssProp(this.div, 'z-index','zIndex','zIndex'));
	}
	this.defw = this.w || 0;
	this.defh = this.h || 0;
}

DDObj.prototype.moveBy = function(d_x, d_y, d_kds, d_o)
{
	if (!this.div) return;
	this.x += (d_x = dd.Int(d_x));
	this.y += (d_y = dd.Int(d_y));
	if (!d_kds || this.is_image || this.parent != this.defparent)
	{
		(d_o = this.css || this.div).left = (this.cssx += d_x) + dd.px;
		d_o.top = (this.cssy += d_y) + dd.px;
	}
	var d_i = this.children.length; while (d_i--)
	{
		if (!(d_o = this.children[d_i]).detached) d_o.moveBy(d_x, d_y, 1);
		d_o.defx += d_x;
		d_o.defy += d_y;
	}
};

DDObj.prototype.moveTo = function(d_x, d_y)
{	
	this.moveBy(dd.Int(d_x)-this.x, dd.Int(d_y)-this.y);
};

DDObj.prototype.hide = function(d_m, d_o, d_p)
{
	if (this.div && this.visible)
	{
		d_p = this.css || this.div;
		if (d_m && !dd.n4)
		{
			this.display = dd.getCssProp(this.div, "display", "display", "display");
			if (this.oimg)
			{
				this.oimg.display = dd.getCssProp(this.oimg, "display", "display", "display");
				this.oimg.style.display = "none";
			}
			d_p.display = "none";
			dd.recalc();
		}
		else d_p.visibility = "hidden";
	}
	this.visible = false;
	var d_i = this.children.length; while (d_i--)
		if (!(d_o = this.children[d_i]).detached) d_o.hide(d_m);
};

DDObj.prototype.show = function(d_o, d_p)
{
	if (this.div)
	{
		d_p = this.css || this.div;
		if (d_p.display && d_p.display == "none")
		{
			d_p.display = this.display || "block";
			if (this.oimg) this.oimg.style.display = this.oimg.display || "inline";
			dd.recalc();
		}
		else d_p.visibility = "visible";
	}
	this.visible = true;
	var d_i = this.children.length; while (d_i--)
		if (!(d_o = this.children[d_i]).detached) d_o.show();
};

DDObj.prototype.resizeTo = function(d_w, d_h, d_o)
{
	if (!this.div) return;
	d_w = (this.w = dd.Int(d_w))-this.dw;
	d_h = (this.h = dd.Int(d_h))-this.dh;
	if (dd.n4)
	{
		this.div.resizeTo(d_w, d_h);
		if (this.is_image)
		{
			this.write('<img src="' + this.src + '" width="' + d_w + '" height="' + d_h + '">');
			(this.nimg = this.div.document.images[0]).src = this.src;
		}
	}
	else if (typeof this.css.pixelWidth != dd_u)
	{
		this.css.pixelWidth = d_w;
		this.css.pixelHeight = d_h;
		if (this.is_image)
		{
			(d_o = this.nimg.style).pixelWidth = d_w;
			d_o.pixelHeight = d_h;
		}
	}
	else
	{
		this.css.width = d_w + dd.px;
		this.css.height = d_h + dd.px;
		if (this.is_image)
		{
			(d_o = this.nimg).width = d_w;
			d_o.height = d_h;
			if (!d_o.complete) d_o.src = this.src;
		}
	}
};

DDObj.prototype.resizeBy = function(d_dw, d_dh)
{
	this.resizeTo(this.w+dd.Int(d_dw), this.h+dd.Int(d_dh));
};

DDObj.prototype.swapImage = function(d_x, d_cp)
{
	if (!this.nimg) return;
	this.nimg.src = d_x;
	this.src = this.nimg.src;
	if (d_cp)
	{
		var d_i = this.copies.length; while (d_i--)
			this.copies[d_i].src = this.copies[d_i].nimg.src = this.nimg.src;
	}
};

DDObj.prototype.setBgColor = function(d_x)
{
	if (dd.n4 && this.div) this.div.bgColor = d_x;
	else if (this.css) this.css.background = d_x;
	this.bgColor = d_x;
};

DDObj.prototype.write = function(d_x, d_o)
{
	this.text = d_x;
	if (!this.div) return;
	if (dd.n4)
	{
		(d_o = this.div.document).open();
		d_o.write(d_x);
		d_o.close();
		dd.getWH(this);
	}
	else if (!dd.op6)
	{
		this.css.height = 'auto';
		this.div.innerHTML = d_x;
		if (!dd.ie4) dd.recalc();
		if (dd.ie4 || dd.n6) setTimeout('dd.recalc();', 0); // n6.0: recalc twice
	}
};

DDObj.prototype.copy = function(d_n, d_p)
{
	if (!this.oimg) return;
	d_n = d_n || 1;
	while (d_n--)
	{
		var d_l = this.copies.length,
		d_o = new DDObj(this.name+this.cmd, d_l+1);
		if (dd.n4)
		{
			d_o.id = (d_p = new Layer(d_o.w)).name;
			d_p.clip.height = d_o.h;
			d_p.visibility = 'show';
			(d_p = d_p.document).open();
			d_p.write(d_o.htm);
			d_p.close();
		}
		else if (dd.db.insertAdjacentHTML) dd.db.insertAdjacentHTML("AfterBegin", d_o.t_htm);
		else if (document.createElement && dd.db && dd.db.appendChild)
		{
			dd.db.appendChild(d_p = document.createElement('div'));
			d_p.innerHTML = d_o.htm;
			d_p.id = d_o.id;
			d_p.style.position = 'absolute';
			d_p.style.width = d_o.w + 'px';
			d_p.style.height = d_o.h + 'px';
		}
		else if (dd.db && dd.db.innerHTML) dd.db.innerHTML += d_o.t_htm;
		d_o.defz = this.defz+1+d_l;
		dd.addProps(d_o);
		d_o.original = this;
		dd.addElt(d_o, this);
		if (this.parent)
		{
			this.parent.addChild(d_o, this.detached);
			d_o.defparent = this.defparent;
		}
		//	alert ("moveTo1");
		d_o.moveTo(d_o.defx = this.defx, d_o.defy = this.defy);
		if (dd.n4) d_o.defsrc = d_o.src = this.defsrc;
		d_o.swapImage(this.src);
	}
};

DDObj.prototype.addChild = function(d_kd, detach, defp)
{
	if (typeof d_kd != "object") d_kd = dd.elements[d_kd];
	if (d_kd.parent && d_kd.parent == this || d_kd == this || !d_kd.is_image && d_kd.defparent && !defp) return;

	this.children[this.children.length] = this.children[d_kd.name] = d_kd;
	d_kd.detached = detach || 0;
	if (defp) d_kd.defparent = this;
	else if (this == d_kd.defparent && d_kd.is_image) dd.getImg(this, d_kd.oimg.name, 1);
	if (!d_kd.defparent || this != d_kd.defparent)
	{
		d_kd.defx = d_kd.x;
		d_kd.defy = d_kd.y;
	}
	if (!detach)
	{
		d_kd.defz = d_kd.defz+this.defz-(d_kd.parent? d_kd.parent.defz : 0)+(!d_kd.is_image*1);
		d_kd.setZ(d_kd.z+this.z-(d_kd.parent? d_kd.parent.z : 0)+(!d_kd.is_image*1), 1);
	}
	if (d_kd.parent) d_kd.parent._removeChild(d_kd, 1);
	d_kd.parent = this;
};

DDObj.prototype._removeChild = function(d_kd, d_newp)
{
	if (typeof d_kd != "object") d_kd = this.children[d_kd];
	var d_oc = this.children, d_nc = new Array();
	for (var d_i = 0; d_i < d_oc.length; d_i++)
		if (d_oc[d_i] != d_kd) d_nc[d_nc.length] = d_oc[d_i];
	this.children = d_nc;
	d_kd.parent = null;
	if (!d_newp)
	{
		d_kd.detached = d_kd.defp = 0;
		if (d_kd.is_image) dd.getImg(d_kd, d_kd.oimg.name, 1);
	}
};

DDObj.prototype.attachChild = function(d_kd)
{
	(d_kd = (typeof d_kd != "object")? this.children[d_kd]: d_kd).detached = 0;
	d_kd.setZ(d_kd.defz + this.z-this.defz, 1);
};

DDObj.prototype.detachChild = function(d_kd)
{
	(d_kd = (typeof d_kd != "object")? this.children[d_kd]: d_kd).detached = 1;
};

DDObj.prototype.setZ = function(d_x, d_kds, d_o)
{
	if (d_kds)
	{
		var d_dz = d_x-this.z,
		d_i = this.children.length; while (d_i--)
			if (!(d_o = this.children[d_i]).detached) d_o.setZ(d_o.z+d_dz, 1);
	}
	dd.z = Math.max(dd.z, this.z = this.div? ((this.css || this.div).zIndex = d_x) : 0);
};

DDObj.prototype.maximizeZ = function()
{
	this.setZ(dd.z+1, 1);
};

DDObj.prototype._resetZ = function(d_o)
{
	if (this.re_z || dd.re_z)
	{
		this.setZ(this.defz);
		var d_i = this.children.length; while (d_i--)
			if (!(d_o = this.children[d_i]).detached) d_o.setZ(d_o.defz);
	}
};

DDObj.prototype.setOpacity = function(d_x)
{
	this.opacity = d_x;
	this._setOpaRel(1.0, 1);
};

DDObj.prototype._setOpaRel = function(d_x, d_kd, d_y, d_o)
{
	if (this.diaphan || d_kd)
	{
		d_y = this.opacity*d_x;
		if (dd.n6) this.css.MozOpacity = d_y;
		else if (dd.ie && !dd.iemac)
		{
			if (this.css)
				this.css.filter = "Alpha(opacity="+parseInt(100*d_y)+")";
		}
		else if (this.css) this.css.opacity = d_y;
		var d_i = this.children.length; while (d_i--)
			if (!(d_o = this.children[d_i]).detached) d_o._setOpaRel(d_x, 1);
	}
};

DDObj.prototype.setCursor = function(d_x)
{
	this._setCrs(this.cursor = (d_x.indexOf('c:')+1)? d_x.substring(2) : d_x);
};

DDObj.prototype._setCrs = function(d_x)
{
	if (this.css) this.css.cursor = ((!dd.ie || dd.iemac) && d_x == 'hand')? 'pointer' : d_x;
};

DDObj.prototype.setDraggable = function(d_x)
{
	this.nodrag = !d_x*1;
	this._setCrs(d_x? this.cursor : 'auto');
};

DDObj.prototype.setResizable = function(d_x)
{
	this.resizable = d_x*1;
	if (d_x) this.scalable = 0;
};

DDObj.prototype.setScalable = function(d_x)
{
	this.scalable = d_x*1;
	if (d_x) this.resizable = 0;
};

DDObj.prototype.del = function(d_os, d_o)
{
	var d_i;
	if (this.parent && this.parent._removeChild) this.parent._removeChild(this);
	if (this.original)
	{
		this.hide();
		if (this.original.copies)
		{
			d_os = new Array();
			for (d_i = 0; d_i < this.original.copies.length; d_i++)
				if ((d_o = this.original.copies[d_i]) != this) d_os[d_o.name] = d_os[d_os.length] = d_o;
			this.original.copies = d_os;
		}
	}
	else if (this.is_image)
	{
		this.hide();
		if (this.oimg)
		{
		  if (dd.n4) this.oimg.src = this.defsrc;
		  else this.oimg.style.visibility = 'visible';
		}
	}
	else if (this.moveTo)
	{
		if (this.css) this.css.cursor = 'default';
			//alert ("moveTo2");
		this.moveTo(this.defx, this.defy);
		this.resizeTo(this.defw, this.defh);
	}
	d_os = new Array();
	for (d_i = 0; d_i < dd.elements.length; d_i++)
	{
		if ((d_o = dd.elements[d_i]) != this) d_os[d_o.name] = d_os[d_o.index = d_os.length] = d_o;
		else d_o._free();
	}
	dd.elements = d_os;
//WB-	if (!dd.op6 && !dd.n4) dd.recalc();
};

DDObj.prototype._free = function()
{
	for (var d_i in this)
		this[d_i] = null;
	dd.elements[this.name] = null;
};



dd.n4RectVis = function(vis)
{
	for (var d_i = 4; d_i--;)
	{
		dd.rectI[d_i].visibility = dd.rectA[d_i].visibility = vis? 'show' : 'hide';
		if (vis) dd.rectI[d_i].zIndex = dd.rectA[d_i].zIndex = dd.z+2;
	}
};

dd.n4RectPos = function(d_o, d_x, d_y, d_w, d_h)
{
	d_o.x = d_x;
	d_o.y = d_y;
	d_o.clip.width = d_w;
	d_o.clip.height = d_h;
};

// NN4: draw img resize rectangle
dd.n4Rect = function(d_w, d_h)
{
	var d_i;
	if (!dd.rectI)
	{
		dd.rectI = new Array();
		dd.rectA = new Array();
	}
	if (!dd.rectI[0])
	{
		for (d_i = 4; d_i--;)
		{
			(dd.rectI[d_i] = new Layer(1)).bgColor = '#000000';
			(dd.rectA[d_i] = new Layer(1)).bgColor = '#ffffff';
		}
	}
	if (!dd.rectI[0].visibility || dd.rectI[0].visibility == 'hide') dd.n4RectVis(1);
	dd.obj.w = d_w;
	dd.obj.h = d_h;
	for (d_i = 4; d_i--;)
	{
		dd.n4RectPos(dd.rectI[d_i], dd.obj.x + (!(d_i-1)? (dd.obj.w-1) : 0), dd.obj.y + (!(d_i-2)? (dd.obj.h-1) : 0), d_i&1 || dd.obj.w, !(d_i&1) || dd.obj.h);
		dd.n4RectPos(dd.rectA[d_i], !(d_i-1)? dd.rectI[1].x+1 : (dd.obj.x-1), !(d_i-2)? dd.rectI[2].y+1 : (dd.obj.y-1), d_i&1 || dd.obj.w+2, !(d_i&1) || dd.obj.h+2);
	}
};

dd.reszTo = function(d_w, d_h)
{
	if (dd.n4 && dd.obj.is_image) dd.n4Rect(d_w, d_h);
	else dd.obj.resizeTo(d_w, d_h);
};

dd.embedVis = function(d_vis)
{
	var d_o = new Array('iframe', 'applet', 'embed', 'object');
	var d_i = d_o.length; while (d_i--)
	{
		var d_p = dd.ie? document.all.tags(d_o[d_i]) : document.getElementsByTagName? document.getElementsByTagName(d_o[d_i]) : null;
		if (d_p)
		{
			var d_j = d_p.length; while (d_j--)
			{
				var d_q = d_p[d_j];
				while (d_q.offsetParent || d_q.parentNode)
				{
					if ((d_q = d_q.parentNode || d_q.offsetParent || null) == dd.obj.div)
					{
						d_p[d_j].style.visibility = d_vis;
						break;
					}
				}
			}
		}
	}
};

dd.maxOffX = function(d_x, d_y)
{
	return (
		(dd.obj.maxoffl+1 && (d_y = dd.obj.defx-dd.obj.maxoffl)-d_x > 0
		|| dd.obj.maxoffr+1 && (d_y = dd.obj.defx+dd.obj.maxoffr)-d_x < 0)? d_y
		: d_x
	);
};

dd.maxOffY = function(d_x, d_y)
{
	return (
		(dd.obj.maxofft+1 && (d_y = dd.obj.defy-dd.obj.maxofft)-d_x > 0
		|| dd.obj.maxoffb+1 && (d_y = dd.obj.defy+dd.obj.maxoffb)-d_x < 0)? d_y
		: d_x
	);
};

dd.inWndW = function(d_x, d_y)
{
	var d_wx = dd.getScrollX(),
	d_ww = dd.getWndW();
	return (
		((d_y = d_wx+2)-d_x > 0) || ((d_y = d_wx+d_ww+dd.obj.w-2)-d_x < 0)? d_y
		: d_x
	);
};

dd.inWndH = function(d_x, d_y)
{
	var d_wy = dd.getScrollY(),
	d_wh = dd.getWndH();
	return (
		((d_y = d_wy+2)-d_x > 0) || ((d_y = d_wy+d_wh+dd.obj.h-2)-d_x < 0)? d_y
		: d_x
	);
};

// These two funcs limit the size of element when mouseresized.
// Implemented 22.5.2003 by Gregor L�tolf <gregor@milou.ch>, modified by Walter Zorn
dd.limW = function(d_w)
{
	return (
		(dd.obj.minw-d_w > 0)? dd.obj.minw
		: (dd.obj.maxw > 0 && dd.obj.maxw-d_w < 0)? dd.obj.maxw
		: d_w
	);
};

dd.limH = function(d_h)
{
	return (
		(dd.obj.minh-d_h > 0)? dd.obj.minh
		: (dd.obj.maxh > 0 && dd.obj.maxh-d_h < 0)? dd.obj.maxh
		: d_h
	);
};


// Optional autoscroll-page functionality. Courtesy Cedric Savarese.
// Implemented by Walter Zorn
function DDScroll()
{
	if (!dd.obj || !dd.obj.scroll && !dd.scroll || dd.op || dd.ie4 || dd.whratio)
	{
		dd.scrx = dd.scry = 0;
		return;
	}
	var d_bnd = 0x1c,
	d_wx = dd.getScrollX(), d_wy = dd.getScrollY();
	if (dd.msmoved)
	{
		var d_ww = dd.getWndW(), d_wh = dd.getWndH(), d_y;
		dd.scrx = ((d_y = dd.e.x-d_ww-d_wx+d_bnd) > 0)? (d_y>>=2)*d_y
			: ((d_y = d_wx+d_bnd-dd.e.x) > 0)? -(d_y>>=2)*d_y
			: 0;
		dd.scry = ((d_y = dd.e.y-d_wh-d_wy+d_bnd) > 0)? (d_y>>=2)*d_y
			: ((d_y = d_wy+d_bnd-dd.e.y) > 0)? -(d_y>>=2)*d_y
			: 0;
	}
	if (dd.scrx || dd.scry)
	{
		window.scrollTo(
			d_wx + (dd.scrx = dd.obj.is_resized? dd.limW(dd.obj.w+dd.scrx)-dd.obj.w : dd.obj.vertical? 0 : (dd.maxOffX(dd.obj.x+dd.scrx)-dd.obj.x)),
			d_wy + (dd.scry = dd.obj.is_resized? dd.limH(dd.obj.h+dd.scry)-dd.obj.h : dd.obj.horizontal? 0 : (dd.maxOffY(dd.obj.y+dd.scry)-dd.obj.y))
		);
			//alert ("moveTo3");
		dd.obj.is_dragged? dd.obj.moveTo(dd.obj.x+dd.getScrollX()-d_wx, dd.obj.y+dd.getScrollY()-d_wy)
			: dd.reszTo(dd.obj.w+dd.getScrollX()-d_wx, dd.obj.h+dd.getScrollY()-d_wy);
	}
	dd.msmoved = 0;
	window.setTimeout('DDScroll()', 0x33);
}



function PICK(d_ev)
{
	dd.e = new dd.evt(d_ev);
	if (dd.e.x >= dd.getWndW()+dd.getScrollX() || dd.e.y >= dd.getWndH()+dd.getScrollY()) return true; // on scrollbar
	var d_o, d_cmp = -1, d_i = dd.elements.length; while (d_i--)
	{
		d_o = dd.elements[d_i];
		if (dd.n4 && dd.e.but > 1 && dd.e.src == d_o.oimg && !d_o.clone) return false;
		if (d_o.visible && dd.e.but <= 1 && dd.e.x >= d_o.x && dd.e.x <= d_o.x+d_o.w && dd.e.y >= d_o.y && dd.e.y <= d_o.y+d_o.h)
		{
			if (d_o.z > d_cmp && dd.e.src.tag.indexOf('input') < 0 && dd.e.src.tag.indexOf('textarea') < 0 && dd.e.src.tag.indexOf('select') < 0 && dd.e.src.tag.indexOf('option') < 0)
			{
				d_cmp = d_o.z;
				dd.obj = d_o;
			}
		}
	}
	if (dd.obj)
	{
		if (dd.obj.nodrag) dd.obj = null;
		else
		{
			dd.e.e.cancelBubble = true;
			var d_rsz = dd.e.modifKey && (dd.obj.resizable || dd.obj.scalable);
			if (dd.op && !dd.op6)
			{
				(d_o = document.getElementById('OpBlUr')).style.pixelLeft = dd.e.x;
				d_o.style.pixelTop = dd.e.y;
				(d_o = d_o.children[0].children[0]).focus();
				d_o.blur();
			}
			else if (dd.ie && !dd.ie4)
			{
				if (document.selection && document.selection.empty) document.selection.empty();
				dd.db.onselectstart = function()
				{
					event.returnValue = false;
				};
			}
			if (d_rsz)
			{
				dd.obj._setCrs('se-resize');
				dd.obj.is_resized = 1;
				dd.whratio = dd.obj.scalable? dd.obj.defw/dd.obj.defh : 0;
				if (dd.ie)
				{
					if (dd.ie4)
					{
						window.dd_x = dd.getScrollX();
						window.dd_y = dd.getScrollY();
					}
					setTimeout(
						'if (dd.obj && document.selection && document.selection.empty)'+
						'{'+
							'document.selection.empty();'+
							'if (dd.ie4) window.scrollTo(window.dd_x, window.dd_y);'+
						'}'
					,0);
				}
				dd.setEvtHdl(1, RESIZE);
				dd.reszTo(dd.obj.w, dd.obj.h);
			}
			else
			{
				dd.obj.is_dragged = 1;
				dd.setEvtHdl(1, DRAG);
			}
			dd.setEvtHdl(2, DROP);
			dd.embedVis('hidden');
			dd.obj._setOpaRel(0.7);
			dd.obj.maximizeZ();
			dd.ofx = dd.obj.x+dd.obj.w-dd.e.x;
			dd.ofy = dd.obj.y+dd.obj.h-dd.e.y;
			if (window.my_PickFunc) my_PickFunc();
			DDScroll();
			return !(
				dd.obj.is_resized
				|| dd.n4 && dd.obj.is_image
				|| dd.n6 || dd.w3c
			);
		}
	}
	if (dd.downFunc) return dd.downFunc(d_ev);
	return true;
}

function DRAG(d_ev)
{
	if (!dd.obj || !dd.obj.visible) return true;
	if (dd.ie4 || dd.w3c || dd.n6 || dd.obj.children.length > 0xf)
	{
		if (dd.wait) return false;
		dd.wait = 1;
		setTimeout('dd.wait = 0;', dd.tiv);
	}
	dd.e = new dd.evt(d_ev);
	if (dd.ie && !dd.e.but)
	{
		DROP(d_ev);
		return true;
	}
	dd.msmoved = 1;
	//	alert ("moveTo4");
	dd.obj.moveTo(
		dd.obj.vertical? dd.obj.x : dd.maxOffX(dd.inWndW(dd.ofx+dd.e.x)-dd.obj.w),
		dd.obj.horizontal? dd.obj.y : dd.maxOffY(dd.inWndH(dd.ofy+dd.e.y)-dd.obj.h)
	);

	if (window.my_DragFunc) my_DragFunc();
	return false;
}

function RESIZE(d_ev)
{
	if (!dd.obj || !dd.obj.visible) return true;
	if (dd.wait) return false;
	dd.wait = 1;
	setTimeout('dd.wait = 0;', dd.tiv);
	dd.e = new dd.evt(d_ev);
	if (dd.ie && !dd.e.but)
	{
		DROP(d_ev);
		return true;
	}
	dd.msmoved = 1;
	var d_w = dd.limW(dd.inWndW(dd.ofx+dd.e.x)-dd.obj.x), d_h;
	if (!dd.whratio) d_h = dd.limH(dd.inWndH(dd.ofy+dd.e.y)-dd.obj.y);
	else
	{
		d_h = dd.limH(dd.inWndH(Math.round(d_w/dd.whratio)+dd.obj.y)-dd.obj.y);
		d_w = Math.round(d_h*dd.whratio);
	}
	dd.reszTo(d_w, d_h);
	if (window.my_ResizeFunc) my_ResizeFunc();
	return false;
}

function DROP(d_ev)
{
	if (dd.obj)
	{
		if (dd.obj.is_dragged)
		{
			if (!dd.obj.is_image) dd.getWH(dd.obj);
		}
		else if (dd.n4)
		{
			if (dd.obj.is_image)
			{
				dd.n4RectVis(0);
				dd.obj.resizeTo(dd.obj.w, dd.obj.h);
			}
		}
		if (!dd.n4 && !dd.op6 || !dd.obj.is_image) dd.recalc();
		dd.setEvtHdl(1, dd.moveFunc);
		dd.setEvtHdl(2, dd.upFunc);
		if (dd.db) dd.db.onselectstart = null;
		dd.obj._setOpaRel(1.0);
		dd.obj._setCrs(dd.obj.cursor);
		dd.embedVis('visible');
		dd.obj._resetZ();
		if (window.my_DropFunc)
		{
			dd.e = new dd.evt(d_ev);
			my_DropFunc();
		}
		dd.msmoved = dd.obj.is_dragged = dd.obj.is_resized = dd.whratio = 0;
		dd.obj = null;
	}
	dd.setEvtHdl(0, PICK);
}

var saveargs;		// Arguments from the DELAYED_SET_DHTML Call 

function delaySet_DHTML()
{
  WB_SET_DHTML();
  my_DropFunc();
}

function DELAYED_SET_DHTML()
{
	saveargs = arguments;	// save the arguments
	if (dd.ie)									// For IE 
	{
		document.body.onload=delaySet_DHTML;  // set message handler to procced at  load finish of the document
	}
	else
	{	
											// for all the good guys to it here 
	  WB_SET_DHTML();
	  my_DropFunc();
	}
}

function WB_SET_DHTML()
{
	var d_a = saveargs, d_ai, d_htm = '', d_o, d_i = d_a.length; 
	while (d_i--)
	{
		if (dd.op6)
		{
			var d_t0 = (new Date()).getTime();
			while ((new Date()).getTime()-d_t0 < 0x99);
		}
		if (!(d_ai = d_a[d_i]).indexOf('c:')) dd.cursor = d_ai.substring(2);
		else if (d_ai == NO_ALT) dd.noalt = 1;
		else if (d_ai == SCROLL) dd.scroll = 1;
		else if (d_ai == RESET_Z) dd.re_z = 1;
		else if (d_ai == RESIZABLE) dd.resizable = 1;
		else if (d_ai == SCALABLE) dd.scalable = 1;
		else if (d_ai == TRANSPARENT) dd.diaphan = 1;
		else
		{
			d_o = new DDObj(d_ai);
			dd.addElt(d_o);
			d_htm += d_o.t_htm || '';
			if (d_o.oimg && d_o.cpy_n)
			{
				var d_j = 0; while (d_j < d_o.cpy_n)
				{
					var d_p = new DDObj(d_o.name+d_o.cmd, ++d_j);
					dd.addElt(d_p, d_o);
					d_p.defz = d_o.defz+d_j;
					d_p.original = d_o;
					d_htm += d_p.t_htm;
				}
			}
		}
	}
/*
	if (dd.n4 || dd.n6 || dd.ie || dd.op || dd.w3c) document.write(
		(dd.n4? '<div style="position:absolute;"><\/div>\n'
		: (dd.op && !dd.op6)? '<div id="OpBlUr" style="position:absolute;visibility:hidden;width:0px;height:0px;"><form><input type="text" style="width:0px;height:0px;"><\/form><\/div>'
		: '') + d_htm
	);
*/
	dd.z = 0x33;
	d_i = dd.elements.length; while (d_i--)
	{
		dd.addProps(d_o = dd.elements[d_i]);
		if (d_o.is_image && !d_o.original && !d_o.clone)
			dd.n4? d_o.oimg.src = spacer : d_o.oimg.style.visibility = 'hidden';
	}
	dd.mkWzDom();
	if (window.onload) dd.loadFunc = window.onload;
	window.onload = dd.initz;
	window.onunload = dd.finlz;
	dd.setEvtHdl(0, PICK);
}

function ADD_DHTML(d_o) // layers only!
{
	d_o = new DDObj(d_o);
	dd.addElt(d_o);
	dd.addProps(d_o);
	dd.mkWzDom();
}




////////////////////////////////////////////////////////////
// If not needed, all code below this line may be removed


// For backward compatibility
dd.d = document;            // < v. 2.72
var RESET_ZINDEX = RESET_Z; // < 3.44
var KEYDOWN_RESIZE = RESIZABLE; // < 4.43
var CURSOR_POINTER = CURSOR_HAND; // < 4.44
var NO_SCROLL = '';         // < v. 4.49




////////////////////////////////////////////////////////////
// FUNCTIONS FOR EXTENDED SCRIPTING
// Use these for your own extensions,
// or to call functions defined elsewhere



/* my_PickFunc IS AUTOMATICALLY CALLED WHEN AN ITEM STARTS TO BE DRAGGED.
The following objects/properties are accessible from here:

- dd.e: current mouse event
- dd.e.property: access to a property of the current mouse event.
  Mostly requested properties:
  - dd.e.x: document-related x co-ordinate
  - dd.e.y: document-related y co-ord
  - dd.e.src: target of mouse event (not identical with the drag drop object itself).
  - dd.e.button: currently pressed mouse button. Left button: dd.e.button <= 1

- dd.obj: reference to currently dragged item.
- dd.obj.property: access to any property of that item.
- dd.obj.method(): for example dd.obj.resizeTo() or dd.obj.swapImage() .
  Mostly requested properties:
	- dd.obj.name: image name or layer ID passed to SET_DHTML();
	- dd.obj.x and dd.obj.y: co-ordinates;
	- dd.obj.w and dd.obj.h: size;
	- dd.obj.is_dragged: 1 while item is dragged, else 0;
	- dd.obj.is_resized: 1 while item is resized, i.e. if <ctrl> or <shift> is pressed, else 0

For more properties and details, visit the API documentation
at http://www.walterzorn.com/dragdrop/api_e.htm (english) or
http://www.walterzorn.de/dragdrop/api.htm (german)    */
function my_PickFunc()
{
}




/* my_DragFunc IS CALLED WHILE AN ITEM IS DRAGGED
See the description of my_PickFunc above for what's accessible from here. */
function my_DragFunc()
{
	//window.status = 'dd.elements.' + dd.obj.name + '.x  = ' + dd.obj.x + '     dd.elements.' + dd.obj.name + '.y = ' + dd.obj.y;
}




/* my_ResizeFunc IS CALLED WHILE AN ITEM IS RESIZED
See the description of my_PickFunc above for what's accessible from here. */
function my_ResizeFunc()
{
	//window.status = 'dd.elements.' + dd.obj.name + '.w  = ' + dd.obj.w + '     dd.elements.' + dd.obj.name + '.h = ' + dd.obj.h;
}




/* THIS ONE IS CALLED ONCE AN ITEM IS DROPPED
See the description of my_PickFunc for what's accessible from here.
Here may be investigated, for example, what's the name (dd.obj.name)
of the dropped item, and where (dd.obj.x, dd.obj.y) it has been dropped... */

// WB+
function sortByElementY(obja,objb)
{
  return dd.elements[obja].y -dd.elements[objb].y;
}


// [NEW] additional function to determine the left offset recursively

function getLeft(l)
{
  if (l.offsetParent) return (l.offsetLeft + getLeft(l.offsetParent));
  else return (l.offsetLeft);
}

// [NEW] additional function to determine the top offset recursively


function getTop(l)
{
 if (l.offsetParent) return (l.offsetTop + getTop(l.offsetParent));
 else return (l.offsetTop);
}



function my_DropFunc()
{
  	//WB+
	
	 var tops = new Array();
	 var topsNum = new Array();
	// iterate over elements by index
	 var d_i ;
	 var draggedObj = dd.obj;

	 // [NEW] the right group offset	
	 var defgroup1x = getLeft(document.getElementById("group1"));
	 var defgroup1y = getTop(document.getElementById("group1"));

	 var  def1x=defgroup1x + document.getElementById("header1").offsetLeft;
	 var  def1h=document.getElementById("header1").scrollHeight;	 
	 var  def2x=defgroup1x+document.getElementById("header2").offsetLeft;
	 var  def3x=defgroup1x+document.getElementById("header3").offsetLeft;
	 var  defDelx=defgroup1x+document.getElementById("headerDel").offsetLeft;

	 if(draggedObj)
	 {				
 	           //alert ("obj found");
		   //document.returnValue = (sprintf("drop id=%s, draggedObj.index=%d, draggedObj.x=%d, draggedObj.y=%d" ,dd.e.src.id, draggedObj.index, draggedObj.x, draggedObj.y));
		  //dd.e.src.innerText = (sprintf("drop id=%s, .i=%d, .x=%d, .y=%d" ,dd.e.src.id, draggedObj.index, draggedObj.x, draggedObj.y));

		  if(draggedObj.x > defDelx && draggedObj.div.className!="liststyleNew" )		// Deletes all, including Groups but not the new Page Object
		  { 
			draggedObj.defx = defDelx;
			draggedObj.div.className="liststyleDelete";
	
		  }
		  else
		  {
			  if(draggedObj.div.className=="liststyleNew" ) 
			   if(draggedObj.id=="topNew") {
			  	var p = prompt(smmPromptText,smmText);
				if(p != null)
				{
			     draggedObj.div.firstChild.nodeValue = p;
				 draggedObj.defh=def1h;
 				 draggedObj.h=def1h;
				}
				else
				{
			
				  draggedObj.moveTo(defDelx,draggedObj.defy);
			//	  draggedObj.defx = 450;
			//	  draggedObj.x=450;
				  return;	// !!!!!!!!!!!!PREMATURE return, do change the New Page object or any order of the others
				} 
			  }
			  if(draggedObj.id.indexOf('top') >=0) {	 
				  if(draggedObj.x > def3x ) {
					draggedObj.defx = def3x;
					draggedObj.div.className="liststyle3";
				  }
				  if(draggedObj.x < def3x ){
				   draggedObj.defx = def2x;
					draggedObj.div.className="liststyle2";
				  }
				  if(draggedObj.x < def2x ) {
				  draggedObj.defx = def1x;
					draggedObj.div.className="liststyle1";
				  }
			}
		}
	 }

	 d_i=dd.elements.length;
 
	 while(d_i--)
	 {
		tops.push (dd.elements[d_i].id);
	 }
	
	 tops.sort(sortByElementY)
	
	 var textbox = document.getElementById("arrangeResult");
	 if(textbox)
	 {
	   var first = true;
	   for (d_i=0 ;d_i<tops.length; d_i++) {
	   	 
		  if(dd.elements[tops[d_i]].id.indexOf('top') >=0 && dd.elements[tops[d_i]].div != null)		// is menu entity element ? 
		  {
	   	    var level=dd.elements[tops[d_i]].div.className.substring(9);
			if (level >=1 && level <=3 )
			{
			    if (first == true) { level = 1; first = false; }  // first element always level 1
				
				if (tops[d_i] == 'topNew') 	
					topsNum.push(tops[d_i].substring(3)+'^'+level+'^'+dd.elements[tops[d_i]].div.firstChild.nodeValue);
				else
	   				topsNum.push(tops[d_i].substring(3)+'^'+level+'^ ');
			}
		 }
	   } // end for
	 	textbox.value = topsNum.join(",");
	 }
	 else
	  alert ("dragdrop.js -  kann das arrangeResult textarea element nicht finden");
//	 alert(tops.join(","));

	 var newY = defgroup1y+80;	 
	 first = true;
	 for (d_i = 0 ; d_i < tops.length ; d_i++ )
	 {   
	 
		 if(dd.elements[tops[d_i]].defx < defDelx )
		 if( first == false )
		 {  
		      dd.elements[tops[d_i]].div.visiblity="visible";
    		      dd.elements[tops[d_i]].moveTo(dd.elements[tops[d_i]].defx, newY+=25);		
			
		  }
		  else
		    {								// disallow the first one not at level 1
					// alert ("moveTo6 " + d_i);
					dd.elements[tops[d_i]].div.className="liststyle1"
		    		dd.elements[tops[d_i]].moveTo(def1x, newY+=25);
					dd.elements[tops[d_i]].defx = def1x;
					first = false;
			}
	 }

}
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_showHideLayers() { //v6.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}
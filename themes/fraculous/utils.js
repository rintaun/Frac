var GetXmlHttpObject=function(){var xmlHttp;try{xmlHttp=new XMLHttpRequest();}catch(e){try{xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");}catch(e){try{xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");}catch(e){/* No xmlHttp */}}}return xmlHttp;};
var SendXmlHttpRequest = function(xmlHttp, url, callback, data, XML, ajax) {
	if (!xmlHttp) return;
	xmlHttp.open((data)?"POST":"GET",url,true);
	xmlHttp.setRequestHeader('User-Agent','XMLHTTP/1.0');
	if (data) {
		xmlHttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
		xmlHttp.setRequestHeader("Content-length", data.length);
		xmlHttp.setRequestHeader("Connection", "close");
	}
	xmlHttp.onreadystatechange = function(){
				if (xmlHttp.readyState != 4||(xmlHttp.status != 200 && xmlHttp.status != 304)) return;
				if (XML)callback(xmlHttp.responseXML);
				else callback(xmlHttp.responseText);
				if (ajax.update) setTimeout('SendXmlHttpRequest(ajax.xmlHttp, ajax.reqURL, ajax.oncomplete, ajax.data, ajax.xml, ajax)',ajax.update*1000);
	}
	if (xmlHttp.readyState==4) return;
	xmlHttp.send(data.replace(/~/g,'%7E').replace(/%20/g,'+'));
};
var AJAX = function(o){return {
	xmlHttp:GetXmlHttpObject(),
	url:o.url,
	hash:null,
	reqURL:null,
	data:o.data||'',
	addData:function(p,v){this.data=(this.data)?this.data+'&'+p+'='+v:p+'='+v;},
	clearData:function(){this.data='';},
	addHeader:function(p,v){this.xmlHttp.setRequestHeader(p,v);},
	onrequest:o.onrequest||_blank,
	onsuccess:o.success||_blank,
	onfail:o.onfail||_blank,
	oncomplete:o.oncomplete||_blank,
	update:o.update||false,
	request:function request(hURL){
		this.hash = hURL || '';
		this.reqURL = this.url + this.hash;
		this.onrequest();
		SendXmlHttpRequest(this.xmlHttp, this.reqURL, this.oncomplete, this.data, this.xml, this);
		},
	xml:o.xml||false
};};

var _blank=function(){};
/* Event Functions */
var addEvent=function(o,e,f) {if(document.addEventListener)o.addEventListener(e,f,false);else if(document.attachEvent)o.attachEvent('on'+e,f);};
var removeEvent=function(o,e,f) {if(document.addEventListener)o.removeEventListener(e,f,false);else if(document.attachEvent)o.detachEvent('on'+e,f);};
var $e=function(e) {
	if(! e)var e=window.event;
	var caller = (e.target ? e.target : (e.srcElement ? e.srcElement : document.body));
	return ({
		event: e,
		caller: (caller.nodeType==3 ? caller.parentNode : caller),
		code: (e.keyCode ? e.keyCode : (e.which ? e.which : null)),
		rclick:(e.button ? e.button==2 : (e.which ? e.which==3 : false)),
		delta: (e.wheelDelta ? ((window.opera?-1:1)*e.wheelDelta/120) : (e.detail ? -e.detail/3 : 0)),
		x: (e.pageX ? e.pageX : (e.clientX ? e.clientX+document.body.scrollLeft+document.documentElement.scrollLeft : 0)),
		y: (e.pageY ? e.pageY : (e.clientY ? e.clientY+document.body.scrollTop+document.documentElement.scrollTop : 0))
	});
};
var returnFalse=function(e){if(e.event)e=e.event;if(e.preventDefault)e.preventDefault();else e.returnValue=false;};

var $ = function(find, node, arr) {
	var f,i=0,len=0,e,p,ret=[];
	find=explode(',', find);
	
	if (node && !node.tagName) {
		var arr = node;
		node = document;
	}
	else if (! node) var node = document;
	while(f=find[i++]) {
		f=explode(/\s+/,$trim(f));
		p=explode('#',f.shift());
		len = p.length-1;
		p[len] = explode('.', p[len]);
	
		var j=0,list,tag=( len ? p[0] : p[len][0]) || '*';
		list = ( len ? [document.getElementById(p[len][0])] : node.getElementsByTagName(tag) );
		
		if (list[0]) while (e=list[j++])
			if ( (tag=='*' || e.tagName==tag.toUpperCase()) && (!p[len][1] || hasClass(e,p[len][1])) ) {
				e = (f.length > 0) ? $(f.join(' '),e) : e;
				e = $Element(e);
				ret=( ret[0] ? ret.concat(e) : [e]);
			}
	}
	return (ret.length==1 && arr!==true) ? ret[0] : norm(ret);
};

var explode=function(d,o){return(typeof(o=o.split(d))=='string')?[o]:o;};
var norm=function(arr){var a=[],l=arr.length;for(var i=0;i<l;i++){for(var j=i+1;j<l;j++)if(arr[i]===arr[j])j=++i;a.push(arr[i]);}return a;};
var $trim=function(str,chr){chr=chr||'\\s';var str=str.replace(new RegExp("^["+chr+"]+", "g"),''),ws=new RegExp("["+chr+"]"),i=str.length;while(ws.test(str.charAt(--i)));return str.slice(0,i+1);};

var $Element = function(t,o) {
	var ret=(t.tagName?t:document.createElement(t));
	if(o) {
		if (o['insert']) delete(o['append']);
		if (o['className']) o['class']=o['className'];
		if (o['enctype']) o['encoding']=o['enctype'];
		for(a in o)switch(a) {
			case 'style':for(s in o[a])ret.style[s]=o[a][s];break;
			case 'events':for(e in o[a])addEvent(ret,e,(o[a][e]));break;
			case 'insert':o[a].parentNode.insertBefore(ret,o[a]);break;
			case 'append':o[a].appendChild(ret);break;
			case 'innerHTML':ret.innerHTML=o[a];break;
			default: ret[a]=o[a];break;
		}
	}
	if (!ret.hasClass) { 
		ret.$style=function(s,v){return $style(this,s,v);};
		ret.classes=function(){return classes(this);};
		ret.hasClass=function(c){return hasClass(this,c);};
		ret.classLike=function(c){return classLike(this,c);};
		ret.addClass=function(c){return addClass(this,c);};
		ret.removeClass=function(c){return removeClass(this,c);};
		ret.addEvent=function(e,f){return addEvent(this,e,f);};
		ret.removeEvent=function(e,f){return removeEvent(this,e,f);};
		ret.update=function(o){return $Element(this,o);};
//		ret.children=function(){var ch=$('*',this, true),c,i=0;while(c=ch[i++])if(c.parentNode==this)ret.push(c);return ret;};
		ret.allChildren=function(){return $('*',this, true);};
		ret.ancestors=function(){var ret=[],a=this;while((a=a.parentNode)&&a.tagName)ret.push($Element(a));return ret;};
		ret.descendsFrom=function(e){var a=this;while(a=a.parentNode)if(a==e)return true;return false;};
		ret.remove=function(){return this.parentNode.removeChild(this);};
	}
	return ret;
};

var $style=function(e,s,v){var f;if(!v){if(f=e.currentStyle)return f[CSStoJS(s)];if(f=window.getComputedStyle)return f(e,"").getPropertyValue(s);}s.style[CSStoJS(s)]=v;};

var classes=function(e){return explode(' ',e.className);};
var hasClass=function(e,c){var i=0,cl=null,cls=classes(e);while(cl=cls[i++])if(cl==c)return true;return false;};
var classLike= function(e,c) {var i=0,cl=null,cls=classes(e),reg=new RegExp(c),ret=[];while(cl=cls[i++])if(reg.test(cl)) ret[ret.length]=cl;if(ret.length)return ret;return false;};
var addClass=function(e,c){if(!hasClass(e,c))e.className=e.className+' '+c;};
var removeClass=function(e,c){var i=0,cl=null,cls=classes(e);while(cl=cls[i++])if(cl==c)cls[i-1]='';e.className=cls.join(' ');};


var CSStoJS=function(css){return css.replace(/\-(.)/g,function(m,l){return l.toUpperCase();});};
var JStoCSS=function(js){return js.replace(/[A-Z]/,function(m){return '-'+m.toLowerCase();});};

var getElementsByRole=function(n){
	if(!n)var n=document;
	var ret=[],a,e,i=0,all=n.getElementsByTagName('*');
	while(e=all[i++]){
		if(e.getAttribute('role')) ret[ret.length]=e;
	}
	return ret;
};

function $FormData(f) {
	var i=0,e=null,data=null,list=$("input,select,textarea", f, true);
	while(e=list[i++]) data = (data?data+'&':'')+(e.name?e.name:(e.id?e.id:e.tagName+'_'+i))+'='+e.value;
	return data;
}

function XMLUpdate(xml){
	if (!xml) alert('An Error has occured. Please forward the folowing information to Daniel Lanigan:\n\nLocation: '+location.href+ '\n\n' + xHttp.xmlHttp.responseText);
	var i=0,update,updates=xml.getElementsByTagName('*');
	while(update=updates[i++])switch(update.tagName){
		case 'update':
			var node=$('#'+update.getAttribute('for'));
			switch(update.getAttribute('what')) {
				case 'remove': node.parentNode.removeChild(node); break;
				case 'append': node.innerHTML += update.childNodes[0].data; roleModel(node); break;
				default: node.innerHTML = update.childNodes[0].data; roleModel(node); break;
			} break;
		case 'script':eval('('+update.childNodes[0].data+')');break;
	}
}

function roleModel(node) { 

	var a=null,i=0,e=null,list=getElementsByRole(node);
	while(e=list[i++]) {
		a = e.getAttribute('role');
		
		var j=0,l=null,s=a.split(' ');
		if (!e.hasRole)while(l=s[j++]) {
			e.hasRole=true;
			var link = explode('-',a),evt;
			if(link[1]) evt = link[1].replace('on','');
			else evt = 'click';
			
			switch (link[0]) {
				case 'noscript': e.style.display='none';break;
				case 'link': addEvent(e,evt,function(e){var evt=$e(e);changeCollection(evt.caller.value);returnFalse(evt);}); break;
				case 'update': 
					addEvent(e,evt,function(e){
						var evt = $e(e);
						xHttp.request(document.body.id.replace(/-/g,'/')+'&data='+(evt.caller.getAttribute('name')? evt.caller.getAttribute('name')+'/'+evt.caller.value : evt.caller.id));
						returnFalse(evt);					
					});
					break;
				case 'submit': var form = e;
					while (form.parentNode && form != document.body && form.tagName != 'FORM') form = form.parentNode;
					if (form.tagName != 'FORM') break;
					else e.submitForm = form;
					addEvent(e,evt,function(e){
						var f,evt=$e(e),m;
						if (f=evt.caller.submitForm) {
							m=f.method.toUpperCase();
							if (m=='POST') xHttp.data = $FormData(f);
							xHttp.request(document.body.id.replace(/-/g,'/')+(m!='POST'?'&'+$FormData(f):''));
						}
						xHttp.clearData();
						returnFalse(evt);
					});
					break;
				case 'labeler':
					var target = $('#'+e.getAttribute('for'));
					target['display'] = $style(target,'display');
					target.style.display = 'none';
					addEvent(e,'click',function(e){
						var evt = $e(e);
						evt.caller.style.display = 'none';
						e = $('#'+evt.caller.getAttribute('for'));
						if (e['display']) {
							e.style.display = e['display'];
							delete e['display'];
						}
					});
					break;
				case 'expanding':
					e.fullText = e.innerHTML;
					switch (evt) {
						case 'all': e.innerHTML = ''; break;
						case 'newline': e.innerHTML = e.innerHTML.replace(/\n(.*)/,''); break;
						case 'br': e.innerHTML = e.innerHTML.replace(/<br>(.*)/,''); break;
					}
					if (e.fullText != e.innerHTML)
						$Element('a',{href:'#',events:{click:function(e){var el=$e(e).caller.parentNode;el.shortText=el.innerHTML; el.innerHTML=el.fullText; returnFalse($e(e));}},style:{display:'inline',paddingLeft:'10px',color:'blue',textDecoration:'underline'},innerHTML:'More',append:e});
					break;
			}
		}
	}
}

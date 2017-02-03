function findPos(obj) {// http://www.quirksmode.org/js/findpos.html
	var curleft = curtop = 0;
	if (obj.offsetParent) {
		curleft = obj.offsetLeft
		curtop = obj.offsetTop
		while (obj = obj.offsetParent) {
			curleft += obj.offsetLeft
			curtop += obj.offsetTop
		}
	}
	return [curleft,curtop];
}

function addEvent(el,trigger,func){
	if (el.addEventListener){
		el.addEventListener(trigger,func,false);
	} else if (el.attachEvent){
		el.attachEvent('on'+trigger, func);
	}
}

Element.prototype.isDescendantOf = function(ancestor){
	var el=this;
	do{
		if(el.isSameNode(ancestor))
			return true;
	}while(el=el.parentNode);
	return false;
}

document.itemSearch={
	XHR: new XMLHttpRequest(),
	keydown:function(e){
		if(e.keyCode==27 || e.keyCode==9){
			document.itemSearch.resultsArea.hide();
			return;
		}
	},
	focus:function(){
		addEvent(document,"mousedown",document.itemSearch.reevaluateFocus);
		document.itemSearch.resultsArea.show();
	},
	reevaluateFocus:function(e){
		if(e && e.target && (!e.target.isDescendantOf(document.getElementById("item_search"))))
			document.itemSearch.resultsArea.hide();
	},
	initiateRequest: function(){
		var query=document.new_item.q.value;
		document.itemSearch.XHR.open("GET","request/search_items/js?q="+escape(query),false);
		document.itemSearch.XHR.onreadystatechange=function(){
			if((document.itemSearch.XHR.readyState==4)&&(document.itemSearch.XHR.status==200))
					document.itemSearch.resultsArea.populate(eval(document.itemSearch.XHR.responseText));
		};

	document.itemSearch.XHR.send(null);
	},
	selectItem: function(id,text){
		document.new_item.item_id.value=id;
		document.new_item.q.value=text;
		document.itemSearch.resultsArea.hide();
		document.new_item.quantity.focus();
	},
	resultsArea: {
		resultsElement: null,
		moreLink: null,
		populate: function(results){
			this.clear();
			for(var i=1; i<results.length; i++){
				this.add(results[i]);
			}
			this.moreLink.add(results[0]);
		},
		clear: function(){
			this.resultsElement.innerHTML="";
		},
		add: function(item){
			var itemElement = document.createElement("li");
			itemElement.link=document.createElement("a");
			itemElement.appendChild(itemElement.link);
			itemElement.setAttribute("class","result");
			itemElement.link.setAttribute("href","javascript:;");
			itemElement.link.innerHTML=item.item_id+" - "+item.item_name+" "+item.unit_weight+item.unit_weight_unit+" - £"+item.unit_price.toFixed(2);

			addEvent(itemElement.link,"click",function(){return function(e){document.itemSearch.selectItem(item.item_id,itemElement.link.innerHTML);e.preventDefault;}}());
			this.resultsElement.appendChild(itemElement);
		},
		show: function(){
			document.itemSearch.resultsArea.resultsElement.style.display="block";	
		},
		hide:function(){
			document.itemSearch.resultsArea.resultsElement.style.display="none";	
		},
		build: function(){
                  document.new_item.q.setAttribute('autocomplete', 'off')
			this.resultsElement=document.createElement("ul");
			var qpos=findPos(document.new_item.q);
			var top=qpos[1]+document.new_item.q.offsetHeight;
			this.resultsElement.style.left=qpos[0];
			this.resultsElement.style.top=top;
			this.resultsElement.setAttribute("id","item_search_results");	
			this.hide();
			document.getElementById("item_search").appendChild(this.resultsElement);

			this.moreLink=document.createElement("li");
			this.moreLink.setAttribute("style","text-align:center;");
			this.moreLink.link=document.createElement("a");
			this.moreLink.appendChild(this.moreLink.link);
			this.moreLink.setAttribute("class","result");
			this.moreLink.link.setAttribute("href","#");
			this.moreLink.link.setAttribute("style","font-weight:bold;");
			this.moreLink.link.innerHTML="Search the full Infinity catalogue.";

			this.moreLink.add=function(t){return function(searchTerm){

t.moreLink.link.setAttribute("href","request/search_catalogue?q="+escape(searchTerm));
				t.resultsElement.appendChild(t.moreLink);
			}}(this);
		}
	}
}
function deleteItem(itemForm){
	itemForm.quantity.value=0;
	itemForm.submit();
}

function init(){
	var forms=document.getElementsByTagName("form");
	for(var i=0;i<forms.length;i++){
		if(forms[i].className.indexOf("request_contents_row")==-1)
			continue;
		addEvent(forms[i].deleteButton,"click",deleteItem.bind(null,forms[i]));
	}

	document.itemSearch.resultsArea.build();
	addEvent(document.new_item.q,"focus",function(){return document.itemSearch.focus}());
	addEvent(document.new_item.q,"keydown",function(){return document.itemSearch.keydown}());
	addEvent(document.new_item.q,"keyup",function(){return document.itemSearch.initiateRequest}());


	if(document.new_item.submit_b){
		document.new_item.action="request/add";
		document.new_item.submit_b.value="Add";
		document.getElementById("quantity").style.display="inline";
	}
}

addEvent(window,"load",init);

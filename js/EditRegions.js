

/*   


##	Accessing the generated elements:

	the InPlace object has a child object named "elems" which contains a reference to each element
	To access them, you need the ordinal corresponding to which edit region you'd like to access.
	The textarea and each button has an attribute added called "ordinal" which will hold the appropriate
	vale.  
	
	InPlace.elems[ordinal]["buttons"][arrayIndex]	array of button elements
	InPlace.elems[ordinal]["edit"]					the text area

	
	
	
	
	
*/



function $() {
//
//	Just a shortened version of document.getElementById();
//	shamelessly ripped from prototype.
//
  var results = [], element;
  for (var i = 0; i < arguments.length; i++) 
  {
    element = arguments[i];
    if (typeof element == 'string')
      element = document.getElementById(element);
    results.push(element);
  }
  if(results.length == 1){return results[0]}
  return results;
}




function getStyle(el,styleProp)
//	notes: retrieves the rendered style attribute [styleProp] of the element [el]
//	Equivalent to el.currentStyle[styleProp] in IE and 
//	document.defaultView.getComputedStyle(el,null).getPropertyValue(styleProp)
//	in W3C compliant browsers.
//	credit: http://www.quirksmode.org/dom/getstyles.html
{
	var x = el;
	if(typeof x == "string"){x = document.getElementById(x);}
	if (x.currentStyle)
		var y = x.currentStyle[styleProp];
	else if (window.getComputedStyle)
		var y = document.defaultView.getComputedStyle(x,null).getPropertyValue(styleProp);
	return y;
}

  

  
var InPlace = {
	
	elems: new Object(),
	createButton: function(caption, ordinal, clickEvent)
	{
		var thisButton = document.createElement("a");

		thisButton.style.position="absolute";	
		thisButton.style.cursor="pointer";
		thisButton.style.display="none";
		thisButton.className = "editButtons";	
		thisButton.href="#";
		thisButton.ordinal = ordinal;
		thisButton.innerHTML = caption;
		thisButton.id = "editButton" + caption + ordinal;
		thisButton.onclick = clickEvent;

		return thisButton;		
	},
	editButtons: {
		hideButtons:function(ordinal){
			for(buttons in InPlace.elems[ordinal]["buttons"]){
				InPlace.elems[ordinal]["buttons"][buttons].style.display = "none";
			}
		},
		showButtons:function(ordinal){
			var xLocation = 1;
			for(button in InPlace.elems[ordinal]["buttons"])
			{
				InPlace.elems[ordinal]["buttons"][button].style.display = "";
				InPlace.elems[ordinal]["buttons"][button].style.top = (InPlace.elems[ordinal]["edit"].offsetHeight+1)+ "px";
				InPlace.elems[ordinal]["buttons"][button].style.left = (xLocation) + "px";				
				xLocation = xLocation + InPlace.elems[ordinal]["buttons"][button].offsetWidth + 2;
			}
		}
	},
	initialize: function(tagName)
	//	notes: find elements of the specified type [tagName]
	//	cycle through them and find elements in the editInPlace class.
	//
	//	for each found element, create two elements. 
	//	Id them 'editDisplayX' and 'editEditX' where X is the array index.
	//
	//	ensure that each item will display properly, then assign the appropriate behaviors.
	//	copy content from the original element into the display item and append the two new
	//	items to the now empty original element - which will then act as a wrapper.
	{
		if(!tagName){tagName = "*"}
		var eipItems = document.getElementsByTagName(tagName);
		var itemsCtr
		for(itemsCtr = 0; itemsCtr<eipItems.length; itemsCtr++){
			if(eipItems[itemsCtr].className.indexOf("editInPlace") != -1){
			this.makeEditable(eipItems[itemsCtr], itemsCtr);
			}
		}
	},

	beginEdit:function(ordinal)
	{

		var lastButton = this.elems[ordinal]["buttons"][this.elems[ordinal]["buttons"].length - 1];

		this.elems[ordinal]["edit"].style.display = "";
		this.elems[ordinal]["displayBorder"].style.display = "";
		this.elems[ordinal]["editBorder"].style.display = "";
		this.elems[ordinal]["resize"].style.display = "";
		
		this.editButtons.showButtons(ordinal);
				
		this.elems[ordinal]["displayBorder"].style.height = (this.elems[ordinal]["display"].offsetHeight) + "px";
		this.elems[ordinal]["displayBorder"].style.width = (this.elems[ordinal]["display"].offsetWidth) + "px";
		this.elems[ordinal]["displayBorder"].style.top = (InPlace.offTop(this.elems[ordinal]["display"])) + "px";
		this.elems[ordinal]["displayBorder"].style.left = (InPlace.offLeft(this.elems[ordinal]["display"])) + "px";

		this.elems[ordinal]["editBorder"].style.height = (this.elems[ordinal]["edit"].offsetHeight + this.elems[ordinal]["buttons"][1].offsetHeight + 6) + "px";
		this.elems[ordinal]["editBorder"].style.width = (this.elems[ordinal]["edit"].offsetWidth) + "px";

		this.elems[ordinal]["resize"].style.width = (this.elems[ordinal]["buttons"][0].offsetHeight) + "px";
		this.elems[ordinal]["resize"].style.top = (this.elems[ordinal]["edit"].offsetHeight + 3) + "px";
		this.elems[ordinal]["resize"].style.left = (this.elems[ordinal]["edit"].offsetWidth - this.elems[ordinal]["buttons"][0].offsetHeight - 3) + "px";	
		this.elems[ordinal]["resize"].style.height = (this.elems[ordinal]["resize"].offsetWidth) + "px";
		
		this.elems[ordinal]["move"].style.width = (this.elems[ordinal]["edit"].offsetWidth - (lastButton.offsetLeft + lastButton.offsetWidth + this.elems[ordinal]["resize"].offsetWidth + 7)) + "px";
		this.elems[ordinal]["move"].style.top = (this.elems[ordinal]["edit"].offsetHeight + 3) + "px";
		this.elems[ordinal]["move"].style.left = (lastButton.offsetLeft + lastButton.offsetWidth + 2) + "px";	
		this.elems[ordinal]["move"].style.height = (this.elems[ordinal]["buttons"][0].offsetHeight) + "px";

		this.elems[ordinal]["edit"].focus();

	},
	finishEdit:function(ordinal)
	{
		this.elems[ordinal]["edit"].style.display="";
		this.elems[ordinal]["display"].style.display="";
		

		this.elems[ordinal]["displayBorder"].style.display = "none";
		this.elems[ordinal]["editBorder"].style.display = "none";
		this.elems[ordinal]["resize"].style.display = "none";
		
		this.editButtons.hideButtons(ordinal);
		
	},
	makeEditable: function(thisItem, ordinal)
	{	
		var displayElm = thisItem;	
		
		displayElm.className = thisItem.className.replace(/editInPlace/, "edit_display");
		displayElm.ordinal = ordinal;
		displayElm.style.cursor = "pointer";
		displayElm.onclick = function()
		{
			window.eval(this.parentNode.getAttribute("onedit"));
			InPlace.beginEdit(this.ordinal);
		}
		var editElm = document.createElement("textarea");
		
		//	retouch the value so it will display consitantly between HTML and the textarea.
		
//	.--------------------------------------------------------------------.
//			contributed by Brian - http://education.ou.edu/+dnd/

		var hasInnerText = (document.getElementsByTagName("body")[0].innerText != undefined) ? true : false; // first, test if browser is innerText compatible (safari) – if not innerHTML will work
		if (!hasInnerText) { // if not innerText compatible, use innerHTML
			editElm.value = thisItem.innerHTML.replace(/[\n\r]/gi,""); // Ff, ie, ns – set textarea value to same as editable div
		} else { // else use innerText
			editElm.innerText = thisItem.innerText; // safari – set textarea value to same as editable div
		}

//	'--------------------------------------------------------------------'
        
		//now do the replacing of breaks, etc.
		editElm.value = editElm.value.replace(/[\n\r]/gi,"");
		editElm.value = editElm.value.replace(/<br>/gi,"\r").replace(/<br\/>/gi,"\r");
		editElm.value = editElm.value.replace(/\r\s/gi,"\r").replace(/\x20+/gi," ");
		
		editElm.style.width = (thisItem.offsetWidth) + "px";
		editElm.style.height = (thisItem.offsetHeight) + "px";
		
		editElm.style.display="none";
		editElm.style.border="0px solid black";
		editElm.className = "edit_edit";
		editElm.id = "editEdit" + ordinal;
		editElm.ordinal = ordinal;
		editElm.onupdate =function() {window.eval(this.parentNode.getAttribute("onupdate"));}
		editElm.onkeypress = function(e){
			pressedKey = e ? Math.max(e.keyCode, e.charCode) : window.event.keyCode;
			ctrlKey = e ? e.ctrlKey : window.event.ctrlKey;
			altKey = e ? e.altKey : window.eventaltKey;
			if((pressedKey == 115 || pressedKey == 83) && ctrlKey && altKey){
				InPlace.finishEdit(this.ordinal, true)
				this.onupdate(); 
				return false;
			}
			if(pressedKey == 27){
				InPlace.finishEdit(this.ordinal, false)
				return false;					
			}
		}
		editElm.onkeyup = function(e){
			var ordinal = this.ordinal;

			if(navigator.product != 'Gecko'){InPlace.elems[ordinal]["display"].innerHTML=InPlace.elems[ordinal]["edit"].value.replace(/[\r]/gi,"<br\/>")}
			if(navigator.product == 'Gecko'){InPlace.elems[ordinal]["display"].innerHTML=InPlace.elems[ordinal]["edit"].value.replace(/[\r\n]/gi,"<br\/>")}
			InPlace.elems[ordinal]["displayBorder"].style.height = (InPlace.elems[ordinal]["display"].offsetHeight) + "px";
			InPlace.elems[ordinal]["displayBorder"].style.width = (InPlace.elems[ordinal]["display"].offsetWidth) + "px";
			InPlace.elems[ordinal]["displayBorder"].style.top = (InPlace.offTop(InPlace.elems[ordinal]["display"])) + "px";
			InPlace.elems[ordinal]["displayBorder"].style.left = (InPlace.offLeft(InPlace.elems[ordinal]["display"])) + "px";	
		}
		
		var displayBorderElm = document.createElement("div");
		var editBorderElm = document.createElement("div");
		var resizeElm = document.createElement("div");
		var moveElm = document.createElement("div");

		displayBorderElm.style.position = "absolute";
		displayBorderElm.style.border = "1px dashed red";
		displayBorderElm.style.display = "none";
		
		editBorderElm.style.position = "absolute";
		editBorderElm.style.border = "1px solid black";
		editBorderElm.style.background = "#FFF";
		editBorderElm.style.display = "none";
		editBorderElm.style.top = (InPlace.offTop(displayElm)) + "px"; // + displayElm.offsetHeight;
		editBorderElm.style.left = (InPlace.offLeft(displayElm)) + "px";	
				
		resizeElm.style.position = "absolute";
		resizeElm.style.background = "gray";	
		resizeElm.style.fontSize = "0em";	
			
		moveElm.style.position = "absolute";
		moveElm.style.background = "#C6C6C6";
		moveElm.style.fontSize = "0em";	
		
		moveElm.onmousedown = function(e){InPlace.startMove(e,ordinal)};	
		resizeElm.onmousedown = function(e){InPlace.startSize(e,ordinal)};
		
		
		// 	populate our object structure
		this.elems[ordinal] = new Object();
		this.elems[ordinal]["edit"] = editElm;
		this.elems[ordinal]["display"] = displayElm;		
		this.elems[ordinal]["displayBorder"] = displayBorderElm;
		this.elems[ordinal]["editBorder"] = editBorderElm;
		this.elems[ordinal]["resize"] = resizeElm;
		this.elems[ordinal]["move"] = moveElm;
		this.elems[ordinal]["buttons"] = new Array();
		
		for(var i = 0; i < InPlaceButtons.length; i++){
			this.elems[ordinal]["buttons"].push( this.createButton(InPlaceButtons[i].caption, ordinal, InPlaceButtons[i].onclick));
		}
		

		//	finally, attach the elements.

		document.getElementsByTagName('body')[0].appendChild(displayBorderElm);
		document.getElementsByTagName('body')[0].appendChild(editBorderElm);
		editBorderElm.appendChild(editElm);
		editBorderElm.appendChild(resizeElm);
		editBorderElm.appendChild(moveElm);
		for(buttons in InPlace.elems[ordinal]["buttons"]){
			editBorderElm.appendChild(InPlace.elems[ordinal]["buttons"][buttons]);
		}
	},
	sizeProc: null,
	moveProc: null,
	startMove: function(e,ordinal)
	{
		if (!e) var e = window.event;
		if (!e.target) e.target = e.srcElement;
		var offsetX = (e.offsetX) ? e.offsetX : e.layerX;
		var offsetY = (e.offsetY) ? e.offsetY : e.layerY;
		offsetX += e.target.offsetLeft;
		offsetY += e.target.offsetTop;
		
		eventObserve(document, 'mousemove', MouseState.onmousemove);
		eventObserve(document, 'mouseup', InPlace.endMove);
		this.moveProc = eventObserve(document, 'mousemove', function(e){InPlace.doMove(e,ordinal, offsetX, offsetY)});
		
		
	},
	doMove: function(e,ordinal,offsetX,offsetY)
	{

		this.elems[ordinal]["editBorder"].style.top = (MouseState.y - offsetY) + "px";
		this.elems[ordinal]["editBorder"].style.left = (MouseState.x - offsetX) + "px";	
	},
	endMove: function(e,ordinal)
	{
		eventStopObserving(document, 'mousemove', MouseState.onmousemove);
		eventStopObserving(document, 'mousemove', InPlace.moveProc);
		eventStopObserving(document, 'mouseup', InPlace.endMove);
	},
	startSize: function(e, ordinal)
	{
		if (!e) var e = window.event;
		if (!e.target) e.target = e.srcElement;
		
		var insideOffsetX = (e.offsetX) ? e.offsetX : e.layerX;
		var insideOffsetY = (e.offsetY) ? e.offsetY : e.layerY;
		var targetWidth = e.target.offsetWidth;
		var targetHeight = e.target.offsetHeight;
		
		offsetX = InPlace.offLeft(e.target.parentNode)- (targetWidth - insideOffsetX) - 2;
		offsetY = InPlace.offTop(e.target.parentNode) - (targetHeight - insideOffsetY) - 2;

		var iebody=(document.compatMode && document.compatMode != "BackCompat")? document.documentElement : document.body
		var dsocleft=(navigator.product != 'Gecko')? iebody.scrollLeft : pageXOffset
		var dsoctop=(navigator.product != 'Gecko')? iebody.scrollTop : pageYOffset
		
		MouseState.x = e.clientX + dsocleft;
		MouseState.y = e.clientY + dsoctop;
		
		eventObserve(document, 'mousemove', MouseState.onmousemove);
		eventObserve(document, 'mouseup', InPlace.endSize);
		this.sizeProc = eventObserve(document, 'mousemove', function(e){InPlace.doSize(e,ordinal, offsetX, offsetY)});	
	},	
	doSize: function(e,ordinal,offsetX,offsetY)
	{
		this.elems[ordinal]["editBorder"].style.height = (MouseState.y - offsetY + 4) + "px";
		this.elems[ordinal]["editBorder"].style.width = (MouseState.x - offsetX + 4) + "px";			
		
		var lastButton = this.elems[ordinal]["buttons"][this.elems[ordinal]["buttons"].length - 1];
		
		editHeight = (MouseState.y - offsetY) - this.elems[ordinal]["buttons"][1].offsetHeight -6;
		editWidth = (MouseState.x - offsetX) - 2;
		MoveWidth = editWidth - (lastButton.offsetLeft + lastButton.offsetWidth + this.elems[ordinal]["resize"].offsetWidth + 6);
		if(editHeight < 80){
			MouseState.y = (80 + lastButton.offsetHeight + 6) + offsetY;
			this.elems[ordinal]["editBorder"].style.height = (80 + lastButton.offsetHeight + 10) + "px"; 
			
		} 
		if(MoveWidth < this.elems[ordinal]["buttons"][0].offsetHeight){
			MouseState.x =  (this.elems[ordinal]["move"].offsetLeft + (lastButton.offsetHeight * 2) + 6) + offsetX; 
			this.elems[ordinal]["editBorder"].style.width = (this.elems[ordinal]["move"].offsetLeft + (lastButton.offsetHeight * 2) + 6) + "px"; 
		} 
		
		this.elems[ordinal]["edit"].style.height = ((MouseState.y - offsetY) - this.elems[ordinal]["buttons"][1].offsetHeight -6) + "px";
		this.elems[ordinal]["edit"].style.width = (MouseState.x - offsetX - 2) + "px";
		
		this.elems[ordinal]["resize"].style.width = (this.elems[ordinal]["buttons"][0].offsetHeight) + "px";
		this.elems[ordinal]["resize"].style.top = (this.elems[ordinal]["edit"].offsetHeight + 3) + "px";
		this.elems[ordinal]["resize"].style.left = (this.elems[ordinal]["edit"].offsetWidth - this.elems[ordinal]["buttons"][0].offsetHeight - 1) + "px";	
		this.elems[ordinal]["resize"].style.height = (this.elems[ordinal]["buttons"][0].offsetHeight) + "px";
		
		this.elems[ordinal]["move"].style.width = (this.elems[ordinal]["edit"].offsetWidth - (lastButton.offsetLeft + lastButton.offsetWidth + this.elems[ordinal]["resize"].offsetWidth + 5)) + "px";
		this.elems[ordinal]["move"].style.top = (this.elems[ordinal]["edit"].offsetHeight + 3) + "px";
		this.elems[ordinal]["move"].style.left = (lastButton.offsetLeft + lastButton.offsetWidth + 2) + "px";	
		this.elems[ordinal]["move"].style.height = (this.elems[ordinal]["buttons"][0].offsetHeight) + "px";		
		this.editButtons.showButtons(ordinal);
		
	},
	endSize: function(ordinal)
	{
		eventStopObserving(document, 'mousemove', MouseState.onmousemove);
		eventStopObserving(document, 'mousemove', InPlace.sizeProc);
		eventStopObserving(document, 'mouseup', InPlace.endSize);
	},
	browserResize: function(){
		
		for(ordinal in InPlace.elems){
// 			var changeY = InPlace.offTop(InPlace.elems[ordinal]["displayBorder"]) - InPlace.offTop(InPlace.elems[ordinal]["display"]);
// 			var changeX = InPlace.offLeft(InPlace.elems[ordinal]["displayBorder"]) - InPlace.offLeft(InPlace.elems[ordinal]["display"]);
// 			
// 			InPlace.elems[ordinal]["editBorder"].style.top = (InPlace.offTop(InPlace.elems[ordinal]["editBorder"]) - changeY) + "px";
// 			InPlace.elems[ordinal]["editBorder"].style.left = (InPlace.offLeft(InPlace.elems[ordinal]["editBorder"]) - changeX) + "px";
// 			
// 			InPlace.elems[ordinal]["displayBorder"].style.height = (InPlace.elems[ordinal]["display"].offsetHeight) + "px";
// 			InPlace.elems[ordinal]["displayBorder"].style.width = (InPlace.elems[ordinal]["display"].offsetWidth) + "px";
// 			InPlace.elems[ordinal]["displayBorder"].style.top = (InPlace.offLeft(InPlace.elems[ordinal]["display"])) + "px";
// 			InPlace.elems[ordinal]["displayBorder"].style.left = (InPlace.offTop(InPlace.elems[ordinal]["display"])) + "px";


			var changeY = InPlace.elems[ordinal]["displayBorder"].offsetTop - InPlace.elems[ordinal]["display"].offsetTop;
			var changeX = InPlace.elems[ordinal]["displayBorder"].offsetLeft - InPlace.elems[ordinal]["display"].offsetLeft;

  			var changeY = InPlace.offTop(InPlace.elems[ordinal]["displayBorder"]) - InPlace.offTop(InPlace.elems[ordinal]["display"]);
  			var changeX = InPlace.offLeft(InPlace.elems[ordinal]["displayBorder"]) - InPlace.offLeft(InPlace.elems[ordinal]["display"]);			
				

 			InPlace.elems[ordinal]["editBorder"].style.top = (InPlace.offTop(InPlace.elems[ordinal]["editBorder"]) - changeY) + "px";
 			InPlace.elems[ordinal]["editBorder"].style.left = (InPlace.offLeft(InPlace.elems[ordinal]["editBorder"]) - changeX) + "px";
			
			InPlace.elems[ordinal]["displayBorder"].style.height = (InPlace.elems[ordinal]["display"].offsetHeight) + "px";
			InPlace.elems[ordinal]["displayBorder"].style.width = (InPlace.elems[ordinal]["display"].offsetWidth) + "px";
			InPlace.elems[ordinal]["displayBorder"].style.top = (InPlace.offTop(InPlace.elems[ordinal]["display"])) + "px";
			InPlace.elems[ordinal]["displayBorder"].style.left = (InPlace.offLeft(InPlace.elems[ordinal]["display"])) + "px";



		}
	},
	offLeft: function (obj) {
		var curleft = 0;
		if (obj.offsetParent) {
			curleft = obj.offsetLeft
			while (obj = obj.offsetParent) {
				curleft += obj.offsetLeft
			}
		}
		return curleft;
	},
	offTop: function (obj) {
		var curtop = 0;
		if (obj.offsetParent) {
			curtop = obj.offsetTop
			while (obj = obj.offsetParent) {
				curtop += obj.offsetTop
			}
		}
		return curtop;
	}
}


eventObserve(window,"load", function(){InPlace.initialize('div')});
eventObserve(window,"resize", function(){InPlace.browserResize()});

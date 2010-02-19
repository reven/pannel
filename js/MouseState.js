// MouseState.js
// Reports the current "State of the Mouse" upon request

function eventObserve(elm, evType, fn, useCapture) {
//
//	Cross-browser friendly means of registering event handlers
//	Taken from http://www.dustindiaz.com/top-ten-javascript/
//
	if (elm.addEventListener) {
		elm.addEventListener(evType, fn, useCapture);
		return fn;
	}
	else if (elm.attachEvent) {
		elm['e'+evType+fn] = fn;
		elm[evType+fn] = function(){elm['e'+evType+fn]( window.event );}
		elm.attachEvent( 'on'+evType, elm[evType+fn] );
		//var r = elm.attachEvent('on' + evType, fn);
		return fn;
	}
	else {
		elm['on' + evType] = fn;
	}
}

function eventStopObserving(elm, evType, fn, useCapture) {
//
//	Cross-browser friendly means of unregistering event handlers
//	bastardized from prototype event.stopObserving, then formatted to jive with eventObserve
//
	if (elm.removeEventListener) {
		elm.removeEventListener(evType, fn, useCapture);
	} else if (elm.detachEvent) {
	  try {
		elm.detachEvent( 'on'+evType, elm[evType+fn] );
		elm[evType+fn] = null;	  
		} 
	  catch (e) {}
	}
	return fn;
}





var buttons = {
//
//	mouse buttons
//
	left:((navigator.product == 'Gecko')?0:1),
	right:((navigator.product == 'Gecko')?2:2),
	middle:((navigator.product == 'Gecko')?1:4)
}


var MouseState = {
//
//	keeps up to date information about the mouse positions and the clickity click of its buttons.
//	watching the mouse move event is pretty intense - resource wise - so turn it off when you
//	aren't using it.
//
	x: 0,
	y: 0,
	leftButtonDown: false,
	rightButtonDown: false,
	middleButtonDown: false,

	onmousedown: function(e)
	{
		if (!e) var e = window.event
		MouseState.leftButtonDown = (e.button & buttons.left)?true:false;
		MouseState.rightButtonDown = (e.button & buttons.right)?true:false;
		MouseState.middleButtonDown = (e.button & buttons.middle)?true:false;
	},
	
	onmouseup: function(e)
	{
		if (!e) var e = window.event
		MouseState.leftButtonDown = (e.button & buttons.left)?false:MouseState.leftButtonDown;
		MouseState.rightButtonDown = (e.button & buttons.right)?false:MouseState.rightButtonDown;
		MouseState.middleButtonDown = (e.button & buttons.middle)?false:MouseState.middleButtonDown;
	},
	
	onmousemove: function(e)
	{
		if (!e) var e = window.event;
		
		var iebody=(document.compatMode && document.compatMode != "BackCompat")? document.documentElement : document.body
		var dsocleft=document.all? iebody.scrollLeft : pageXOffset
		var dsoctop=document.all? iebody.scrollTop : pageYOffset


		MouseState.x = e.clientX + dsocleft;
		MouseState.y = e.clientY + dsoctop;
	},
	
	captureMove: function(){
		eventObserve(document, 'mousemove', MouseState.onmousemove);
	},

	releaseMove: function(){
		eventStopObserving(document, 'mousemove', MouseState.onmousemove);
	}
}

eventObserve(document, 'mousedown', MouseState.onmousedown);
eventObserve(document, 'mouseup', MouseState.onmouseup);
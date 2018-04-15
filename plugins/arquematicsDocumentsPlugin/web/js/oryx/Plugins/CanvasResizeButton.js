ORYX.Plugins.CanvasResizeButton = Clazz.extend({
	
	construct: function(canvas, position, callback){

		this.canvas = canvas;
		var parentNode = canvas.getHTMLContainer().parentNode.parentNode.parentNode;
		
		window.myParent=parentNode
		var scrollNode 	= parentNode.firstChild;
		var svgRootNode = scrollNode.firstChild.firstChild;
		// The buttons
		var buttonGrow 	= ORYX.Editor.graft("http://www.w3.org/1999/xhtml", parentNode, ['div', { 'class': 'canvas_resize_indicator canvas_resize_indicator_grow' + ' ' + position ,'title':ORYX.I18N.RESIZE.tipGrow+ORYX.I18N.RESIZE[position]}]);
		var buttonShrink 	= ORYX.Editor.graft("http://www.w3.org/1999/xhtml", parentNode, ['div', { 'class': 'canvas_resize_indicator canvas_resize_indicator_shrink' + ' ' + position ,'title':ORYX.I18N.RESIZE.tipShrink+ORYX.I18N.RESIZE[position]}]);
		
		// Defines a callback which gives back
		// a boolean if the current mouse event 
		// is over the particular button area
		var offSetWidth = 60;
		var isOverOffset = function(event){
			
			if(event.target!=parentNode && event.target!=scrollNode&& event.target!=scrollNode.firstChild&& event.target!=svgRootNode&& event.target!=scrollNode)
				return false;
			
			//if(inCanvas){offSetWidth=30}else{offSetWidth=30*2}
			//Safari work around
			var X=event.layerX
			var Y=event.layerY
			if((X - scrollNode.scrollLeft)<0 ||Ext.isSafari){	X+=scrollNode.scrollLeft;}
			if((Y - scrollNode.scrollTop )<0 ||Ext.isSafari){ Y+=scrollNode.scrollTop ;}

			if(position == "N"){
				return  Y < offSetWidth+scrollNode.firstChild.offsetTop;
			} else if(position == "W"){
				return X < offSetWidth + scrollNode.firstChild.offsetLeft;
			} else if(position == "E"){
				//other offset
				var offsetRight=(scrollNode.offsetWidth-(scrollNode.firstChild.offsetLeft + scrollNode.firstChild.offsetWidth));
				if(offsetRight<0)offsetRight=0;
				return X > scrollNode.scrollWidth-offsetRight-offSetWidth;
			} else if(position == "S"){
				//other offset
				var offsetDown=(scrollNode.offsetHeight-(scrollNode.firstChild.offsetTop  + scrollNode.firstChild.offsetHeight));
				if(offsetDown<0)offsetDown=0;

				return Y > scrollNode.scrollHeight -offsetDown- offSetWidth;
			}
			
			return false;
		}
		
		var showButtons = (function() {
			buttonGrow.show(); 
			
			var x1, y1, x2, y2;
			try {
				var bb = this.canvas.getRootNode().childNodes[1].getBBox();
				x1 = bb.x;
				y1 = bb.y;
				x2 = bb.x + bb.width;
				y2 = bb.y + bb.height;
			} catch(e) {
				this.canvas.getChildShapes(true).each(function(shape) {
					var absBounds = shape.absoluteBounds();
					var ul = absBounds.upperLeft();
					var lr = absBounds.lowerRight();
					if(x1 == undefined) {
						x1 = ul.x;
						y1 = ul.y;
						x2 = lr.x;
						y2 = lr.y;
					} else {
						x1 = Math.min(x1, ul.x);
						y1 = Math.min(y1, ul.y);
						x2 = Math.max(x2, lr.x);
						y2 = Math.max(y2, lr.y);
					}
				});
			}
			
			var w = canvas.bounds.width();
			var h = canvas.bounds.height();
			
			var isEmpty = canvas.getChildNodes().size()==0;
		
			if(position=="N" && (y1>ORYX.CONFIG.CANVAS_RESIZE_INTERVAL || (isEmpty && h>ORYX.CONFIG.CANVAS_RESIZE_INTERVAL))) buttonShrink.show();
			else if(position=="E" && (w-x2)>ORYX.CONFIG.CANVAS_RESIZE_INTERVAL) buttonShrink.show();
			else if(position=="S" && (h-y2)>ORYX.CONFIG.CANVAS_RESIZE_INTERVAL) buttonShrink.show();
			else if(position=="W" && (x1>ORYX.CONFIG.CANVAS_RESIZE_INTERVAL || (isEmpty && w>ORYX.CONFIG.CANVAS_RESIZE_INTERVAL))) buttonShrink.show();
			else buttonShrink.hide();
		}).bind(this);
		
		var hideButtons = function() {
			buttonGrow.hide(); 
			buttonShrink.hide();
		}	
		
		// If the mouse move is over the button area, show the button
		scrollNode.addEventListener(	ORYX.CONFIG.EVENT_MOUSEMOVE, 	function(event){ if( isOverOffset(event) ){showButtons();} else {hideButtons()}} , false );
		// If the mouse is over the button, show them
		buttonGrow.addEventListener(		ORYX.CONFIG.EVENT_MOUSEOVER, 	function(event){showButtons();}, true );
		buttonShrink.addEventListener(		ORYX.CONFIG.EVENT_MOUSEOVER, 	function(event){showButtons();}, true );
		// If the mouse is out, hide the button
		//scrollNode.addEventListener(		ORYX.CONFIG.EVENT_MOUSEOUT, 	function(event){button.hide()}, true )
		parentNode.addEventListener(	ORYX.CONFIG.EVENT_MOUSEOUT, 	function(event){hideButtons()} , true );
		//svgRootNode.addEventListener(	ORYX.CONFIG.EVENT_MOUSEOUT, 	function(event){ inCanvas = false } , true );
		
		// Hide the button initialy
		hideButtons();
		
		// Add the callbacks
		buttonGrow.addEventListener('click', function(){callback( position ); showButtons();}, true);
		buttonShrink.addEventListener('click', function(){callback( position, true ); showButtons();}, true);
	}
	

});
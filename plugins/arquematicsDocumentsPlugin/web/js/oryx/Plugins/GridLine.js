ORYX.Plugins.GridLine = Clazz.extend({
	
	construct: function(parentId, direction) {

		if (ORYX.Plugins.GridLine.DIR_HORIZONTAL !== direction && ORYX.Plugins.GridLine.DIR_VERTICAL !== direction) {
			direction = ORYX.Plugins.GridLine.DIR_HORIZONTAL;
		}
		
	
		this.parent = $(parentId);
		this.direction = direction;
		this.node = ORYX.Editor.graft("http://www.w3.org/2000/svg", this.parent,
					['g']);

		this.line = ORYX.Editor.graft("http://www.w3.org/2000/svg", this.node,
			['path', {
				'stroke-width': 1, stroke: 'silver', fill: 'none',
				'stroke-dasharray': '5,5',
				'pointer-events': 'none'}]);

		this.hide();

	},

	hide: function() {
		this.node.setAttributeNS(null, 'display', 'none');
	},

	show: function() {
		this.node.setAttributeNS(null, 'display', '');
	},

	getScale: function(){
		try {
			return this.parent.parentNode.transform.baseVal.getItem(0).matrix.a;
		} catch(e) {
			return 1;
		}
	},
	
	update: function(pos) {
		
		if (this.direction === ORYX.Plugins.GridLine.DIR_HORIZONTAL) {
			var y = pos instanceof Object ? pos.y : pos; 
			var cWidth = this.parent.parentNode.parentNode.width.baseVal.value/this.getScale();
			this.line.setAttributeNS(null, 'd', 'M 0 '+y+ ' L '+cWidth+' '+y);
		} else {
			var x = pos instanceof Object ? pos.x : pos; 
			var cHeight = this.parent.parentNode.parentNode.height.baseVal.value/this.getScale();
			this.line.setAttributeNS(null, 'd', 'M'+x+ ' 0 L '+x+' '+cHeight);
		}
		
		this.show();
	}


});

ORYX.Plugins.GridLine.DIR_HORIZONTAL = "hor";
ORYX.Plugins.GridLine.DIR_VERTICAL = "ver";
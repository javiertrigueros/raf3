if (!ORYX.Plugins) 
    ORYX.Plugins = new Object();

(function($, arquematics, ORYX, document, window) {

ORYX.Plugins.Wireframe = Clazz.extend({
        
  // Defines the facade
  facade: undefined,
  options: {
    modal_content:          "#simple-text-modal",
    modal_content_title:    "#modal-title", 

    control_input:          "#input_box_label",

    cmd_cancel:             ".cmd-cancel-box",
    cmd_accept:             ".cmd-accept-box" 
  },
    
  // Constructor 
  construct: function(facade)
  {
    this.facade = facade;     
                
    this.facade.registerOnEvent('layout.wireframe.carousel', this.handleObjectMove.bind(this));

    this.facade.registerOnEvent(ORYX.CONFIG.EVENT_MOUSEDOWN, this.handleMouseDown.bind(this));

    this.facade.registerOnEvent(ORYX.CONFIG.EVENT_MOUSEOVER, this.handleMouseOver.bind(this));
    this.facade.registerOnEvent(ORYX.CONFIG.EVENT_MOUSEOUT, this.handleMouseOut.bind(this));  

    this.facade.registerOnEvent(ORYX.CONFIG.EVENT_DBLCLICK, this.handleDoubleClick.bind(this));

  },

  handleMouseDown: function(e, uiObj)
  {
    //console.log('handleMouseDown');
  },

  handleMouseOver: function(event, uiObj)
  {
    //console.log('handleMouseOver');
  },

  handleMouseOut: function(event, uiObj)
  {
    //console.log('handleMouseOut');
  },

  handleDoubleClick: function(event, uiObj) 
  {

    if ((uiObj instanceof ORYX.Core.Node)
      && (uiObj.getStencil().idWithoutNs() === 'browser'))
    {
      this.showModal(uiObj);
      
      this.facade.disableEvent(ORYX.CONFIG.EVENT_KEYDOWN);
    }

    return false
  },
        
  handleObjectMove: function( event )
  {
    //console.log('handleObjectMove');
  },
  /**
  * devuelve el texto del nodo
  *
  * @param  uiObj: objeto
  *
  * @return string:
  */
  getNodeText: function (uiObj)
  {
    var ret = ''
    , props = this.getEditableProperties(uiObj)
    , allRefToViews = props.collect(function(prop){ return prop.refToView() }).flatten().compact()
    // Get all labels 
    , labels = uiObj.getLabels().findAll(function(label){ return allRefToViews.any(function(toView){ return label.id.endsWith(toView) }); });
    
    if( labels.length == 1 )
    {
      var $node = $("svg").find('#' + labels[0].id + ' tspan');
      ret = $node.text();
    }

    return ret;
  },

  showModal: function(uiObj)
  {
    var text = this.getNodeText(uiObj)
    , that = this
    , options = this.options;

    //prepara el modal
    $(options.control_input).val(text);
    $(options.modal_content_title).text(ORYX.I18N.wireframe.BrowserURL);
    //muestra el modal
    $(options.modal_content).modal({
            show: true,
            keyboard: false,
            backdrop: 'static'
      });
    //TODO: hace focus
    //pero mal
    setTimeout(function(){ 
      $(options.control_input).focus();
    }, 30);


    $(options.control_input).off();
    $(options.cmd_cancel).off();
    $(options.cmd_accept).off();

    $(options.cmd_accept).on('click', function(e) {
      e.preventDefault();
      $(options.modal_content).modal('hide');
    });

    $(options.cmd_cancel).on('click', function(e) {
      e.preventDefault();
      //vuelve al antiguo texto
      //que lo toma al mostrar el modal
      $(options.control_input).val(text);
      that.changeContent(uiObj);
      $(options.modal_content).modal('hide');
    });


    $(options.control_input).on('change', function() {
      that.changeContent(uiObj);
    });
    
  },

  changeContent: function (uiObj)
  {

    var  props = this.getEditableProperties(uiObj)
    , allRefToViews = props.collect(function(prop){ return prop.refToView() }).flatten().compact()
    // Get all labels 
    , labels = uiObj.getLabels().findAll(function(label){ return allRefToViews.any(function(toView){ return label.id.endsWith(toView) }); })
    , newValue = $(this.options.control_input).val()
    , oldValue = this.getNodeText(uiObj)
    , facade = this.facade;
    
    if (newValue != oldValue)
    {
            var prop = this.getPropertyForLabel(props, uiObj, labels[0]);
      var propId    = prop.prefix() + "-" + prop.id();
      var commandClass = ORYX.Core.Command.extend({
          construct: function(){
            this.el = uiObj;
            this.propId = propId;
            this.oldValue = oldValue;
            this.newValue = newValue;
            this.facade = facade;
          },
          execute: function(){
            this.el.setProperty(this.propId, this.newValue);
            //this.el.update();
            this.facade.setSelection([this.el]);
            this.facade.getCanvas().update();
            this.facade.updateSelection();
          },
          rollback: function(){
            this.el.setProperty(this.propId, this.oldValue);
            //this.el.update();
            this.facade.setSelection([this.el]);
            this.facade.getCanvas().update();
            this.facade.updateSelection();
          }
        });
        // Instanciated the class
        var command = new commandClass();
        
        // Execute the command
        this.facade.executeCommands([command]);
    }
  },

  getPropertyForLabel: function (properties, shape, label) {
      return properties.find(function(item){ return item.refToView().any(function(toView){ return label.id == shape.id + toView })});
  },

  getEditableProperties: function (shapeNode) {
      // Get all properties which where at least one ref to view is set
    var props = shapeNode.getStencil().properties().findAll(function(item){ 
      return (item.refToView() 
          &&  item.refToView().length > 0
          &&  item.directlyEditable()); 
    });
    
    return props.findAll(function(item){ return !item.readonly() &&  item.type() == ORYX.CONFIG.TYPE_STRING });
  }

});

}(jQuery, arquematics, ORYX, document, window));
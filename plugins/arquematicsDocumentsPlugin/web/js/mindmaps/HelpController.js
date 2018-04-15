/**
 * <pre>
 * Listens to HELP_COMMAND and displays notifications.
 * Provides interactive tutorial for first time users.
 * </pre>
 * 
 * @constructor
 * @param {mindmaps.EventBus} eventBus
 * @param {mindmaps.commandRegistry} commandRegistry
 * @param {mindmaps.mindmapModel} mindmapModel
 */
mindmaps.HelpController = function(eventBus, commandRegistry, mindmapModel) {

 
  /**
   * Prepares notfications to show for help command.
   */
  function setupHelpButton() 
  {
      
    var command = commandRegistry.get(mindmaps.HelpCommand);
    command.setHandler(showHelp);

    var notifications = [];
    function showHelp() 
    {
        
       var renderer = new mindmaps.StaticCanvasRenderer();
       
       var doc = mindmapModel.getDocument();
       
       var data = renderer.renderAsDataPNG(doc);
       var dataJson = doc.prepareSave().serialize();
       $('#diagram_vector_image').val(data);
       $('#diagram_json').val(dataJson);
    
       $('#form_diagram').submit();
            
     //
    }
    
  }

  setupHelpButton();
};

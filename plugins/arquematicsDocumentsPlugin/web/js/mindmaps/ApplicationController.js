/**
 * Creates a new Application Controller.
 * 
 * @constructor
 */
mindmaps.ApplicationController = function() {
  var eventBus = new mindmaps.EventBus();
  var shortcutController = new mindmaps.ShortcutController();
  var commandRegistry = new mindmaps.CommandRegistry(shortcutController);
  var undoController = new mindmaps.UndoController(eventBus, commandRegistry);
  var mindmapModel = new mindmaps.MindMapModel(eventBus, commandRegistry, undoController);
  var clipboardController = new mindmaps.ClipboardController(eventBus,
      commandRegistry, mindmapModel);
      
  //var helpController = new mindmaps.HelpController(eventBus, commandRegistry, mindmapModel);
  
  var saveController = new mindmaps.SaveController(eventBus, commandRegistry, mindmapModel);
  
  //var printController = new mindmaps.PrintController(eventBus,
  //    commandRegistry, mindmapModel);
      
  //var autosaveController = new mindmaps.AutoSaveController(eventBus, mindmapModel);
  
  //var filePicker = new mindmaps.FilePicker(eventBus, mindmapModel);
 

  /**
   * Handles the new document command.
   */
  function doNewDocument() {
    // close old document first
    var doc = mindmapModel.getDocument();
    doCloseDocument();

    var presenter = new mindmaps.NewDocumentPresenter(eventBus,
        mindmapModel, new mindmaps.NewDocumentView());
    presenter.go();
  }
  

  /**
   * Handles the save document command.
   */
  function doSaveDocument() {
    /*var presenter = new mindmaps.SaveDocumentPresenter(eventBus,
        mindmapModel, new mindmaps.SaveDocumentView(), autosaveController, filePicker);
    */
    var presenter = new mindmaps.SaveDocumentPresenter(eventBus,
        mindmapModel, new mindmaps.SaveDocumentView());
    presenter.go();
  }

  /**
   * Handles the close document command.
   */
  function doCloseDocument() {
    var doc = mindmapModel.getDocument();
    if (doc) {
      // TODO for now simply publish events, should be intercepted by
      // someone
      mindmapModel.setDocument(null);
    }
  }

  /**
   * Handles the open document command.
   */
  function doOpenDocument() {
    var presenter = new mindmaps.OpenDocumentPresenter(eventBus,
        mindmapModel, new mindmaps.OpenDocumentView(), filePicker);
    presenter.go();
  }

  function doExportDocument() {
    var presenter = new mindmaps.ExportMapPresenter(eventBus,
        mindmapModel, new mindmaps.ExportMapView());
    presenter.go();
  }
  
  this.getMindmapModel = function()
  {
  
    return mindmapModel; 
     
  };

  /**
   * Initializes the controller, registers for all commands and subscribes to
   * event bus.
   */
  this.init = function() 
  {
      
    var newDocumentCommand = commandRegistry
        .get(mindmaps.NewDocumentCommand);
    newDocumentCommand.setHandler(doNewDocument);
    newDocumentCommand.setEnabled(true);

    var openDocumentCommand = commandRegistry
        .get(mindmaps.OpenDocumentCommand);
    openDocumentCommand.setHandler(doOpenDocument);
    openDocumentCommand.setEnabled(true);

    var saveDocumentCommand = commandRegistry
        .get(mindmaps.SaveDocumentCommand);
    saveDocumentCommand.setHandler(doSaveDocument);

    var closeDocumentCommand = commandRegistry
        .get(mindmaps.CloseDocumentCommand);
    closeDocumentCommand.setHandler(doCloseDocument);

    
    var exportCommand = commandRegistry.get(mindmaps.ExportCommand);
    exportCommand.setHandler(doExportDocument);
    
     
    eventBus.subscribe(mindmaps.Event.DOCUMENT_CLOSED, function() {
      saveDocumentCommand.setEnabled(false);
      closeDocumentCommand.setEnabled(false);
      exportCommand.setEnabled(false);
    });

    
    eventBus.subscribe(mindmaps.Event.DOCUMENT_OPENED, function() {
      saveDocumentCommand.setEnabled(true);
      closeDocumentCommand.setEnabled(true);
      exportCommand.setEnabled(true);
    });
  };

  /**
   * Launches the main view controller.
   */
  this.go = function() {
    var viewController = new mindmaps.MainViewController(eventBus,
        mindmapModel, commandRegistry);
    viewController.go();
    
    if (mindmaps.autoload)
    {
       try {
        //decodifica los datos si es necesario
        if (arquematics.crypt)
        {
              mindmaps.PASS = arquematics.crypt.decryptHexToString(mindmaps.PASS);
              mindmaps.DATA = arquematics.simpleCrypt.decryptHex(mindmaps.PASS, mindmaps.DATA);
              $('#note_title').val(arquematics.simpleCrypt.decryptHex(mindmaps.PASS, $('#note_title').val()));
        }
        var doc = mindmaps.Document.fromJSON(mindmaps.DATA);
       } catch (e) {
        eventBus.publish(mindmaps.Event.NOTIFICATION_ERROR, 'File is not a valid mind map!');
        throw new Error('Error while opening map from hdd', e);
       }
       mindmapModel.setDocument(doc);
    }
    else
    {
        doNewDocument();
    }
  };

  this.init();
};

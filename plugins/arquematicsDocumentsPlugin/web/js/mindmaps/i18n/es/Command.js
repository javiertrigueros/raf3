
var App = {};
App.CENTRAL_IDEA = "Idea Central";
App.NEW_IDEA = "Nueva idea";

App.INSPECTOR = "Inspector";
App.NAVIGATOR = "Navegador";

/**
 * Creates a new command. Base class for all commands
 * 
 * @constructor
 * @borrows EventEmitter
 */
mindmaps.Command = function() {
  this.id = "BASE_COMMAND";
  this.shortcut = null;
  /**
   * The handler function.
   * 
   * @private
   * @function
   */
  this.handler = null;
  this.label = null;
  this.description = null;

  /**
   * @private
   */
  this.enabled = false;
};

/**
 * Events that can be emitted by a command object.
 * @namespace
 */
mindmaps.Command.Event = {
  HANDLER_REGISTERED : "HandlerRegisteredCommandEvent",
  HANDLER_REMOVED : "HandlerRemovedCommandEvent",
  ENABLED_CHANGED : "EnabledChangedCommandEvent"
};

mindmaps.Command.prototype = {
  /**
   * Executes the command. Tries to call the handler function.
   */
  execute : function() {
    if (this.handler) {
      this.handler();
      if (mindmaps.DEBUG) {
        console.log("handler called for", this.id);
      }
    } else {
      if (mindmaps.DEBUG) {
        console.log("no handler found for", this.id);
      }
    }
  },

  /**
   * Registers a new handler.
   * 
   * @param {Function} handler
   */
  setHandler : function(handler) {
    this.removeHandler();
    this.handler = handler;
    this.publish(mindmaps.Command.Event.HANDLER_REGISTERED);
  },

  /**
   * Removes the current handler.
   */
  removeHandler : function() {
    this.handler = null;
    this.publish(mindmaps.Command.Event.HANDLER_REMOVED);
  },

  /**
   * Sets the enabled state of the command.
   * 
   * @param {Boolean} enabled
   */
  setEnabled : function(enabled) {
    this.enabled = enabled;
    this.publish(mindmaps.Command.Event.ENABLED_CHANGED, enabled);
  }
};
/**
 * Mixin EventEmitter into command objects.
 */
EventEmitter.mixin(mindmaps.Command);

/**
 * Node commands
 */

/**
 * Creates a new CreateNodeCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.CreateNodeCommand = function() {
  this.id = "CREATE_NODE_COMMAND";
  this.shortcut = "tab";
  this.label = "Agregar";
  this.icon = "glyphicon glyphicon-plus-sign";
  this.description = "Crea un nuevo nodo";
};
mindmaps.CreateNodeCommand.prototype = new mindmaps.Command();

/**
 * Creates a new CreateSiblingNodeCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.CreateSiblingNodeCommand = function() {
  this.id = "CREATE_SIBLING_NODE_COMMAND";
  this.shortcut = "shift+tab";
  this.label = "Agregar";
  this.icon = "glyphicon glyphicon-plus-sign";
  this.description = "Crear nodo hermano";
};
mindmaps.CreateSiblingNodeCommand.prototype = new mindmaps.Command();

/**
 * Creates a new DeleteNodeCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.DeleteNodeCommand = function() {
  this.id = "DELETE_NODE_COMMAND";
  this.shortcut = ["del", "backspace"];
  this.label = "Eliminar";
  this.icon = "glyphicon glyphicon-minus-sign";
  this.description = "Eliminar nodo";
};
mindmaps.DeleteNodeCommand.prototype = new mindmaps.Command();

/**
 * Creates a new EditNodeCaptionCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.EditNodeCaptionCommand = function() {
  this.id = "EDIT_NODE_CAPTION_COMMAND";
  this.shortcut = ["F2", "return"];
  this.label = "Editar etiquetas del nodo";
  this.description = "Editar texto del nodo";
};
mindmaps.EditNodeCaptionCommand.prototype = new mindmaps.Command();

/**
 * Creates a new ToggleNodeFoldedCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.ToggleNodeFoldedCommand = function() {
  this.id = "TOGGLE_NODE_FOLDED_COMMAND";
  this.shortcut = "space";
  this.description = "Mostrar o ocultar los nodos hijos";
};
mindmaps.ToggleNodeFoldedCommand.prototype = new mindmaps.Command();

/**
 * Undo commands
 */

/**
 * Creates a new UndoCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.UndoCommand = function() {
  this.id = "UNDO_COMMAND";
  this.shortcut = ["ctrl+z", "meta+z"];
  this.label = "Deshacer";
  this.icon = "icon-undo";
  this.description = "Deshacer";
};
mindmaps.UndoCommand.prototype = new mindmaps.Command();

/**
 * Creates a new RedoCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.RedoCommand = function() {
  this.id = "REDO_COMMAND";
  this.shortcut = ["ctrl+y", "meta+shift+z"];
  this.label = "Rehacer";
  this.icon = "icon-cw";
  this.description = "Rehacer";
};
mindmaps.RedoCommand.prototype = new mindmaps.Command();

/**
 * Clipboard commands
 */

/**
 * Creates a new CopyNodeCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.CopyNodeCommand = function() {
  this.id = "COPY_COMMAND";
  this.shortcut = ["ctrl+c", "meta+c"];
  this.label = "Copiar";
  this.icon = "icon-docs";
  this.description = "Copiar rama";
};
mindmaps.CopyNodeCommand.prototype = new mindmaps.Command();

/**
 * Creates a new CutNodeCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.CutNodeCommand = function() {
  this.id = "CUT_COMMAND";
  this.shortcut = ["ctrl+x", "meta+x"];
  this.label = "Cortar";
  this.icon = "icon-scissors";
  this.description = "Cortar rama";
};
mindmaps.CutNodeCommand.prototype = new mindmaps.Command();

/**
 * Creates a new PasteNodeCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.PasteNodeCommand = function() {
  this.id = "PASTE_COMMAND";
  this.shortcut = ["ctrl+v", "meta+v"];
  this.label = "Pegar";
  this.icon = "icon-paste";
  this.description = "Pegar rama";
};
mindmaps.PasteNodeCommand.prototype = new mindmaps.Command();

/**
 * Document commands
 */

/**
 * Creates a new NewDocumentCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */

mindmaps.NewDocumentCommand = function() {
  this.id = "NEW_DOCUMENT_COMMAND";
  this.label = "Nuevo";
  this.icon = "ui-icon-document-b";
  this.description = "Crear un nuevo mapa mental";
};
mindmaps.NewDocumentCommand.prototype = new mindmaps.Command();

/**
 * Creates a new OpenDocumentCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.OpenDocumentCommand = function() {
  this.id = "OPEN_DOCUMENT_COMMAND";
  this.label = "Abrir...";
  this.shortcut = ["ctrl+o", "meta+o"];
  this.icon = "ui-icon-folder-open";
  this.description = "Abrir mapa mental existente";
};
mindmaps.OpenDocumentCommand.prototype = new mindmaps.Command();

/**
 * Creates a new SaveDocumentCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.SaveDocumentCommand = function() {
  this.id = "SAVE_DOCUMENT_COMMAND";
  this.label = "Guardar...";
  this.shortcut = ["ctrl+s", "meta+s"];
  this.icon = "glyphicon glyphicon-floppy-disk";
  this.description = "Guardar mapa mental";
};
mindmaps.SaveDocumentCommand.prototype = new mindmaps.Command();

/**
 * Creates a new CloseDocumentCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.CloseDocumentCommand = function() {
  this.id = "CLOSE_DOCUMENT_COMMAND";
  this.label = "Cerrar";
  this.icon = "ui-icon-close";
  this.description = "Cerrar mapa mental";
};
mindmaps.CloseDocumentCommand.prototype = new mindmaps.Command();



/**
 * Creates a new PrintCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.PrintCommand = function() {
  this.id = "PRINT_COMMAND";
  this.icon = "ui-icon-print";
  this.label = "Imprimir";
  this.shortcut = ["ctrl+p", "meta+p"];
  this.description = "Imprimir mapa mental";
};
mindmaps.PrintCommand.prototype = new mindmaps.Command();

/**
 * Creates a new ExportCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.ExportCommand = function() {
  this.id = "EXPORT_COMMAND";
  this.icon = "ui-icon-image";
  this.label = "Exportar como imagen...";
  this.description = "Exportat mapa mental como imagen";
};
mindmaps.ExportCommand.prototype = new mindmaps.Command();

/**
 * Creates a new ExportCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.SaveCommand = function() {
  this.id = "SAVE_COMMAND";
  this.icon = "glyphicon glyphicon-floppy-disk";
  this.label = "Guardar";
  this.description = "Guardar mapa mental";
};
mindmaps.SaveCommand.prototype = new mindmaps.Command();


/**
 * Creates a new SaveArCommand
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.SaveArCommand = function() {
  this.id = "SAVE_ARQUEMATICS_COMMAND";
  this.enabled = true;
  this.icon = "glyphicon glyphicon-floppy-disk";
  this.label = "Guardar y salir";
  this.shortcut = "F4";
  this.description = "Guardar mapa mental";
};
mindmaps.SaveArCommand.prototype = new mindmaps.Command();
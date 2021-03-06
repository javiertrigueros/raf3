/*global define*/
define([
    'underscore',
    'jquery',
    'marionette',
    'app',
    'helpers/uri',
    'enquire'
], function (_, $,  Marionette, App, URI, enquire) {
    'use strict';

    /**
     * Submodule which shows note content
     */
    var AppNote = App.module('AppNote', { startWithParent: false }),
        executeAction,
        API;

    AppNote.on('start', function () {
        App.mousetrap.API.restart();
        App.AppNavbar.start();

        App.log('AppNote is started');
    });

    AppNote.on('stop', function () {
        App.log('AppNote is stoped');
    });

    /**
     * The router
     */
    AppNote.Router = Marionette.AppRouter.extend({
        appRoutes: {
            'note/'                                            : 'showNotes',
            'note/add'                                         : 'addNote',
            'note/share/:id'                                   : 'shareNote',
            'note/edit/:id'                                    : 'editNote',
            'note/remove/:id'                                  : 'removeNote',
            'note(/f/:filter)(/q/:query)(/p:page)'             : 'showNotes',
            'note(/f/:filter)(/q/:query)(/p:page)(/show/:id)'  : 'showNote',
            
            'excel/'                                            : 'showListExcel',
            'excel/add'                                         : 'addExcel',
            'excel/share/:id'                                   : 'shareNote',
            'excel/edit/:id'                                    : 'editExcel',
            'excel/remove/:id'                                  : 'removeNote',
            'excel(/f/:filter)(/q/:query)(/p:page)'             : 'showListExcel',
            'excel(/f/:filter)(/q/:query)(/p:page)(/show/:id)'  : 'showExcel',
            
            'svg/'                                                          : 'showListSvg',
            'svg/remove/:id'                                                : 'removeNote',
            'svg(/f/:filter)(/q/:query)(/p:page)'                           : 'showListSvg',
            'svg(/f/:filter)(/q/:query)(/p:page)(/show/:id)'                : 'showSvg',
            
            'rawchart/'                                                     : 'showListRawchart',
            'rawchart/remove/:id'                                           : 'removeNote',
            'rawchart(/f/:filter)(/q/:query)(/p:page)'                      : 'showListRawchart',
            'rawchart(/f/:filter)(/q/:query)(/p:page)(/show/:id)'           : 'showRawchart',
            
            'mindmaps/'                                                     : 'showListMindmaps',
            'mindmaps/remove/:id'                                           : 'removeNote',
            'mindmaps(/f/:filter)(/q/:query)(/p:page)'                      : 'showListMindmaps',
            'mindmaps(/f/:filter)(/q/:query)(/p:page)(/show/:id)'           : 'showMindmaps',
            
            'bpmn'                                                          : 'showListBpmn',
            'bpmn/remove/:id'                                               : 'removeNote',
            'bpmn(/f/:filter)(/q/:query)(/p:page)'                          : 'showListBpmn',
            'bpmn(/f/:filter)(/q/:query)(/p:page)(/show/:id)'               : 'showBpmn',
            
            'epc/'                                                          : 'showListEpc',
            'epc/remove/:id'                                                : 'removeNote',
            'epc(/f/:filter)(/q/:query)(/p:page)'                           : 'showListEpc',
            'epc(/f/:filter)(/q/:query)(/p:page)(/show/:id)'                : 'showEpc',
            
            'uml/'                                                          : 'showListUml',
            'uml/remove/:id'                                                : 'removeNote',
            'uml(/f/:filter)(/q/:query)(/p:page)'                           : 'showListUml',
            'uml(/f/:filter)(/q/:query)(/p:page)(/show/:id)'                : 'showUml',
            
            'umlsequence/'                                                  : 'showListUmlsequence',
            'umlsequence/remove/:id'                                        : 'removeNote',
            'umlsequence(/f/:filter)(/q/:query)(/p:page)'                   : 'showListUmlsequence',
            'umlsequence(/f/:filter)(/q/:query)(/p:page)(/show/:id)'        : 'showUmlsequence',
            
            'wireframe/'                                                   : 'showListWireframe',
            'wireframe/remove/:id'                                         : 'removeNote',
            'wireframe(/f/:filter)(/q/:query)(/p:page)'                    : 'showListWireframe',
            'wireframe(/f/:filter)(/q/:query)(/p:page)(/show/:id)'         : 'showWireframe',

            'umlusecase/'                                                   : 'showListUmlusecase',
            'umlusecase/remove/:id'                                         : 'removeNote',
            'umlusecase(/f/:filter)(/q/:query)(/p:page)'                    : 'showListUmlusecase',
            'umlusecase(/f/:filter)(/q/:query)(/p:page)(/show/:id)'         : 'showUmlusecase'
        }
    });

    /**
     * Start application
     */
    executeAction = function (action, args)
    {
        App.startSubApp('AppNote');
        action(args);
    };

    /**
     * Controller
     */
    API = {
        notesArg: null,

        getArgs: function (args) {
            var values = ['filter', 'query', 'page', 'id'],
                argsObj = {};

            if (args.length === 1 && typeof args[0] === 'object') {
                return args[0];
            }

            _.each(values, function (value, index) {
                argsObj[value] = (args[index]) ? args[index] : null;
            });

            argsObj.page = Number(argsObj.page);
            //argsObj.profile = argsObj.profile || URI.getProfile();
            return argsObj;
        },
        /**
         * no tenemos filtro, de favoritos o trash
         */
        getArgsSimple: function (args) {
            var values = ['filter','query', 'page', 'id'],
                argsObj = {};

            _.each(values, function (value, index) {
                argsObj[value] = (args[index]) ? args[index] : null;
            });

            argsObj.page = Number(argsObj.page);
            return argsObj;
        },
        
        showListSvg: function()
        {
            var args = this.getArgsSimple(arguments);
            //docType = svg  imagenes vectoriales
            args.docType = 'svg';
            
            require(['apps/notes/list/controller'], function (List) {
                executeAction(new List().listSvg, args);
            });
        },
        
        showListExcel: function()
        {
            var args = this.getArgsSimple(arguments);
            //docType = svg  imagenes vectoriales
            args.docType = 'svg';
            
            require(['apps/notes/list/controller'], function (List) {
                executeAction(new List().listSvg, args);
            });
        },
        
        showExcel: function () {
            var args = this.getArgsSimple(arguments);
            args.docType = 'svg';
           
            require(['apps/notes/show/showController'], function (Show) {
                App.trigger('doc:loading', args);
                App.trigger('notes:show', args);
                executeAction(new Show().showNote, args);
            });
        },
        
        // Add new note
        addExcel: function ()
        {
            require(['apps/notes/form/controller'], function (Form) {
                executeAction(new Form().addExcel);
            });
        },

      
        // Edit an existing note
        editExcel: function (id) {
            
            require(['apps/notes/form/controller'], function (Form) {
                executeAction(new Form().editNote, {id : id});
            });
        },
        
        
        showSvg: function () {
            var args = this.getArgsSimple(arguments);
            args.docType = 'svg';
           
            require(['apps/notes/show/showController'], function (Show) {
                App.trigger('doc:loading', args);
                App.trigger('notes:show', args);
                executeAction(new Show().showNote, args);
            });
        },
        
        showListRawchart: function()
        {
            var args = this.getArgsSimple(arguments);
            args.docType = 'rawchart';
            require(['apps/notes/list/controller'], function (List) {                
                App.trigger('notes:show', args);
                executeAction(new List().listSvg, args);
            });
        },
        
        showRawchart: function () {
            var args = this.getArgsSimple(arguments);
            args.docType = 'rawchart';
           
            require(['apps/notes/show/showController'], function (Show) {
                App.trigger('doc:loading', args);
                App.trigger('notes:show', args);
                executeAction(new Show().showNote, args);
            });
        },
        
        showMindmaps: function () {
            var args = this.getArgsSimple(arguments);
            args.docType = 'mindmaps';
           
            require(['apps/notes/show/showController'], function (Show) {
                App.trigger('doc:loading', args);
                App.trigger('notes:show', args);
                executeAction(new Show().showNote, args);
            });
        },

        showListWireframe: function()
        {
            var args = this.getArgsSimple(arguments);
            args.docType = 'wireframe';

            require(['apps/notes/list/controller'], function (List) {
                executeAction(new List().listSvg, args);
            });
        },
        
        showListMindmaps: function()
        {
            var args = this.getArgsSimple(arguments);
            args.docType = 'mindmaps';
            
            require(['apps/notes/list/controller'], function (List) {
                executeAction(new List().listSvg, args);
            });
        },
        
        showListBpmn: function()
        {
            var args = this.getArgsSimple(arguments);
            //docType = svg  imagenes vectoriales
            args.docType = 'bpmn';
            
            require(['apps/notes/list/controller'], function (List) {
                executeAction(new List().listSvg, args);
            });
        },
        
        showWireframe: function () {
            var args = this.getArgsSimple(arguments);
            args.docType = 'wireframe';
           
            require(['apps/notes/show/showController'], function (Show) {
                App.trigger('doc:loading', args);
                App.trigger('notes:show', args);
                executeAction(new Show().showNote, args);
            });
        },

        showBpmn: function () {
            var args = this.getArgsSimple(arguments);
            args.docType = 'bpmn';
           
            require(['apps/notes/show/showController'], function (Show) {
                App.trigger('doc:loading', args);
                App.trigger('notes:show', args);
                executeAction(new Show().showNote, args);
            });
        },
        
        showListEpc: function()
        {
            var args = this.getArgsSimple(arguments);
            args.docType = 'epc';
            
            require(['apps/notes/list/controller'], function (List) {
                executeAction(new List().listSvg, args);
            });
        },
        
        showEpc: function () {
            var args = this.getArgsSimple(arguments);
            args.docType = 'epc';
           
            require(['apps/notes/show/showController'], function (Show) {
                App.trigger('doc:loading', args);
                App.trigger('notes:show', args);
                executeAction(new Show().showNote, args);
            });
        },
        
        showListUml: function()
        {
            var args = this.getArgsSimple(arguments);
            args.docType = 'uml';
            
            require(['apps/notes/list/controller'], function (List) {
                executeAction(new List().listSvg, args);
            });
        },
        
        showUml: function () {
            var args = this.getArgsSimple(arguments);
            args.docType = 'uml';
           
            require(['apps/notes/show/showController'], function (Show) {
                App.trigger('doc:loading', args);
                App.trigger('notes:show', args);
                executeAction(new Show().showNote, args);
            });
        },
        
        showListUmlsequence: function()
        {
            var args = this.getArgsSimple(arguments);
            args.docType = 'umlsequence';
            
            require(['apps/notes/list/controller'], function (List) {
                executeAction(new List().listSvg, args);
            });
        },
        
        showUmlsequence: function () {
            var args = this.getArgsSimple(arguments);
            args.docType = 'umlsequence';
           
            require(['apps/notes/show/showController'], function (Show) {
                App.trigger('doc:loading', args);
                App.trigger('notes:show', args);
                executeAction(new Show().showNote, args);
            });
        },
        
        showListUmlusecase: function()
        {
            var args = this.getArgsSimple(arguments);
            args.docType = 'umlusecase';
            
            require(['apps/notes/list/controller'], function (List) {
                executeAction(new List().listSvg, args);
            });
        },
        
        showUmlusecase: function () {
            var args = this.getArgsSimple(arguments);
            args.docType = 'umlusecase';
           
            require(['apps/notes/show/showController'], function (Show) {
                App.trigger('doc:loading', args);
                App.trigger('notes:show', args);
                executeAction(new Show().showNote, args);
            });
        },
        
        // Show list of notes
        showNotes: function () {
            var args = this.getArgs(arguments);
            args.docType = 'note';
            
            require(['apps/notes/list/controller'], function (List) {
                executeAction(new List().listNotes, args);
            });
        },

        // Show content of note
        showNote: function () {
            var args = this.getArgs(arguments);

            require(['apps/notes/show/showController'], function (Show) {
                App.trigger('doc:loading', args);
                App.trigger('notes:show', args);
                executeAction(new Show().showNote, args);
            });
        },
        
        // Edit an existing note
        shareNote: function (id)
        {
            require(['apps/notes/form/controller'], function (Form) {
                executeAction(new Form().shareNote, {id : id});
            });
        },

        // Add new note
        addNote: function ()
        {
            require(['apps/notes/form/controller'], function (Form) {
                executeAction(new Form().addNote);
            });
        },

      
        // Edit an existing note
        editNote: function (id) {
            
            require(['apps/notes/form/controller'], function (Form) {
                executeAction(new Form().editNote, {id : id});
            });
        },
        
        /*
        notesWhileEditing: function (profile) {
            if ( !API.notesArg ) {
                App.trigger('notes:show', {profile: profile});
            }
        },*/

        // Remove an existing note
        removeNote: function (id) {
            require(['apps/notes/remove/removeController'], function (Controller) {
                executeAction(new Controller().remove, {id : id});
            });
        },

        // Re-render sidebar only if necessary
        checkShowSidebar: function (args) {
            var current = _.omit(API.notesArg || {}, 'id');
            API.notesArg = args;

            if ( !_.isEqual(current,  _.omit(args, 'id')) ) {
                API.showNotes(args);
            }
        }
    };

    /**
     * Router events
     */
    App.on('notes:list', function () {
        App.navigate(URI.link('/note'), { trigger : true });
    });
    
    App.on('notes:newNote', function () {
        App.navigate(URI.link('/note/add'), { trigger : true });
    });
    
    /**
     * Router events
     */
    /*
    App.on('vectors:list', function () {
        App.navigate(URI.link('/vectors'), { trigger : true });
    });*/
   
    // Show sidebar with notes list only on big screen
    /*
    App.on('notes:show', function (args) {
        $(App.content.el).addClass('active-row');
        enquire.register('screen and (min-width:768px)', {
            match: function () {
                API.checkShowSidebar(args);
            },
            unmatch: function () {
                API.notesArg = args;
            }
        });
    });*/
    
    
    App.on('doc:loading', function (args) {
        $('body').addClass('loading');
    });
    
    App.on('doc:loaded', function (args) {
        $('body').removeClass('loading');
    });
    

    // Toggle to sidebar
    App.on('notes:toggle', function (args) {
        $(App.content.el).removeClass('active-row');
        API.checkShowSidebar(args);
    });

    // Re render
    App.on('notes:rerender', function () {
        API.showNotes(API.notesArg || {});
    });

    // Re-render sidebar if new note has been added
    App.on('notes:added', function (model) {
        API.showNotes(_.extend(API.notesArg || {}, {id: model.get('id')}));
    });

    // Show form
    AppNote.on('showForm', function () {
        App.navigate(URI.link('/notes/add'), true);
    });

    // Navigate to last note
    AppNote.on('navigate:back', function () {
        var url = URI.note(API.notesArg, API.notesArg);
        App.navigate(url, true);
    });

    // Re-render sidebar's and note's content after sync:after event
    App.on('sync:after', function () {
        
        // Re-render sidebar and note's content
        if ( App.currentApp.moduleName === 'AppNote' &&
           !App.getCurrentRoute().match(/\/[edit|add]+/) )
       {
           
            var notesArg = _.extend(API.notesArg || {}, {
                profile : URI.getProfile()
            });

            API.showNotes(notesArg);
            if (notesArg.id) {
                API.showNote(notesArg);
                //App.trigger('notes:rerender');
            }
        } 
    });

    /**
     * Register the router
     */
    App.addInitializer(function(){
        new AppNote.Router({
            controller: API
        });
    });

    return AppNote;
});

/*global define*/
define([
    'underscore',
    'app',
    'backbone',
    'marionette',
    'helpers/uri',
    'collections/notes',
    'apps/notes/list/views/noteSidebar',
    'apps/notes/list/views/appLogo',
    'apps/notes/show/views/noteViewMsg'
], function (_, App, Backbone, Marionette, URI, Notes, NotesSidebar, AppLogo, NoteViewMsg) {
    'use strict';

    var List = App.module('AppNote.List');

    /**
     * Notes list controller - shows notes list in sidebar
     */
    List.Controller = Marionette.Controller.extend({
        //ultimo filtro utilizado
        lastFilter: '',
        //se ha lanzado el evento de scroll
        onScrollFire: false,
        
        initialize: function ()
        {
            _.bindAll(this, 'loadNextPage','endLoadNextPage','listNotes', 'listSvg', 'showSidebar', 'favoriteNotes');
            
            this.onScrollFire = false;
             
            this.notes = new Notes();

            //ya no redirecciona fuera
            App.hasRedirOut = false;
            // Application events
            App.on('notes:show', this.changeFocus, this);
            App.on('notes:next', this.toNextNote, this);

            //loadNextPage
            this.listenTo(this.notes, 'loadNextPage', this.loadNextPage, this);
            
            //DOCTYPES
            this.listenTo(this.notes, 'filter:svg', this.activeSvg, this);
            this.listenTo(this.notes, 'filter:rawchart', this.activeRawchart, this);
            this.listenTo(this.notes, 'filter:mindmaps', this.activeMindmaps, this);
            this.listenTo(this.notes, 'filter:epc', this.activeEpc, this);
            this.listenTo(this.notes, 'filter:bpmn', this.activeBpmn, this);
            this.listenTo(this.notes, 'filter:uml', this.activeUml, this);
            this.listenTo(this.notes, 'filter:umlsequence', this.activeUmlsequence, this);
            this.listenTo(this.notes, 'filter:umlusecase', this.activeUmlusecase, this);
            this.listenTo(this.notes, 'filter:wireframe', this.activeWireframe, this);
           
            // Filter
            this.listenTo(this.notes, 'filter:all', this.activeNotes, this);
            this.listenTo(this.notes, 'filter:favorite', this.favoriteNotes, this);
            this.listenTo(this.notes, 'filter:trashed', this.trashedNotes, this);
            this.listenTo(this.notes, 'filter:search', this.searchNotes, this);
           
            // Navigation with keys
            this.listenTo(this.notes, 'navigateTop', this.toPrevNote, this);
            this.listenTo(this.notes, 'navigateBottom', this.toNextNote, this);
        },

        /**
         * Fetch notes, then show it
         */
        listNotes: function (args)
        {
            this.args = _.clone(args) || this.args;
            App.settings.pagination = parseInt(App.settings.pagination);

            this.query = {};
            
            //borra el contenido seleccionado
            App.AppNavbar.trigger('resetContent', this.args);
            
            // Filter
            if (_.isNull(this.args) === false && this.args.filter)
            {
                this.lastFilter = 'filter:' + this.args.filter;
                this.notes.trigger('filter:' + this.args.filter);
            } else {
                this.lastFilter = 'filter:all';
                this.notes.trigger('filter:all');
            }
        },
        
        loadNextPage: function()
        {
          if (this.notes.hasNextPage() && !this.onScrollFire)
          {
           this.onScrollFire = true;
           this.notes.trigger('showMore');
           //remove false para que no haga reset en la
           //colección cuando carga nuevo contenido
            $.when( this.notes.getNextPage({remove: false}))
                .done(this.endLoadNextPage);
          }
        },
        endLoadNextPage: function()
        {
          this.onScrollFire = false;
          this.notes.trigger('onFinishedLoad');
        },
        
        /**
         * Show list of Svg
         */
        listSvg: function (args)
        {
           this.args = _.clone(args) || this.args;
           
           this.query = {};
           
           //borra todo el contenido
           App.AppNavbar.trigger('resetContent', this.args);
           
           if ( this.args.docType === 'svg')
           {
             this.lastFilter = 'filter:svg';
             this.notes.trigger('filter:svg');    
           }
           else if (this.args.docType === 'rawchart')
           {
             this.lastFilter = 'filter:rawchart';
             this.notes.trigger('filter:rawchart');   
           }
           else if (this.args.docType === 'mindmaps')
           {
             this.lastFilter = 'filter:mindmaps';
             this.notes.trigger('filter:mindmaps');   
           }
           else if (this.args.docType === 'bpmn')
           {
             this.lastFilter = 'filter:bpmn';
             this.notes.trigger('filter:bpmn');   
           }
           else if (this.args.docType === 'epc')
           {
             this.lastFilter = 'filter:epc';
             this.notes.trigger('filter:epc');  
           }
           else if (this.args.docType === 'uml')
           {
             this.lastFilter = 'filter:uml';
             this.notes.trigger('filter:uml');  
           }
           else if (this.args.docType === 'umlsequence')
           {
             this.lastFilter = 'filter:umlsequence';
             this.notes.trigger('filter:umlsequence');   
           }
           else if (this.args.docType === 'umlusecase')
           {
             this.lastFilter = 'filter:umlusecase';
             this.notes.trigger('filter:umlusecase');       
           }
           else if (this.args.docType === 'wireframe')
           {
             this.lastFilter = 'filter:wireframe';
             this.notes.trigger('filter:wireframe');  
           }
        },
        
        /**
         * Show only active svg
         */
        activeSvg: function ()
        {
           this.notes.setCollectionParams(
           {
                docType: 'svg',
                trash: false,
                isFavorite: false
           });
            
           $.when(
            this.notes.fetch({ data: {page: this.args.page}})
            ).done(this.showSidebar);
        },
        
         /**
         * Show only active activeMindmaps
         */
        activeMindmaps: function ()
        {
           this.notes.setCollectionParams(
           {
                docType: 'mindmaps',
                trash: false,
                isFavorite: false
           });
           
           $.when(
            this.notes.fetch({ data: {page: this.args.page}})
            ).done(this.showSidebar);
            
        },
            
        /**
        * Show only active activeEpc
        */
        activeEpc: function ()
        {
           this.notes.setCollectionParams(
           {
                docType: 'epc',
                trash: false,
                isFavorite: false
           });
           
           $.when(
            this.notes.fetch({ data: {page: this.args.page}})
            ).done(this.showSidebar);
            
        },

        /**
        * Show only active activeEpc
        */
        activeBpmn: function ()
        {
           this.notes.setCollectionParams(
           {
                docType: 'bpmn',
                trash: false,
                isFavorite: false
           });
           
           $.when(
            this.notes.fetch({ data: {page: this.args.page}})
            ).done(this.showSidebar);
            
        },    
            
        /**
        * Show only active activeEpc
        */
        activeUml: function ()
        {
           this.notes.setCollectionParams(
           {
                docType: 'uml',
                trash: false,
                isFavorite: false
           });
           
           $.when(
            this.notes.fetch({ data: {page: this.args.page}})
            ).done(this.showSidebar);
            
        },  
        
        /**
        * Show only active activeUmlsequence
        */
        activeUmlsequence: function ()
        {
           this.notes.setCollectionParams(
           {
                docType: 'umlsequence',
                trash: false,
                isFavorite: false
           });
           
           $.when(
            this.notes.fetch({ data: {page: this.args.page}})
            ).done(this.showSidebar);
            
        },  
        
        /**
        * Show only active activeUmlsequence
        */
        activeUmlusecase: function ()
        {
           this.notes.setCollectionParams(
           {
                docType: 'umlusecase',
                trash: false,
                isFavorite: false
           });
           
           $.when(
            this.notes.fetch({ data: {page: this.args.page}})
            ).done(this.showSidebar);
            
        },  

        /**
        * Show only active activeUmlsequence
        */
        activeWireframe: function ()
        {

           this.notes.setCollectionParams(
           {
                docType: 'wireframe',
                trash: false,
                isFavorite: false
           });
           
           $.when(
            this.notes.fetch({ data: {page: this.args.page}})
            ).done(this.showSidebar);
            
        },  
          
        /**
         * Show only active rawchart
         */
        activeRawchart: function ()
        {
           this.notes.setCollectionParams(
           {
                docType: 'rawchart',
                trash: false,
                isFavorite: false
           });
           
           $.when(
            this.notes.fetch({ data: {page: this.args.page}})
            ).done(this.showSidebar);
            
        },
        
        
        /**
         * Show only active notes
         */
        activeNotes: function ()
        {
           
           this.notes.setCollectionParams(
            {
                docType: 'note',
                trash: false,
                isFavorite: false
            });
           
           $.when(
                this.notes.fetch({ data: {page: this.args.page}})
            ).done(this.showSidebar);
            
        },

        /**
         * Show favorite notes
         */
        favoriteNotes: function ()
        {
            this.notes.setCollectionParams(
            {
                docType: false,
                trash: false,
                isFavorite: 1
            });
        
            $.when(
             this.notes.fetch({ data: {page: this.args.page}})
            ).done(this.showSidebar);
        },

        /**
         * Show only removed notes
         */
        trashedNotes: function ()
        {
           //this.args.docType = 'trash';
            
           this.notes.setCollectionParams(
           {
                docType: false,
                trash: 1,
                isFavorite: false
           });
           
            $.when(
                    this.notes.fetch({ data: { page: this.args.page}})
            ).done(this.showSidebar);
        },

        /**
         * Notes with notebook
         */
        notebooksNotes: function ()
        {
            $.when(
                this.notes.fetch({
                    conditions: (  {notebookId : this.args.query} )
                })
            ).done(this.showSidebar);
        },

        /**
         * Notes which tagged with :tag
         */
        taggedNotes: function ()
        {
            var self = this,
                notes;
            $.when(
                this.notes.fetch({
                    conditions: ( {trash : 0} )
                })
            ).done(
                function () {
                    notes = self.notes.getTagged(self.args.query);
                    self.notes.reset(notes);
                    self.showSidebar();
                }
            );
        },

        /**
         * Search notes
         */
        searchNotes: function ()
        {
            var self = this,
                notes;
            $.when(
                // Fetch without limit, because with encryption, searching is impossible
                this.notes.fetch({
                    conditions: {trash : 0}
                })
            ).done(
                function () {
                    notes = self.notes.search(self.args.query);
                    self.notes.reset(notes);
                    self.showSidebar();
                }
            );
        },

        /**
         * muestra la lista de elementos
         */
        showSidebar: function ()
        {
            
            //: TODO mira el error. No cumple las condiciones
            // del fetch
            // esto es un mal arreglo
            //this.notes.filterList(this.args.filter, this.args.query);
            
            // Pagination
            /*
            if (this.notes.length > App.settings.pagination) {
                var notes = this.notes.pagination(this.args.page, App.settings.pagination);
                this.notes.reset(notes);
            }
            else if (this.args.page > 1) {
                this.notes.reset([]);
            }*/

            /*
            // Next page
            if (this.notes.length === App.settings.pagination) {
                this.args.next = this.args.page + App.settings.pagination;
            } else {
                this.args.next = this.args.page;
            }

            // Previous page
            if (this.args.page > App.settings.pagination) {
                this.args.prev = this.args.page - App.settings.pagination;
            }*/
            
            // Next page
            /*
            if (this.notes.length === App.settings.pagination) {
                this.args.next = this.args.page + 1;
            } else {
                this.args.next = this.args.page;
            }

            // Previous page
            if (this.args.page > App.settings.pagination) {
                this.args.prev = this.args.page - 1;
            }*/
            
            //this.notes.reset(notes);
            
            /*
            console.log('this.notes.totalPages');
            console.log(this.notes.totalPages);
            console.log('this.notes');
            console.log(this.notes);
            */
           
            /*
            if ((this.args.page == 0) || (this.args.page == 1))
            {
              this.args.next = (this.notes.state.totalPages < 2)?false:2;
              this.args.prev = false;
            }
            else if (this.args.page == this.notes.state.totalPages)
            {
               this.args.next = false;
               this.args.prev = this.args.page - 1;     
            }
            else
            {
               this.args.next = this.args.page + 1;
               this.args.prev = this.args.page - 1;     
            }*/
            
            //this.args.doctype = 'notes';
            
            var View = new NotesSidebar({
                collection : this.notes,
                args       : this.args
            });
            
            var appLogo = new AppLogo({
                collection : this.notes,
                args       : this.args
            });
            
            App.sidebar.show(View);
            App.logo.show(appLogo);
            
            // Active note
            if (this.args.id) {
                this.changeFocus(this.args);
            }
            else if (this.notes.length === 0)
            {
               App.content.show(new NoteViewMsg(this.args));
            }
            
            App.AppNavbar.trigger('titleChange', this.args);
            
        },

        changeFocus: function (args)
        {
            if ( !args ) { return; }
            this.args = args;
            this.notes.trigger('changeFocus', args.id);
        },

        /**
         * Redirects to note
         */
        toNote: function (note)
        {
            if ( !note) { return; }

            var url = URI.note(this.args, note);
            return App.navigate(url, true);
        },

        /**
         * Navigate to next note
         */
        toNextNote: function ()
        {
            console.log('toNextNote');
            console.log(this.notes.length);
            // Nothing is here
            if (this.notes.length === 0) {
                return;
            }

            var note;
            try {
                note = this.notes.get(this.args.id);
                note = note.next();
            }
            catch (e) {
                note = this.notes.at(0);
            }

            if (this.notes.length >= App.settings.pagination && this.notes.indexOf(note) < 0) {
                this.notes.trigger('nextPage');
            }

            return this.toNote(note);
        },

        /**
         * Navigate to previous note
         */
        toPrevNote: function ()
        {
            // Nothing is here
            if (this.notes.length === 0) {
                return;
            }

            var note;
            try {
                note = this.notes.get(this.args.id);
                note = note.prev();
            }
            catch (e) {
                note = this.notes.last();
            }

            if (this.args.page > 1 && this.notes.indexOf(note) < 0) {
                this.notes.trigger('prevPage');
            }

            return this.toNote(note);
        }

    });

    return List.Controller;
});

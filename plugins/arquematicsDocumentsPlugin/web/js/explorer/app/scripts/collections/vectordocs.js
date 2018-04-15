/*global define*/
define([
    'underscore',
    'backbone',
    'models/vectordoc',
    'backbone.paginator'
], function (_, Backbone, Vectordoc) {
    'use strict';
   
    var Vectordocs = Backbone.PageableCollection.extend({
        model: Vectordoc,
        idAttribute: 'id',
        
        state: {
            pageSize: 10,
            // You can use 0-based or 1-based indices, the default is 1-based.
            // You can set to 0-based by setting ``firstPage`` to 0.
            firstPage: 1,

            // Set this to the initial page index if different from `firstPage`. Can
            // also be 0-based or 1-based.
            currentPage: 1
        },
        
        queryParams: {
            // `Backbone.PageableCollection#queryParams` converts to ruby's
            // will_paginate keys by default.
            currentPage: "page",
            pageSize: "size"
        },
        
        doctype: 'svg',
        url: function()
        {
          return  '/docvector/' + this.doctype;  
        },
        
        setDocType: function(doctype)
        {
          this.doctype = doctype;
        },
        
        parseState: function (resp, queryParams, state, options) {
            return {totalPages: parseInt(resp.total_pages),
                    totalRecords: parseInt(resp.total_count)};
        },

        parseRecords: function (resp, options) {
            return resp.items;
        },
        
        /*
        parseLinks: function (resp, xhr) {
            return resp.pages;
        },*/
        
        /**
         * 
         */
        initialize: function ()
        {
           
           
        },

        comparator: function (model) {
            return -model.get('created');
        },

        filterList: function (filter, query) {
            var res;
    
            switch (filter) {
            case 'favorite':
                res = this.getFavorites();
                break;
            case 'notebook':
                res = this.getNotebookNotes(query);
                break;
            case 'trashed':
                res = this.getTrashed();
                break;
            default:
                res = this.getActive();
                break;
            }
            return this.reset(res);
        },

        /**
         * Filter the list of all notes that are favorite
         */
        getFavorites: function () {
            return this.filter(function (note) {
                return note.get('isFavorite') === 1 && note.get('trash') === 0;
            });
        },

        /**
         * Only active notes
         */
        getActive: function () {
            return this.without.apply(this, this.getTrashed());
        },

        /**
         * Show only notebook's notes
         */
        getNotebookNotes: function ( notebookId ) {
            return this.filter(function (note) {
                var notebook = note.get('notebookId');

                if (notebook !== 0) {
                    return notebook.get('id') === notebookId && note.get('trash') === 0;
                }
            });
        },

        /**
         * Show only tag's notes
         */
        getTagged: function ( tagName ) {
            return this.filter(function (note) {
                if (note.get('tags').length > 0) {
                    return (_.indexOf(note.get('tags'), tagName) !== -1) && note.get('trash') === 0;
                }
            });
        },

        /**
         * Filter the list of notes that are removed to trash
         */
        getTrashed: function () {
            return this.filter(function (note) {
                return note.get('trash') === 1;
            });
        },
        
        getByID: function (id) {
            return this.filter(function (note) {
                return note.get('id') === id;
            });
        },

        /**
         * Filter: only unencrypted, JSON data probably encrypted data
         */
        getUnEncrypted: function () {
            return this.filter(function (note) {
                try {
                    JSON.parse(note.get('title'));
                    return false;
                } catch (e) {
                    return true;
                }
            });
        },

        /**
         * Search
         */
        search : function(letters) {
            if (letters === '') {
                return this;
            }

            var pattern = new RegExp(letters, 'gim'),
                data;

            return this.filter(function(model) {
                //data = model.decrypt();
                data = model.toJSON();
                pattern.lastIndex = 0;  // Reuse regexp
                return pattern.test(data.title) || pattern.test(data.content);
            });
        },

        /**
         * Pagination
         * @var int perPage
         * @var int page
         */
        pagination : function (page, perPage)
        {
            var collection = this;
            
            collection.state = page;
            
            collection = _(collection.rest(page));
            collection = _(collection.first(perPage));

            return collection.map( function(model) {
                return model;
            });
        }
    });
    
    return Vectordocs;
});

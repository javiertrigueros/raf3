/*global define*/
define([
    'underscore',
    'backbone',
    'helpers/sync/arSyncNote',
    'app',
    'libs/utils',
    'arquematics'
], function (_, Backbone,  ArSyncNote, App, Utils, arquematics) {
    'use strict';

    /**
     * Notes model
     */
    var Model = Backbone.Model.extend({
        idAttribute: 'id',
        urlRoot: '/doc/note',
        defaults: {
            'id'            :  undefined,
            'title'         :  '',
            'content'       :  '',
            'taskAll'       :  0,
            'taskCompleted' :  0,
            'isFavorite'    :  0,
            'trash'         :  0,
            'share'         :  0,
            'dataImage'     :  '',
            'images'        :  [],
            'pass'          :  '',
            'diagramType'   :  'note',
            'guid'          : false,
            'created'       :  Date.now(),
            'updated'       :  Date.now()
        },

        validate: function (attrs) {
            var errors = [];
            if (attrs.title === '') {
                errors.push('title');
            }

            if (errors.length > 0) {
                return errors;
            }
        },
        /*
        hasChangeContent: function(data)
        {
           return !((data.title === this.title)
                    || (data.content === this.content)) ;  
        },*/
        
        hasChangeContent: function(data)
        {
           return (data.title !== this.get('title'))
                  || (data.content !== this.get('content'));
        },

        initialize: function () {

            this.on('update:any', this.updateDate);
            this.on('setFavorite', this.setFavorite);
            this.on('setShare', this.setShare);
            this.on('setRestoreFromTrash', this.setRestoreFromTrash);
            
            if (this.isNew())
            {
                this.set('guid', false);
                //genera un pass aleatorio
                this.set('pass',   arquematics.utils.randomKeyString(50))
                this.set('created', Date.now());
                this.updateDate();
            }
        },
        
         parse : function(data)
         {
            data = this.decrypt(data);
            
            //transformacion
            //hacer la operacion arquematics.codec.Base64.toString(data.dataImage)
            //no es necesario 
            data.created = Utils.parseDate(data.created);
            data.updated = Utils.parseDate(data.updated);
            //valores por defecto 0
            data.taskAll = parseInt(data.taskAll || 0);
            data.taskCompleted = parseInt(data.taskCompleted || 0);
            data.isFavorite = parseInt(data.isFavorite || 0);
            data.trash =  parseInt(data.trash || 0);
            
            return data;
         },
        
        decrypt: function (data) 
        {
            var dataClone = data || this.toJSON();

                dataClone.pass = arquematics.crypt.decryptHexToString(data.pass);

                dataClone.title = arquematics.simpleCrypt.decryptBase64(data.pass , data.title);       
                dataClone.content = arquematics.simpleCrypt.decryptBase64(data.pass , data.content);
                dataClone.dataImage = arquematics.simpleCrypt.decryptBase64(data.pass , data.dataImage);

            return dataClone;
        },
        encrypt: function (data) {
            var dataClone = data || this.toJSON();
            
            dataClone.title   = arquematics.simpleCrypt.encryptBase64(dataClone.pass , dataClone.title);
            dataClone.content = arquematics.simpleCrypt.encryptBase64(dataClone.pass , dataClone.content);
            dataClone.dataImage = arquematics.simpleCrypt.encryptBase64(dataClone.pass , dataClone.dataImage);
            
            return dataClone;
        },

        /**
         * Note's last modified time
         */
        updateDate: function () {
            this.set('updated', Date.now());
        },

        /**
         * Saves model's id for sync purposes, then destroys it
         */
        /*
        destroySync: function () {
            return new Removed().newObject(this, arguments);
        },*/

        next: function () {
            if (this.collection) {
                return this.collection.at(this.collection.indexOf(this) + 1);
            }
        },

        prev: function () {
            if (this.collection) {
                return this.collection.at(this.collection.indexOf(this) - 1);
            }
        },
        setRestoreFromTrash: function()
        {
           this.trigger('update:any');
           this.save({'trash': 0}); 
        },
        setShare: function()
        {
            var that = this;
            //deshabilita los controles para hacer otras
           //acciones
           this.trigger('disableControls');
           this.trigger('update:any');
            $.when(this.save({'share': 1}))
                 .done(function (){
                     that.trigger('enableControls'); 
                 });
        },
        
        setFavorite: function () {
            var isFavorite = (this.get('isFavorite') === 1) ? 0 : 1
            , that = this;
            //deshabilita los controles para hacer otras
            //acciones
            this.trigger('disableControls');
            this.trigger('update:any');
            $.when(this.save({'isFavorite': isFavorite}))
                 .done(function (){
                        that.trigger('enableControls');
                  }); 
        },
        
        generateId: function()
        {
           var guid = this.get('guid');
           
           if (!guid)
           {
             guid = ArSyncNote.guid();  
             this.set('guid', guid);
           }
           return guid;
        }

        ,sync: function (method, model, options)
        {
          var that = this;
          
          var success = options.success;
          options.success = function(data, status, xhr)
          {
              if(success)
              {
                 success(data, status, xhr);
                 //esto lo llama espues de terminar todo
                 if ((method === 'create') || (method === 'update'))
                 {
                    that.trigger('afterUpdateSync');      
                 }
              }
           };
          
          if (method === 'create')
          {
                  $.when(ArSyncNote.create(model.encrypt())) 
                   .then(function (data)
                    {
                        options.data = data;
                        return Backbone.sync(method, model, options);    
                    });    
          }
          else if (method === 'update')
          {
               $.when(ArSyncNote.update(model.encrypt())) 
                   .then(function (data)
                    {
                        options.data = data;
                        return Backbone.sync(method, model, options);    
                    });    
          }
          else
          {
            return Backbone.sync(method, model, options);      
          }         
        }

    });

    //Mirar
    //Model.ajaxSync = ArSyncNote.sync;
        
    return Model;
});

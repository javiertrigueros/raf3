/*global define*/
define([
    'underscore',
    'backbone',
    'helpers/sync/arSyncVector',
    'libs/utils',
    'arquematics'
], function (_, Backbone,  ArSyncVector, Utils, arquematics) {
    'use strict';

    /**
     * Vectordoc model
     */
    var Model = Backbone.Model.extend({
        idAttribute: 'id',
        defaults: {
            'id'            :  undefined,
            'title'         :  '',
            'isFavorite'    :  0,
            'dataImage'     :  '',
            'json'          :  '',
            'diagramType'   :  '',
            'trash'         :  0,
            'share'         :  0,      
            'pass'          :  '',
            'created'       :  Date.now(),
            'updated'       :  Date.now()
        },
        
        urlRoot: function()
        {
          return  '/docvector/' + this.get('diagramType');  
        },
        
            
        guid: false,

        validate: function (attrs) {
            var errors = [];
            if (attrs.title === '') {
                errors.push('title');
            }

            if (errors.length > 0) {
                return errors;
            }
        },

        initialize: function () {

            this.on('update:any', this.updateDate);
            this.on('setFavorite', this.setFavorite);
            this.on('setShare', this.setShare);
            this.on('setRestoreFromTrash', this.setRestoreFromTrash);

            if (this.isNew())
            {
                //genera un pass aleatorio
                this.set('pass',   arquematics.utils.randomKeyString(50))
                this.set('created', Date.now());
                this.updateDate();
            }
            
            this.guid = false;
        },
        
        parse : function(data)
        {
            data = this.decrypt(data);
            
            data.created = Utils.parseDate(data.created);
            data.updated = Utils.parseDate(data.updated);
            //valores por defecto 0
            data.isFavorite = parseInt(data.isFavorite || 0);
            data.trash =  parseInt(data.trash || 0);
            
            return data;
        },
        
        decrypt: function (data) 
        {
            var dataClone = data || this.toJSON();

                dataClone.pass = arquematics.crypt.decryptHexToString(data.pass);

                dataClone.title = arquematics.simpleCrypt.decryptBase64(data.pass , data.title);       
                dataClone.dataImage = arquematics.simpleCrypt.decryptBase64(data.pass , data.dataImage);
                dataClone.json = arquematics.simpleCrypt.decryptBase64(data.pass , data.json);
                    
            return dataClone;
        },
        
        encrypt: function (data) {
            var dataClone = data || this.toJSON();

            dataClone.title   = arquematics.simpleCrypt.encryptBase64(dataClone.pass , dataClone.title);
            dataClone.dataImage = arquematics.simpleCrypt.encryptBase64(dataClone.pass , dataClone.dataImage);
            dataClone.json = arquematics.simpleCrypt.encryptBase64(dataClone.pass , dataClone.json);
            
            return dataClone;
        },

        updateDate: function () {
            this.set('updated', Date.now());
        },

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
           this.trigger('update:any');
           this.save({'share': 1});
        },
        setFavorite: function () {
            var isFavorite = (this.get('isFavorite') === 1) ? 0 : 1;
            this.trigger('update:any');
            this.save({'isFavorite': isFavorite});
        },
        
        generateId: function()
        {
           if (!this.guid)
           {
             this.guid = ArSyncNote.guid();      
           }
           return this.guid;
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
              $.when(ArSyncVector.create((!this.guid)?
                        model.encrypt():
                        _.extend(model.encrypt(), {id:this.generateId()}))) 
                   .then(function (data)
                    {
                        options.data = data;
                        return Backbone.sync(method, model, options);    
                    });    
          }
          else if (method === 'update')
          {
               $.when(ArSyncVector.update(model.encrypt())) 
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
        
    return Model;
});

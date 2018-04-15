/*global define*/
define([
    'underscore',
    'jquery',
    'arquematics',
    'libs/utils',
], function (_, $, arquematics, Utils) {
    'use strict';
    
    var instance = null;

    function ArSyncNote() {
        
        var that = this;
        
        this.formOptions = {
           form_notes: '#note-form',
           
           input_note_id:               "#note_id",
           input_note_pass:             "#note_pass",
           input_note_title:            "#note_title",
           input_note_content:          "#note_content",
           input_note_data_image:       "#note_data_image",
           input_note_task_all:         "#note_task_all",
           input_note_task_complete:    "#note_task_complete",
           input_note_is_favorite:      "#note_is_favorite",
           input_note_trash:            "#note_trash",
           input_note_share:            "#note_share",
           input_note_guid:             "#note_guid",
           input_note_type:             "#note_type",
           input_note_csrf_token:       "#note__csrf_token"
        };
        
        this._gui = false;
      
        this.sync = function (method, model, options) {
                var done = $.Deferred()
                , resp;
                
                switch (method) {
                    case 'auth':
                        
                    break;
                    case 'read':
                       if (model instanceof Backbone.Collection) {
                         resp = that.findAll(model, options); 
                       }
                       else
                       {
                         resp = that.find(model, options);
                       }
                    break;
                    case 'create':
                      resp = that.create(model, options);
                    break;
                    case 'update':
                      resp = that.update(model, options);
                    break;
                    case 'delete':
                       resp = that.destroy(model, options); 
                    break;
                }
                
                
                function callMethod (method, res) {
                    if (options && _.has(options, method)) {
                        options[method](res);
                    }
                }

                resp.then(function(res) {
                    callMethod('success', res);
                    callMethod('complete', res);
                    done.resolve(res);
                }, function(res) {
                    callMethod('error', res);
                    callMethod('complete', res);
                    done.reject(res);
                });
                
                //return Backbone.sync(method, model, options);
                return done;
            }
        
        //this.collectionCloud.sync = sync;
    }

    ArSyncNote.prototype = {
            
        S4: function () {
            /*jslint bitwise: true */
            return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
        },

        /**
        * Generate a pseudo-GUID by concatenating random hexadecimal.
        */
        guid: function () {
            return (this.S4()+this.S4()+'-'+this.S4()+'-'+this.S4()+'-'+this.S4()+'-'+this.S4()+this.S4()+this.S4());
        },
            
        _setFormData: function (modelData, isEnCrypt)
        {      
                var formOptions = this.formOptions;
                if (isEnCrypt)
                {
                    $(formOptions.input_note_pass).val(modelData.pass);
                }
                
                $(formOptions.input_note_title).val(modelData.title);
                $(formOptions.input_note_content).val(modelData.content);
                $(formOptions.input_note_data_image).val(modelData.dataImage);
                
                $(formOptions.input_note_task_all).val(modelData.taskAll);
                $(formOptions.input_note_task_complete).val(modelData.taskComplete);
                $(formOptions.input_note_is_favorite).val(modelData.isFavorite);
                $(formOptions.input_note_trash).val(modelData.trash);
                $(formOptions.input_note_share).val(modelData.share);
                $(formOptions.input_note_type).val(modelData.diagramType);
                $(formOptions.input_note_guid).val(modelData.guid);
            
          },

          destroy: function(model, options)
          {
                var d = $.Deferred() 
                , optionsClone = options? _(options).clone(): {error: false, success: false};
                
                var error = optionsClone.error;
                optionsClone.error = function(jqXHR, status, errorThrown) {
                         d.reject();
                        //aqui se pueden hacer cosillas con el error
                        if(error)
                            error(jqXHR, status, errorThrown);
                };
                      
                var success = optionsClone.success;
                optionsClone.success = function(data, status, xhr) {
                    data = model.parseData(data, arquematics.crypt); 
                    data = model.set(data).encrypt();
                    
                    if(success)
                    {
                        success(data, status, xhr);
                    }
                    d.resolve(data);
                };
               
               optionsClone.url = '/doc/note/' + model.get('id');
                
                
               var params = {
                 type: "DELETE",
                 datatype: 'json',
                 contentType: 'application/x-www-form-urlencoded;charset=UTF-8',
                 cache: false
               };
                
               $.ajax(_.extend(params, optionsClone));
               
               return d;
            },
            find: function(model, options) 
            {
               var d = $.Deferred()
               , optionsClone = options? _(options).clone(): {error: false, success: false};
                
               var error = optionsClone.error;
               optionsClone.error = function(jqXHR, status, errorThrown) {
                        d.reject();
                        //aqui se pueden hacer cosillas con el error
                        if(error)
                            error(jqXHR, status, errorThrown);
               };
                      
               var success = optionsClone.success;
               optionsClone.success = function(data, status, xhr) {
                   data = model.parseData(data, arquematics.crypt);
                   
                   data = model.set(data).encrypt();
                   
                   if(success)
                   {
                       success(data, status, xhr);
                   }
                   d.resolve(data);
               };
               
               optionsClone.url = '/doc/note/' + model.get('id');
               
               var params = {
                 type: 'GET',
                 datatype: 'json',
                 contentType: 'application/x-www-form-urlencoded;charset=UTF-8',
                 cache: false
               };
              
               $.ajax(_.extend(params, optionsClone));
               
               return d;
            },
          
            findAll: function(model, options) 
            {
               var d = $.Deferred()
               , optionsClone = options? _(options).clone(): {error: false, success: false};
                
               var error = optionsClone.error;
               optionsClone.error = function(jqXHR, status, errorThrown) {
                        d.reject();
                        //aqui se pueden hacer cosillas con el error
                        if(error)
                            error(jqXHR, status, errorThrown);
               };
                      
               var success = optionsClone.success;
               optionsClone.success = function(data, status, xhr) {
                            var items = []
                            if (data && data.contents && (data.contents.length > 0))
                            {
                                for (var i = 0, item; i < data.contents.length; i++)
                                {
                                    item = data.contents[i];
                                     
                                    items.push({
                                        id : item.guid,
                                        updated: Utils.parseDate(item.modified)
                                      });
                                }
                                
                                if(success)
                                {
                                    success(items, status, xhr);      
                                }
                                
                                d.resolve(items);
                            }
                            else
                            {
                             
                              if(success)
                              {
                                success(items, status, xhr);      
                              }
                              d.resolve(items);
                            }          
               };
               
               optionsClone.url = '/doc/note?page=' + model.state.currentPage;
               
               var params = {
                 type: 'GET',
                 datatype: 'json',
                 contentType: 'application/x-www-form-urlencoded;charset=UTF-8',
                 cache: false
               };
              
              $.ajax(_.extend(params, optionsClone)); 
              
              return d;
            },
            
            create: function (model)
            {
              var d = $.Deferred() 
              , optionsClone = model
               , $form = $(this.formOptions.form_notes)
              , $pass = $(this.formOptions.input_note_pass);
              
              this._setFormData(optionsClone, arquematics.crypt);
              if (arquematics.crypt)
              {
                 $.when(arquematics.utils.encryptForm($form, $pass))
                 .then(function (data){
                     d.resolve(data);
                  });   
              }
              else
              {
                 data = $form.find('input, select, textarea').serialize();
                 d.resolve(data);
              }
              
              return d;
            },
           
            update: function(model)
            {
                
                var d = $.Deferred()  
                , optionsClone = model
                , $form = $(this.formOptions.form_notes)
                , $pass = $(this.formOptions.input_note_pass);
                
              
              this._setFormData(optionsClone, arquematics.crypt);
                
              if (arquematics.crypt)
              {
                  $.when(arquematics.utils.encryptForm($form, $pass))
                 .then(function (data){
                     d.resolve(data);
                  });   
              }
              else
              {
                 data = $form.find('input, select, textarea').serialize();
                 d.resolve(data);   
              }
              
              return d;
            }
    };

    return (instance = (instance || new ArSyncNote()));
});

/*global define*/
define([
    'underscore',
    'jquery',
    'arquematics',
    'libs/utils',
], function (_, $, arquematics, Utils) {
    'use strict';
    
    var instance = null;

    function ArSyncVector() {
        
        var that = this;
        
        this.formOptions = {
           form: '#diagram-form',
           
           input_pass:                  '#diagram_pass',
           input_diagram_title:         '#diagram_title',
           input_diagram_json:          '#diagram_json',
           input_diagram_data_image:    '#diagram_data_image',
           input_diagram_type:          '#diagram_type',
           input_diagram_share:         '#diagram_share',
           input_diagram_is_favorite:   '#diagram_is_favorite',
           input_diagram_trash:         '#diagram_trash',
           input_diagram_csrf_token:    '#diagram__csrf_token'
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

    ArSyncVector.prototype = {
            
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
                    $(formOptions.input_pass).val(modelData.pass);
                }
                
                $(formOptions.input_diagram_title).val(modelData.title);    
                $(formOptions.input_diagram_json).val(modelData.json);
                $(formOptions.input_diagram_data_image).val(modelData.dataImage);
                $(formOptions.input_diagram_type).val(modelData.diagramType);
                $(formOptions.input_diagram_share).val(modelData.share);
                $(formOptions.input_diagram_is_favorite).val(modelData.isFavorite);
                $(formOptions.input_diagram_trash).val(modelData.trash);
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
                , $form = $(this.formOptions.form)
                , $pass = $(this.formOptions.input_pass);
                
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

    return (instance = (instance || new ArSyncVector()));
});

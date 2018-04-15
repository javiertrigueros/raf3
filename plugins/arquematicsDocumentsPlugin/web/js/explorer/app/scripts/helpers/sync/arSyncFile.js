/*global define*/
define([
    'underscore',
    'jquery',
], function (_, $) {
    'use strict';
    
    var instance = null;

    function ArSyncFile() {
        
        var that = this;
        
        this.formOptions = {
           form_file: '#file-form',
           form_file_update: '#file-form-update',
           
           input_id:               "#file_id",
           input_type:             "#file_type",
           input_src:              "#file_src",
           input_h:                "#file_h",
           input_w:                "#file_w",
           input_guid:             "#file_guid",
           input_csrf_token:       "#file__csrf_token",
           
           
           input_update_id:               "#file_update_id",
           input_update_type:             "#file_update_type",
           input_update_src:              "#file_update_src",
           input_update_h:                "#file_update_h",
           input_update_w:                "#file_update_w",
           input_update_guid:             "#file_update_guid",
           input_update_csrf_token:       "#file_update__csrf_token"
           
        };

    }

    ArSyncFile.prototype = {
            
        S4: function () {
           return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
        },

        /**
        * Generate a pseudo-GUID by concatenating random hexadecimal.
        */
        guid: function () {
                return (this.S4()+this.S4()+'-'+this.S4()+'-'+this.S4()+'-'+this.S4()+'-'+this.S4()+this.S4()+this.S4());
        },
            
        _setFormData: function (modelData)
        {
            var formOptions = this.formOptions;

            $(formOptions.input_type).val(modelData.type);
            $(formOptions.input_src).val(modelData.src);
            $(formOptions.input_h).val(modelData.h);
            $(formOptions.input_w).val(modelData.w);
            $(formOptions.input_guid).val((modelData.id || false)? modelData.id: this.guid());
        },
        
        _setFormUpdateData: function (modelData)
        {
            var formOptions = this.formOptions;

            $(formOptions.input_update_h).val(modelData.h);
            $(formOptions.input_update_w).val(modelData.w);
            $(formOptions.input_update_guid).val((modelData.id || false)? modelData.id: this.guid());
        },
        

        prepareFormData: function (model, method)
        {
              var $form;
              
              if (method === 'create')
              {
                 $form = $(this.formOptions.form_file);
                 this._setFormData(model);
              }
              else
              {
                 $form = $(this.formOptions.form_file_update);      
                 this._setFormUpdateData(model);
              }
              
              return $form.find('input, select, textarea').serialize();
        }
    };

    return (instance = (instance || new ArSyncFile()));
});

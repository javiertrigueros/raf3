/**
 * Arquematics 
 * 
 * Login en la pagina
 * 
 * @package: arquematicsPlugin
 * @version: 0.1
 * @author Javier Trigueros Martínez de los Huertos
 * 
 * Copyright (c) 2014
 * Licensed under the MIT license.
 * 
 */

/**
 * 
 * @param {jQuery} $
 * @param {arquematics} arquematics
 * 
 * @returns {arquematics}
 */

// Extended disable function
jQuery.fn.extend({
    disable: function(state) {
        return this.each(function() {
            var $this = $(this);
            if($this.is('input, button, textarea, select'))
              this.disabled = state;
            else
              $this.toggleClass('disabled', state);
        });
    }
});

(function($, arquematics, jSEncrypt) {
   
   arquematics.login = {
       options:
          {
          form_login: '#login-form',
          form_back: '#user-back-form',
          form_password_reset:  '#password-reset-forgot-form',
          
          content_password_reset_form: '#password-reset-form',
            
          private_key_modal: '#private-key-modal',
          waiting_modal:     '#waiting-modal',
            
          input_signin_username: '#signin_username',
          input_signin_password: '#signin_password',
          
          input_user_key_username: '#user_key_username',
          input_signin_private_key:    '#signin_private_key',
          input_signin_public_key: '#signin_public_key',
          input_signin_store_key: '#signin_store_key',
            
          cmd_send_login: '#cmd-send-login',
          cmd_private_key_send: '#cmd-private-key-send',
          cmd_private_key_send_extra: '.cmd-private-key-send-extra',
          cmd_private_key_cancel_extra: '#cmd-private-key-cancel-extra',
          cmd_private_key_cancel: '#cmd-private-key-cancel',
          
          cmd_forgot_password: '.cmd-forgot-password',
          cmd_close_password_reset: '.cmd-close-password-reset',
          
          cmd_password_reset: '.cmd-password-reset',
          
          content_hide: '.not-a-member',
          
          waitWithFullScreenModal: true,
          send_private_key: false
        },
                
       init: function (options)
       {
           options = options || {};
            
           this.options = $.extend(this.options, options);
           
           this.initHandlers();

           $(this.options.input_signin_private_key).autosize();
           
           $(this.options.input_signin_username).focus();
       },
       
       initHandlers: function ()
       {
           var options = this.options
           , that = this;

           $(options.form_login).on('submit', function(e) {
              e.preventDefault();
              
               //evita que vuelva a initStatus
              if (!arquematics.login.context.lock)
              {
                arquematics.login.context.start();      
              }
              
           });
           
           $(options.cmd_private_key_send_extra).on('click', function(e) {
              e.preventDefault();
              $(options.form_login).submit();
           });
           
           
           $(options.cmd_private_key_cancel +', '+ options.cmd_private_key_cancel_extra).on('click', function(e) {
               e.preventDefault();
               
               $(options.input_signin_private_key).val('');
               
               $(options.content_hide).show();
           });
           
           
           $(options.cmd_forgot_password).on('click', function(e)
           {
               e.preventDefault();
               
               $(options.form_login).hide();
              
               $(options.content_password_reset_form).show();
           });
           
           $(options.cmd_close_password_reset).on('click', function(e)
           {
               e.preventDefault();
               
               $(options.form_login).show();
              
               $(options.content_password_reset_form).hide();
           });
           
           $(options.cmd_password_reset).on('click', function(e)
           {
               e.preventDefault();
               
               that._sendPasswordReset();
           });
           
       },
       
       _sendPasswordReset: function ()
       {
           var options = this.options
           , that = this;
           
            var form = $(options.form_password_reset)
           , formData = form.find('input, select, textarea').serialize();
            
                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    datatype: "json",
                    data: formData,
                    cache: false})
               .done(function(dataJSON)
               {
                  
                   
               })
               .fail(function() {
                    
               });
       },
       /**
       * 
       * @param {boolean} buttonsEnable : true activa todos los botones
       */
       buttonsEnable: function (buttonsEnable)
       {
           if (buttonsEnable)
           {
              $(this.options.cmd_send_login).button('reset');
              $(this.options.cmd_private_key_send).button('reset');
              
              $(this.cmd_private_key_cancel_extra).disable(false);
              $(this.cmd_private_key_cancel).disable(false);
           }
           else
           {
              $(this.options.cmd_send_login).button('loading');
              $(this.options.cmd_private_key_send).button('loading');
              
              $(this.cmd_private_key_cancel_extra).disable(true);
              $(this.cmd_private_key_cancel).disable(true);
           }
       },
       
       notPrivateKey: function(username, token)
       {
          var data = arquematics.store.read(username + '-key')
          , ret = (data == null)
          , serverToken = token || false
          , localToken = arquematics.store.read(username + '-token-key');
          
          ret = ret || (serverToken != localToken);
          
          return ret;
       },
       
       checkPrivateKeyField: function(privateKey)
       {
           function isArray(value)
           {
                return Object.prototype.toString.call(value) === '[object Array]';
           }

           var str = $.trim(privateKey)
           , dataHexEncode =  str.toUpperCase()
                    .replace(/(\r\n|\n|\r)/gm,'')
                    .replace(/-/g,'')
                    .replace(/BEGIN KEY/,'')
                    .replace(/END KEY/,'')
                    .replace(/^[A-F0-9]$/gm,'')
                    .replace(/\s+/g,'')
            , plainData = arquematics.codec.HEX.toString(dataHexEncode)
            , keyStrArr = plainData.split("|");
    
            return (isArray(keyStrArr) && (keyStrArr.length >= 4));
       }
       
       
       
       
   };
   
   /**
    * Mirar patron State http://www.dofactory.com/javascript-state-pattern.aspx
    */
   arquematics.login.context = {
       currentState: false,
      
       lock: false,
       change: function (state) 
       {
         this.currentState = state;
         this.currentState.go();
       },
       start: function ()
       {
         this.lock = true;
         
         this.currentState = new arquematics.login.initStatus();
         this.currentState.go();  
       },
       
       startOAuth: function ()
       {
         this.lock = true;
         
         this.currentState = new arquematics.login.initStatusOAuth();
         this.currentState.go();  
       }
       
   };
    
   arquematics.login.initStatus = function ()
   {
       var options = arquematics.login.options;
       
       this.checkInputFields = function()
       {
           var userName = $.trim($(options.input_signin_username).val()),
               pass = $.trim($(options.input_signin_password).val());
           
           return ((userName.length >= 4) && (pass.length >= 4));
       };
        
       this.go = function ()
       {
            var $form_group = $(options.input_signin_username).parents('.form-group');
            $form_group.removeClass('has-error');
            
            if (!this.checkInputFields())
            {
                arquematics.login.context.change(new arquematics.login.errorInputFieldsStatus());        
            }
            else
            {
               arquematics.login.context.change(new arquematics.login.processUserOrEmailStatus());     
            }
       };
       
   };
   
   arquematics.login.processUserOrEmailStatus = function ()
   {
       var options = arquematics.login.options;

       this.go = function ()
       {
           $(options.private_key_modal).modal('hide');
           
           $(options.content_hide).hide();
           
           if (options.waitWithFullScreenModal)
           {
             $(options.waiting_modal).modal('show');      
           }
           else
           {
              $('body').addClass('loading');    
           }
           
           //copia el usuario
           $(options.input_user_key_username).val($(options.input_signin_username).val());
           
           var form = $(options.form_back)
           , formData = form.find('input, select, textarea').serialize();
            
                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    datatype: "json",
                    data: formData,
                    cache: false})
               .done(function(dataJSON)
               {
                   if ((dataJSON.status === 200) &&  dataJSON.values.hasPublicKey)
                   {
                       if (arquematics.login.notPrivateKey(dataJSON.values.username, dataJSON.values.token))
                       {
                          arquematics.login.context.change(new arquematics.login.noPrivateAndPublicKeysStatus());      
                       }
                       else
                       {
                         arquematics.login.context.change(new arquematics.login.sendLogRequestStatus());  
                       }
                   }
                   else if (dataJSON.status === 200) 
                   {
                      //genera y almacena una nueva clave
                      arquematics.login.context.change(new arquematics.login.generateKeysAndStoreStatus(dataJSON.values));     
                   }
                   else if (dataJSON.status === 500) 
                   {
                      arquematics.login.context.change(new arquematics.login.errorInputFieldsStatus);    
                   }
                   
                })
                .fail(function() {
                    
                   arquematics.login.context.change(new arquematics.login.errorInputFieldsStatus);   
                });
       };
   }
   
   arquematics.login.initStatusOAuth = function ()
   {
       var options = arquematics.login.options;
       
       this.go = function ()
       {
           $(options.private_key_modal).modal('hide');
           
           $(options.content_hide).hide();
           
           
           if (options.waitWithFullScreenModal)
           {
             $(options.waiting_modal).modal('show');      
           }
           else
           {
              $('body').addClass('loading');    
           }
           
           
           //copia el usuario
           $(options.input_user_key_username).val($(options.input_signin_username).val());
           
           var form = $(options.form_back)
           , formData = form.find('input, select, textarea').serialize();
            
                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    datatype: "json",
                    data: formData,
                    cache: false})
               .done(function(dataJSON)
               {
                   if ((dataJSON.status === 200) &&  dataJSON.values.hasPublicKey)
                   {
                       if (arquematics.login.notPrivateKey(dataJSON.values.username, dataJSON.values.token))
                       {
                          arquematics.login.context.change(new arquematics.login.inputKeysStatus());      
                       }
                       else
                       {
                           $(options.input_signin_private_key).val('');
                           $(options.input_signin_public_key).val('');
                           $(options.input_signin_store_key).val('');
                           //salida del script
                           window.location.href = dataJSON.values.url;   
                       }
                   }
                   else if (dataJSON.status === 200) 
                   {
                      //genera y almacena una nueva clave
                      arquematics.login.context.change(new arquematics.login.generateKeysAndStoreStatus(dataJSON.values));     
                   }
                   
                })
                .fail(function() {
                    
                   arquematics.login.context.change(new arquematics.login.errorInputFieldsStatus);   
                });
       };
       
   };
   
   arquematics.login.sendLogRequestStatus = function (keyEncoded) 
   {

     keyEncoded = keyEncoded ||  false;

     var options = arquematics.login.options;
     
     this.go = function ()
     {
          arquematics.login.buttonsEnable(false);
         
          var form = $(options.form_login)
          , formData = form.find('input, select, textarea').serialize();
          
          $(options.input_signin_private_key).val('');
          
          if (keyEncoded)
          {
            formData += '&signin[private_key]=' + JSON.stringify(keyEncoded);     
          }
         
          $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    datatype: "json",
                    data: formData,
                    cache: false})
               .done(function(dataJSON)
               {
                    $(options.input_signin_private_key).val('');
                    $(options.input_signin_public_key).val('');
                    $(options.input_signin_store_key).val('');
                    //salida del script
                    window.location.href = dataJSON.url;  
                })
               .fail(function() {
                   arquematics.login.context.change(new arquematics.login.errorInputFieldsStatus); 
                });
          
     };
   };
   
   arquematics.login.noPrivateAndPublicKeysStatus = function () 
   {
       
       var options = arquematics.login.options;
    
       this.go = function ()
       {
           arquematics.login.buttonsEnable(false);
                 
           $(options.private_key_modal).modal('hide');
           $(options.content_hide).hide();
           
           if (options.waitWithFullScreenModal)
           {
             $(options.waiting_modal).modal('show');      
           }
           else
           {
              $('body').addClass('loading');    
           }

           if (arquematics.login.checkPrivateKeyField($(options.input_signin_private_key).val()))
           {
               arquematics.login.context.change(new arquematics.login.storeInputKeysStatus());     
           }
           else 
           {
                var form = $(options.form_back);
                //copia el usuario
                $(options.input_user_key_username).val($(options.input_signin_username).val());
            
                var formData = form.find('input, select, textarea').serialize();
            
                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    datatype: "json",
                    data: formData,
                    cache: false,
                    success: function(dataJSON)
                    {
                        if (dataJSON.status === 200)
                        {
                            if (dataJSON.values.hasPublicKey)
                            {
                               arquematics.login.context.change(new arquematics.login.inputKeysStatus());          
                            }
                            else
                            {
                               arquematics.login.context.change(new arquematics.login.generateKeysAndStoreStatus(dataJSON.values));     
                            }
                        }
                        else 
                        {
                          arquematics.login.context.change(new arquematics.login.errorInputFieldsStatus); 
                        }
                    },
                    statusCode: {
                        404: function() {
                           arquematics.login.context.change(new arquematics.login.errorInputFieldsStatus); 
                        },
                        500: function() {
                           arquematics.login.context.change(new arquematics.login.errorInputFieldsStatus);  
                        }
                    },
                    error: function(dataJSON)
                    {
                       arquematics.login.context.change(new arquematics.login.errorInputFieldsStatus);  
                    }
                });
                  
           }
           
       };
       
       
   };
   
   arquematics.login.errorInputFieldsStatus = function () 
   {
       
       var options = arquematics.login.options;
       
       this.go = function ()
       {
           arquematics.login.buttonsEnable(true);
           
           $(options.private_key_modal).modal('hide');
           $(options.content_hide).show();
           
           if (options.waitWithFullScreenModal)
           {
             $(options.waiting_modal).modal('hide');      
           }
           else
           {
              $('body').removeClass('loading');    
           }
           
           arquematics.login.context.lock = false;
           
           var $form_group = $(options.input_signin_username).parents('.form-group');
           $form_group.addClass('has-error');
           
           $(options.input_signin_private_key).val('');
           $(options.input_signin_public_key).val('');
           $(options.input_signin_store_key).val('');
           
           $(options.input_signin_username).focus();
       };
       
   };
   
   arquematics.login.storeInputKeysStatus = function () 
   {
       var options = arquematics.login.options;
       
       this.go = function ()
       {
           arquematics.login.buttonsEnable(false);
                 
           if (arquematics.login.checkPrivateKeyField($(options.input_signin_private_key).val()))
           {
                var form = $(options.form_back);
                //copia el usuario
                $(options.input_user_key_username).val($(options.input_signin_username).val());
            
                var formData = form.find('input, select, textarea').serialize();
            
                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    datatype: "json",
                    data: formData,
                    cache: false,
                    success: function(dataJSON)
                    {
                        if (dataJSON.status === 200)
                        {
                            if (dataJSON.values.hasPublicKey)
                            {
                               arquematics.store.write(dataJSON.values.username + '-key', 1);
                               arquematics.store.write(dataJSON.values.username + '-token-key', dataJSON.values.token);
                               
                               arquematics.utils.storeKeyForUser(dataJSON.values.id + '-key', $.trim($(options.input_signin_private_key).val()));
                               
                               if (dataJSON.values.isAuthenticated)
                               {
                                  //salida del script
                                  window.location.href = dataJSON.values.url;       
                               }
                               else
                               {
                                  arquematics.login.context.change(new arquematics.login.sendLogRequestStatus);     
                               }   
                            }
                            else
                            {
                               arquematics.login.context.change(new arquematics.login.errorInputFieldsStatus);     
                            }
                        }
                        else 
                        {
                          arquematics.login.context.change(new arquematics.login.errorInputFieldsStatus); 
                        }
                    },
                    statusCode: {
                        404: function() {
                           arquematics.login.context.change(new arquematics.login.errorInputFieldsStatus); 
                        },
                        500: function() {
                           arquematics.login.context.change(new arquematics.login.errorInputFieldsStatus);  
                        }
                    },
                    error: function(dataJSON)
                    {
                       arquematics.login.context.change(new arquematics.login.errorInputFieldsStatus);  
                    }
                });
                   
           }
           else
           {
              arquematics.login.context.change(new arquematics.login.errorInputFieldsStatus);       
           }
       };
   };
   
   arquematics.login.inputKeysStatus = function () 
   {
       var options = arquematics.login.options;
       
       this.go = function ()
       { 
          arquematics.login.buttonsEnable(true);
          
          arquematics.login.context.lock= false;
           
          $(options.private_key_modal).val('');
          
          $(options.private_key_modal).modal('show');
          
          if (options.waitWithFullScreenModal)
          {
             $(options.waiting_modal).modal('hide');      
           }
           else
           {
              $('body').removeClass('loading');    
           }
          
          setTimeout($.proxy(function() {
            $(options.input_signin_private_key).focus();
          }, this), 500);    
          
       };
       
   };
   
   arquematics.login.generateKeysAndStoreStatus = function (userObj) 
   {
       var options = arquematics.login.options;

       this.go = function ()
       {
          var ecc = new arquematics.ecc()
            , storeKey = arquematics.utils.randomKeyString(50)
            , mailEncrypt
            , keyEncoded = [];
          
          ecc.generate();
          
          $(options.input_signin_public_key).val( ecc.getPublicKey());
          
          $(options.input_signin_store_key).val(storeKey);
          
          arquematics.store.write(userObj.username + '-key', 1);
          arquematics.store.write(userObj.username + '-token-key', userObj.token);
          arquematics.utils.store(userObj.id + '-key', ecc, storeKey);
          
          if (options.send_private_key)
          {
            jSEncrypt.setPublicKey(arquematics.codec.Base64.toString(userObj.publicMailKey));
                    
            mailEncrypt = arquematics.codec.HEX.encode(ecc.getData());
          
            mailEncrypt = arquematics.utils.wordwrap(mailEncrypt);
          
            for (var i = 0, count = mailEncrypt.length; i < count; i++)
            {
              //:TODO
              //jSEncrypt.encrypt codifica a base64? o algo asi
              //ni idea, lo arreglo en el back con base64_decode(base64_decode($data->data))
              //mirar en sfGuardUserProfile->checkPrivateKey 
              keyEncoded.push({ index: i, data:  arquematics.codec.Base64.encode(jSEncrypt.encrypt(mailEncrypt[i]))});
            }
          
            arquematics.login.context.change(new arquematics.login.sendLogRequestStatus(keyEncoded));        
          }
          else
          {
            arquematics.login.context.change(new arquematics.login.sendLogRequestStatus());              
          }
       };
       
   };
   
    
}(jQuery, arquematics, new JSEncrypt()));
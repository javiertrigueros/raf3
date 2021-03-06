/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 * dependencias con:
 */

/**
 * 
 * @param {jQuery} $
 * @param {arquematics} arquematics
 * @returns 
 */



$.widget( "arquematics.fieldpass", {
	options: {
            passLenght: 8,
            //cualquier texto
            regex:                                  '^(.*)+$',
            hideTextControl:                        true,
            resetInput:                             false,
            //cierra el control cuando envia con exito
            sendClose:                            true,
            //url para enviar los datos
            sendURL:                              false,
            //campos a enviar si esta vacio envia los campos del formulario
            fieldsToSend:                          [],
            //formulario que se va a enviar
            form:                                  false,     
            
            resetControl: function(e, that) 
            {
              var $btn = $(e.currentTarget);
              $btn.button('reset');
              
              if (that.options.resetInput)
              {
                this.$uiControlInputPass.val('');
                this.$uiControlInputPassAgain.val('');       
              }
              
              this.$uiControlInputPass.focus();       
            },
            
            resetControlError: function(e, that) 
            {
              var $btn = $(e.currentTarget);
              $btn.button('reset');
            }
	},
        
        _create: function() {
            this._loadDOM();
            this._initEventHandlers();
	},
        
        _initEventHandlers: function () {
            var elem = this.element
            , that = this
            , $controlInput = this.$uiControlInput;
            
            $controlInput.on('keypress paste', that, function(e){
                var $inputControl = $(this)
                , ret = that._filter(e, $(this));

                setTimeout(function(){
                    that._infoStrongText($inputControl);
                }, 2);
                
                return ret;
            });
            
            this.$uiControlText.on("click", function (e) 
            {
                e.stopPropagation();
                that.open();
            });
            
            $(elem).find('.cancel').on("click", function (e) 
            {
                e.stopPropagation();
                that.close();
            });
            
            $(elem).find('.send').on("click", function (e) 
            {
                var $btn = $(e.currentTarget)
                 , okRegex =         new RegExp("(?=.{" + that.options.passLenght + ",}).*", "g")
                 , isValidPass = ((that.$uiControlInputPassAgain.val() == that.$uiControlInputPass.val()) && okRegex.test(that.$uiControlInputPassAgain.val()));
                
                e.stopPropagation();
                
                if (isValidPass)
                {
                  $btn.button('loading');
                
                  that.sendContent(e);      
                }
            });
            
        },
        
        _infoErrorMatch: function()
        {
            var $groupControlAgain = this.$uiControlInputPassAgain.parent()
            , $groupControlPass = this.$uiControlInputPass.parent()
            , $helpBlock =  $groupControlAgain.find('.help-block')
            , isValidPass =   this.$uiControlInputPassAgain.val() == this.$uiControlInputPass.val();    
            
            if (!isValidPass)
            {
              if (!$groupControlAgain.hasClass('has-error'))
              {
                $groupControlAgain.addClass('has-error');      
              }
              $groupControlAgain.removeClass('has-warning');
              $groupControlAgain.removeClass('has-success');
              
              $helpBlock.text(this.options.txtErrorMatch);
            }
            else
            {
              $groupControlAgain.removeClass('has-error');
              $groupControlAgain.removeClass('has-warning');
              $groupControlAgain.removeClass('has-success'); 
              
              if ($groupControlPass.hasClass('has-error'))
              {
                $groupControlAgain.addClass('has-error');     
              }
              
              if ($groupControlPass.hasClass('has-warning'))
              {
                 $groupControlAgain.addClass('has-warning');     
              }
              
              if ($groupControlPass.hasClass('has-success'))
              {
                 $groupControlAgain.addClass('has-success');     
              }
              
              $helpBlock.text('');   
            }
        },
        
        _infoStrongText: function($inputTextControl)
        {
            
            var strongRegex =   new RegExp("^(?=.{" + this.options.passLenght + ",})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g")
            , mediumRegex =     new RegExp("^(?=.{" + this.options.passLenght + ",})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g")
            , okRegex =         new RegExp("(?=.{" + this.options.passLenght + ",}).*", "g")
            , hasNoNum =        new RegExp("^(?=.{" + this.options.passLenght + ",})(?=.*[A-Z])(?=.*[a-z])(?=.*\\W).*$", "g")
            , hasNoMay =        new RegExp("^(?=.{" + this.options.passLenght + ",})(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g")
            , hasNoMin =        new RegExp("^(?=.{" + this.options.passLenght + ",})(?=.*[A-Z])(?=.*[0-9])(?=.*\\W).*$", "g")
            , hasNoChars =      new RegExp("^(?=.{" + this.options.passLenght + ",})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).*$", "g")
            , $groupControl = $inputTextControl.parent()
            , $helpBlock =  $groupControl.find('.help-block')
            , options = this.options;
     
            if (strongRegex.test($inputTextControl.val())) 
            {
              if (!$groupControl.hasClass('has-success'))
              {
                $groupControl.addClass('has-success');      
              }
              $groupControl.removeClass('has-warning');
              $groupControl.removeClass('has-error');
              
              $helpBlock.text(options.txtStrong);

                   
            }
            else if (mediumRegex.test($inputTextControl.val()))
            {
              if (!$groupControl.hasClass('has-success'))
              {
                $groupControl.addClass('has-success');      
              }
              
              if (hasNoNum.test($inputTextControl.val()))
              { //le faltan los caracteres especiales
                $helpBlock.text(options.txtMediumNumbers);
              }
              else if (hasNoMay.test($inputTextControl.val()))
              {
                  $helpBlock.text(options.txtMediumCapital);
              }
              else if (hasNoMin.test($inputTextControl.val()))
              {     
                 $helpBlock.text(options.txtMediumLowercase);
              }
              else if (hasNoChars.test($inputTextControl.val()))
              {
                 $helpBlock.text(options.txtMediumSpecial);
              }
              else
              {
                  $helpBlock.text(options.txtMedium);
              }

              $groupControl.removeClass('has-warning');
              $groupControl.removeClass('has-error');         
            }
            else if (okRegex.test($inputTextControl.val()))
            {
              if (!$groupControl.hasClass('has-warning'))
              {
                $groupControl.addClass('has-warning');      
              }
              
              $helpBlock.text(options.txtWeak);
              
              $groupControl.removeClass('has-success');
              $groupControl.removeClass('has-error');    
            }
            else 
            {
              if (!$groupControl.hasClass('has-error'))
              {
                $groupControl.addClass('has-error');       
              }
              
              $helpBlock.text(options.txtHasError);
              
              $groupControl.removeClass('has-success');
              $groupControl.removeClass('has-warning');
            }
            
            this._infoErrorMatch();
            
        },
      
        _filter: function(event, $inputTextControl) {
            var that = this;   
            var controlInputString = '';
            var regex = new RegExp(this.options.regex);
           
            if (event.type==='keypress') 
            {
              var key = event.charCode ? event.charCode : event.keyCode ? event.keyCode : 0;

               //  8 = backspace, 9 = tab, 13 = enter, 35 = end, 36 = home, 37 = left, 39 = right, 46 = delete & .
              if (  key === 8 || key === 9 || key === 13 || key === 35 || key === 36|| key === 37 || key === 39 || key === 46) 
              {
                 //mirar 35 = #, 39 = ', 37 = %, 36 = $, 
                if (event.charCode === 0 && event.keyCode === key) {
                  return true;                                             
                }
              }
              
              controlInputString = $inputTextControl.val() + String.fromCharCode(key);
            } 
            else if (event.type === 'paste') 
            {
              $inputTextControl.data('value_before_paste', $inputTextControl.val());
              setTimeout(function(){
                that._filter({type:'after_paste'}, $inputTextControl);
              }, 1);
              return true;
            } 
            else if (event.type === 'after_paste') 
            {
              controlInputString = $inputTextControl.val();
            } 
            else 
            {
              return false;
            }
           
            if (regex.test(controlInputString)) {
              return true;
            }
            
            if (event.type === 'after_paste')
            {
                $inputTextControl.val($inputTextControl.data('value_before_paste'));    
            }
            return false;
         },
         
         open: function (e)
         {
            if (this.options.hideTextControl)
            {
                this.$uiControlText.hide();  
            }
            else
            {
                this.$uiControlText
                    .removeClass('hide')
                    .show();   
            }
            
            this.$uiControlTextForm
                .removeClass('hide')
                .show();
                
            this.$uiControlInputPass.focus();
         },
         close: function (e)
         {
             this.$uiControlTextForm.hide();
             if (this.options.hideTextControl)
             {
                this.$uiControlText
                    .removeClass('hide')
                    .show();
             }
         },
        
         sendContent: function (e)
         {
            var that = this,
                options = this.options,
                form = (options.form === false)?this.$uiControlForm:$(options.form);
            
            var formData = form.find('input, select, textarea').serialize();

            $.ajax({
                type: "POST",
                url: (!that.options.sendURL)?form.attr('action'):that.options.sendURL,
                data: formData,
                cache: false})
            .done(function (){
                if (options.sendClose)
                {
                  that.close();
                }
                
                that._trigger('resetControl',e, that);
            })
            .fail(function (){
                
                that._trigger('resetControlError',e, that);
             });
            
        },
        _loadDOM: function()
        {
            var that = this;
            //div que se activa al pulsar uiControlText
            this.$uiControlTextForm = $(this.element).find('.ui-control-text-form');
            //div que activa el $uiControlTextForm 
            this.$uiControlText = $(this.element).find('.ui-control-text');
            //control de datos del formulario
            this.$uiControlInput = $(this.element).find('.ui-control-text-input');
            
            this.$uiControlInput.each(function() {
                var $node = $(this);
               
                if ($node.hasClass('ui-control-pass-again'))
                {
                   that.$uiControlInputPassAgain = $node;     
                }
                else
                {
                   that.$uiControlInputPass = $node;  
                }
            });
            
            //campo pass
            this.$uiControlInputPass = $(this.element).find('.ui-control-pass');
            //campo pass again
            this.$uiControlInputPassAgain = $(this.element).find('.ui-control-pass-again');
            
            //formulario que que se envia
            this.$uiControlForm = $(this.element).find('.ui-control-form');
  
        }
});

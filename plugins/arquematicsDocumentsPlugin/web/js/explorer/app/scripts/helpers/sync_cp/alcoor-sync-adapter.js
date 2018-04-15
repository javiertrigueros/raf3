/*global define*/
define([
    'underscore',
    'jquery',
    'constants',
    'sjcl',
    'arquematics'
], function (_, $, CONST, sjcl, arquematics, document, window) {
    'use strict';

    /**
     * alcoor adapter
     */
    var Adapter = function (model) {
        
        console.log('alcoor adapter');
        
        this.formOptions = {
           form_notes: '#note-form',
           
           input_note_id:               "#note_id",
           input_note_pass:             "#note_pass",
           input_note_title:            "#note_title",
           input_note_content:          "#note_content",
           input_note_task_all:         "#note_task_all",
           input_note_task_complete:    "#note_task_complete",
           input_note_notebook_id:      "#note_notebook_id",
           input_note_is_favorite:      "#note_is_favorite",
           input_note_trash:            "#note_trash",
           input_note_synchronized:     "#note_synchronized",
           input_note_guid:             "#note_guid",
           input_note_csrf_token:       "#note__csrf_token"
        };
        
        this.userInfo = require([
            'jquery',
            'arquematics',
            '/arquematicsPlugin/js/arquematics/arquematics.store.js',
            '/arquematicsPlugin/js/arquematics/arquematics.codec.js',
            '/arquematicsPlugin/js/arquematics/arquematics.utils.js',
            '/arquematicsPlugin/js/arquematics/arquematics.ecc.js',
        ], function ($, arquematics) {
             
             var d = $.Deferred();
             
             $.ajax({type: "GET",
                 url: '/doc/userinfo',
                 datatype: 'json',
                 cache: false})
                .done(function(userInfo){
                    
                    if (userInfo && userInfo.encrypt)
                    {
                        arquematics.initEncrypt(
                            userInfo.user.id,
                            userInfo.user.store_key); 
                        
                        arquematics.crypt.setPublicEncKeys(userInfo.public_keys);
                    }
                    
                    d.resolve(userInfo);
                })
                .fail(function(error){
                   d.reject(false);
                }); 
                
                return d;
        });
        
        /*
        var key = CONST.DROPBOX_KEY;
        
        this.dir = (model ? model.storeName : 'notes');

        if (typeof model === 'object' && model.database.id !== 'notes-db') {
            this.dir = model.database.id + '/' + this.dir;
        }

        if (window.dropboxKey !== '') {
            key = window.dropboxKey;
        }*/
        
        /*
        this.client = new Dropbox.Client({
            key    : key
            // secret : constants.DROPBOX_SECRET
        });

        // Auth settings
        this.client.authDriver(new Dropbox.AuthDriver.Popup({
            receiverUrl: CONST.URL + 'dropbox.html',
            rememberUser: true
        }));*/

    };

    _.extend(Adapter.prototype, {

        /*
        auth: function (interactive) {
            var d = $.Deferred(),
                self = this;

            interactive = interactive || false;

            this.client.authenticate({ interactive: interactive }, function () {

                if ( self.client.isAuthenticated() ) {
                    d.resolve(true);
                }
                else if (interactive === false) {
                    $.when(self.auth(true)).then(function () {
                        d.resolve(true);
                    }, function () {
                        d.reject();
                    });
                }
                else {
                    d.reject();
                }

            });

            return d;
        },

        checkAuth: function () {
            this.client.authenticate({ interactive: false });
            return this.client.isAuthenticated();
        },*/

        sync: function (method, model, options) {
            var resp;
            console.log('sync method');
            console.log(method);
            
            console.log('storeName');
            console.log(model.storeName);
            
            
            if (model && model.id !== undefined)
            {
              console.log(model.id);      
            }
            else
            {
              console.log('sin modelo');         
            }
            
            switch (method) {
             case 'auth':
                resp = this.auth(model, options);
                break;
            case 'read':
                resp = ((model === undefined) || (model.id === undefined)) ? this.findAll(model.storeName) : this.find(model, options) ;
                break;
            case 'create':
                resp = this.create(model, options);
                break;
            case 'update':
                resp = this.update(model, options);
                break;
            case 'delete':
                resp = this.destroy(model, options);
                break;
            }

            return resp;
        },
        auth: function (model, options) {
            var d = $.Deferred();
            
            $.post(
                '/doc/auth'
            )
            .done(function(p){
                d.resolve(true);
            })
            .fail(function(p){
                d.reject(false);
            });
            
            return d;  
        },
        _setFormData: function (model, isEnCrypt)
        {
            var formOptions = this.formOptions;
            
            if (isEnCrypt)
            {
               $(formOptions.input_note_pass).val(model.get('pass'));
               
               $(formOptions.input_note_title).val(arquematics.simpleCrypt.encryptHex(model.get('pass') , model.get('title')));
               $(formOptions.input_note_content).val(arquematics.simpleCrypt.encryptHex(model.get('pass') , model.get('content')));     
            }
            else
            {
               $(formOptions.input_note_title).val(model.get('title'));
               $(formOptions.input_note_content).val(model.get('content')); 
            }
            
            $(formOptions.input_note_task_all).val(model.get('taskAll'));
            $(formOptions.input_note_task_complete).val(model.get('taskComplete'));
            $(formOptions.input_note_notebook_id).val(model.get('notebookId'));
            $(formOptions.input_note_is_favorite).val(model.get('isFavorite'));
            $(formOptions.input_note_trash).val(model.get('trash'));
            $(formOptions.input_note_synchronized).val(model.get('synchronized'));
            $(formOptions.input_note_guid).val(model.get('id'));
            
        },
        _parseData: function (data, isEnCrypt)
        {
             if (arquematics.crypt)
             {
                data.pass = arquematics.crypt.decryptHexToString(data.pass);

                data.title = arquematics.simpleCrypt.decryptHex(data.pass,data.title);
                data.content = arquematics.simpleCrypt.decryptHex(data.pass, data.content);
             }   
             
             data.created = new Date(parseInt(data.created)).getTime();
             data.updated = new Date(parseInt(data.updated)).getTime(); 
        },
        /**
         * Add a new model
         */
        create: function (model, options) {
            var d = $.Deferred()
            , formOptions = this.formOptions
            , $form = $(this.formOptions.form_notes)
            , that = this;
            
            if ( !model.id) {
                //genera la nueva id
                model.set('id', this.guid());
            }
            console.log('create');
            console.log(model.storeName);
            if (model.storeName === 'notes')
            {
               console.log('entra en notes');
               if (arquematics.crypt)
               {
                    model.set('pass', arquematics.utils.randomKeyString(50));
               }
            
               this._setFormData(model, arquematics.crypt);
            
              if (arquematics.crypt)
              {
                console.log('arquematics');
                console.log('encryptFormAndSendDeferred');
                
                var callBack = function(formData)
                {
                   $.ajax({type: "POST",
                    url: $form.attr('action'),
                    datatype: 'json',
                    data: formData,
                    cache: false})
                        .done(function(data){
                            that._parseData(data, arquematics.crypt);
                            d.resolve(data);
                        })
                        .fail(function(p){
                            d.reject(false)
                        });  
                }
                
                arquematics.utils.encryptFormAndSend( $form, callBack, $(formOptions.input_note_pass));      
              }
              else
              {
                $.ajax({type: "POST",
                    // POST a '/doc/notes',
                    url:  $form.attr('action'),
                    datatype: 'json',
                    data: $form.find('input, select, textarea').serialize(),
                    cache: false})
                    .done(function(data){
                        that._parseData(data, arquematics.crypt);
                        d.resolve(data);
                    })
                    .fail(function(p){
                        d.reject(false)
                    });     
              }     
            }
            else
            {
               $.ajax({type: "POST",
                    // POST a '/doc/notes',
                    url: 'doc/null',
                    datatype: 'json',
                    data: 'go=yes' +  model.get('id'),
                    cache: false})
                    .done(function(data){
                        d.resolve(data);
                    })
                    .fail(function(p){
                        d.reject(false)
                    });      
            }
            
            
            return d;  
        },

        /**
         * Update a model 
         */
        update: function (model, options) {
            var d = $.Deferred()
            , formOptions = this.formOptions
            , $form = $(this.formOptions.form_notes);
            
            console.log('update');
            console.log(model.storeName);
            if (model.storeName === 'notes')
            {
               if (arquematics.crypt)
               {
                    console.log(model.get('pass'));
                    d.resolve(true);
               } 
            }
            else
            {
                $.ajax({type: "POST",
                    // POST a '/doc/notes',
                    url: 'doc/viewnull/' + model.id,
                    datatype: 'json',
                    data: 'go=yes',
                    cache: false})
                    .done(function(data){
                        d.resolve(data);
                    })
                    .fail(function(p){
                        d.reject(false)
                    });     
            }
            
            return d;  
            //return this.writeFile(model, options);
        },

        /**
         * Delete a model from Dropbox
         */
        destroy: function (model) {
            
            if ( !model.id) {
                return;
            }
            
            var d = $.Deferred();
            
            console.log('destroy');
            console.log(model.storeName);
            
            if (model.storeName === 'notes')
            {
                $.ajax({
                    url: '/doc/notes/' + model.id,
                    datatype: 'json',
                    cache: false,
                    type: 'DELETE'
                })
                .done(function(data){
                        d.resolve(data);
                })
                .fail(function(p){
                     d.reject(p)
                });    
            }
            else
            {
                 $.ajax({type: "POST",
                    // POST a '/doc/notes',
                    url: 'doc/viewnull/' + model.id,
                    datatype: 'json',
                    data: 'go=yes',
                    cache: false})
                    .done(function(data){
                        d.resolve(data);
                    })
                    .fail(function(p){
                        d.reject(false)
                    });   
             }

            return d;
           
        },

        /**
         * Retrieve a model
         */
        find: function (model) {
            var d = $.Deferred()
            , that = this;
            
            console.log('find');
            console.log(model.storeName);
            if (model.storeName === 'notes')
            {
                $.ajax({
                    url: '/doc/notes/' + model.id,
                    datatype: 'json',
                    type: "GET"
                })
                .done(function(data){
                    
                    that._parseData(data, arquematics.crypt);
                    d.resolve(data);
                 })
                .fail(function(error){
                        d.reject(error)
                });     
            }
            else
            {
                $.ajax({
                    url: '/doc/notes/' + model.id,
                    datatype: 'json',
                    type: "GET"
                })
                 .done(function(data){
                        d.resolve(data);
                  })
                 .fail(function(error){
                        d.reject(error)
                    });      
            }
           
            return d;
            
            /*
            var d = $.Deferred();

            this.client.readFile(
                this.dir + '/' + model.get('id') + '.json',
                function (error, data) {
                    if (error) {
                        d.reject(error);
                    } else {
                        d.resolve(JSON.parse(data));
                    }
                    return true;
                }
            );

            return d;*/
        },

        /**
         * Collection of files - no content, just id and modified time
         */
        findAll: function (modelType) {
            
            var d = $.Deferred()
            ,   items = []
            ,   data
            ,   id;
            
            console.log('findAll');
            console.log(modelType);
            
            if (modelType === 'notes')
            {
              $.ajax({
                url: '/doc/notes',
                datatype: 'json',
                type: "GET"
              })
              .done(function( data ) {
                console.log('findAll resolve');
                console.log(data.contents.length > 0);
                console.log(data.contents);
                if (data && data.contents && (data.contents.length > 0))
                {
                   for (var i = 0, item; i < data.contents.length; i++) {
                       item = data.contents[i];
                       console.log(item);
                       items.push({
                            id : item.guid,
                            updated: new Date(parseInt(item.modified)).getTime()
                       });
                   }
                }
                console.log('resolve items');
                console.log(items);
                d.resolve(items);
              })
              .fail(function(error) {
                d.reject(error);
              });  
            }
            else
            {
                $.ajax({type: "POST",
                    // POST a '/doc/notes',
                    url: 'doc/null/',
                    datatype: 'json',
                    data: 'go=yes',
                    cache: false})
                    .done(function(data){
                        d.resolve(data);
                    })
                    .fail(function(p){
                        d.reject(false)
                });         
            }
            
            
            return d;  
            
        },

        /**
         * Write model's content to file
         */
        writeFile: function (model) {
            /*
            var d = $.Deferred();
            if ( !model.id) {
                return;
            }

            this.client.writeFile(
                this.dir + '/' + model.id + '.json',
                JSON.stringify(model),
                function (error) {
                    if (error) {
                        d.reject(error);
                    } else {
                        d.resolve(model);
                    }
                    return true;
                }
            );

            return d;*/
        },

        S4: function () {
            /*jslint bitwise: true */
            return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
        },

        /**
         * Generate a pseudo-GUID by concatenating random hexadecimal.
         */
        guid: function () {
            return (this.S4()+this.S4()+'-'+this.S4()+'-'+this.S4()+'-'+this.S4()+'-'+this.S4()+this.S4()+this.S4());
        }

    });

    return function (method, model, options) {
        var adapter = new Adapter(model),
            done = $.Deferred(),
            args = arguments,
            resp;

        
        if (method === 'auth') {
            /*
            // No popup === true
            if (arguments[1] === true) {
                return adapter.checkAuth();
            }
            else {
                
            }*/
            
            return adapter.auth();
        }
        else {
            /*
            $.when(adapter.auth()).then(function () {
                console.log('args');
                console.log(args);
                resp = adapter.sync.apply(adapter, args);
            });*/
            
            resp = adapter.sync.apply(adapter, args);
        }
        
        //resp = adapter.sync.apply(adapter, args);

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

        return done;

    };

});

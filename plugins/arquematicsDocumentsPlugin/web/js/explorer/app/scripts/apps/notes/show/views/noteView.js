/*global define*/
/*global Markdown*/
define([
    'underscore',
    'app',
    'marionette',
    'helpers/uri',
    'text!apps/notes/show/templates/noteView.html',
    'checklist',
    //'tags',
    //'libs/images',
    //'prettify',
    'helpers/mathjax',
    //'hammerjs',
    'libs/utils',
    'd3',
    'nvd3',
    'blobUtil',
    'arquematics',
    'marked',
    'backbone.mousetrap'
    //'pagedown-extra',
], function (_, App, Marionette, URI, Template, Checklist, /*Tags, Img, /*prettify,*/ mathjax, /*Hammer,*/ Utils, d3, nv, blobUtil, arquematics, marked ) {
    'use strict';

    var View = Marionette.ItemView.extend({
        template: _.template(Template),

        className: 'content-notes',

        ui: {
            header: 'header',
            favorite : '.favorite i',
            progress : '.progress-bar',
            percent  : '.progress-percent'
        },

        events: {
            'click .favorite'               : 'favorite',
            'click .task [type="checkbox"]' : 'toggleTask'
        },

        keyboardEvents: {
            'up'   : 'scrollTop',
            'down' : 'scrollDown'
        },

        initialize: function(options) {
            this.files = options.files;
            
            this.clickEnabled = true;

            // Setting shortcuts
            var configs = App.settings;
            this.keyboardEvents[configs.actionsRotateStar] = 'favorite';

            // Model events
            this.listenTo(this.model, 'change:isFavorite', this.changeFavorite);
            this.listenTo(this.model, 'change:taskCompleted', this.taskProgress, this);

        },

        onDomRefresh: function () {
            
            var URL = window.URL || window.webkitURL
            , diagramType =  this.model.get('diagramType')
            , $this = $(this.el);
            
            function resizeimg($image, w, h, maxW, maxH)
            {
                $image.width(w + '%');
                $image.height('auto');
                
            }
            console.log('diagramType');
            console.log(diagramType);
            
            if (diagramType === 'note')
            {
               if (this.files && (this.files.length > 0))
               {
                for (var i = 0; i < this.files.length; ++i) {
                    var imageModel = this.files.at(i)
                    , image;
                    
                    if (typeof imageModel.get('id') != 'undefined')
                    {
                        image = this.$('img[alt="' + imageModel.get('id') + '"]').get(0);
                        if ((image) && (image.nodeName.toLowerCase() === 'img'))
                        {
                            image.onload = function() {
                                
                                var h = window.innerHeight
                                        || document.documentElement.clientHeight
                                        || document.body.clientHeight
                                , w = window.innerWidth
                                        || document.documentElement.clientWidth
                                        || document.body.clientWidth;
                                    
                                resizeimg($(this), imageModel.get('w'), imageModel.get('h') , w, h); 
                            };
                            
                            image.src = URL.createObjectURL(imageModel.get('src'));       
                        }
                    }
                }
               }
               // MathJax
               mathjax.init(this.el); 
            }
            else if (diagramType === 'rawchart')
            {
                var content = JSON.parse(this.model.get('content'))
                , doc = arquematics.document.createDocument(content.params.chartType);
                
                doc.show(content, $this.find('#rawchart-content') , App.settings.appLang, true);
            }
            else if ((diagramType === 'bpmn' ) || (diagramType === 'wireframe' ) 
                    || (diagramType === 'uml' ) || (diagramType === 'umlsequence' ) 
                    || (diagramType === 'umlusecase' )  || (diagramType === 'epc' ))
            {
                var imageURL = this.model.get('dataImage')
                , img = new Image
                , $imageContent = $this.find('#image-content')
                ;
                /*
                blobUtil.dataURLToBlob(imageURL)
                .then(function (blob){
                    
                    $imageContent.append(img);
                    $imageContent.removeClass('hide').show();
                    
                    img.src = URL.createObjectURL(blob);    
                }); */
                
                img.onload = function()
                {
                    $imageContent.append(img);
                    $imageContent.removeClass('hide').show();
                };
                
                if (arquematics.codec.Base64.isBase(imageURL))
                {
                  img.src = imageURL;
                }

                /*
                img.src = imageURL;
                $imageContent.append(img);
                $imageContent.removeClass('hide').show();*/
            }
            else
            {
                var imageURL = this.model.get('dataImage')
                , data = arquematics.codec.decodeURIData(imageURL)
                , $imageContent = $this.find('#image-content')
                , parser = new DOMParser()
                , doc = parser.parseFromString(data, 'image/svg+xml');

                $imageContent.append(doc.documentElement);
                $imageContent.removeClass('hide').show();
            }
            
            App.trigger('doc:loaded');
            
            //hace scroll all principio de la pagina
            $("html, body").animate({ scrollTop: 0 }, 20);
        },
        
        onClose: function () {
            
        },
       
        /**
         * Decrypt content and title
         */
        serializeData: function () {
            //var data = _.extend(this.model.toJSON(), this.options.decrypted),
            var data = this.model.toJSON(),
                self = this;
                // Convert from markdown to HTML
                // converter = Markdown.getSanitizingConverter();
               // converter = new Markdown.Converter();
               // Markdown.Extra.init(converter);
            
            // data.title = $(converter.makeHtml(data.title)).text();
            // Show title
            document.title = data.title;
            
            /*
            if (data.diagramType === 'note')
            {
                // Customize markdown converter
                converter.hooks.chain('postNormalization', function (text) {
                    text = new Checklist().toHtml(text);
                    //text = new Tags().toHtml(text);
                    return self.imgHelper.toHtml(text, self.options.files);
                });
              
                data.content = converter.makeHtml(data.content);
            }*/
            
             //datos de usuario
            data.isAdmin = App.userInfo.cms_admin;
            //data.userMenu = App.userInfo.HTML;

            data.uri = URI.link('/');
            
            URI.setCurrentId(data.id);
            
            return data;
        },

        changeFavorite: function ()
        {
            
            var sidebar = $('#note-' + this.model.get('id') + ' .favorite');
            if (this.model.get('isFavorite') === 1) {
                this.ui.favorite.removeClass('fa-star-o');
                sidebar.removeClass('fa-star-o');
               
                this.ui.favorite.addClass('fa-star');
                sidebar.addClass('fa-star');
            } else {
                
                this.ui.favorite.removeClass('fa-star');
                sidebar.removeClass('fa-star');
                
                this.ui.favorite.addClass('fa-star-o');
                sidebar.addClass('fa-star-o');
            }
        },

        /**
         * Add note item to your favorite notes list
         */
        favorite: function (e) {
            e.preventDefault();
            
            if (this.clickEnabled)
            {
              this.model.trigger('setFavorite'); 
            }
            
            return false;
        },

        /**
         * Toggle task status
         */
        toggleTask: function (e) {
            
            if (this.clickEnabled)
            {
               var task = $(e.target),
                taskId = parseInt(task.attr('data-task'), null),
                //content = this.model.decrypt().content,
                content = this.model.content,
                text = new Checklist().toggle(content, taskId);

                // Save result
                this.model.trigger('updateTaskProgress', text);  
            }
        },

        /**
         * Shows percentage of completed tasks
         */
        taskProgress: function () {
            var percent = Math.floor(this.model.get('taskCompleted') * 100 / this.model.get('taskAll'));
            this.ui.progress.css({width: percent + '%'}, this.render, this);
            this.ui.percent.html(percent + '%');
        },

        /**
         * Scroll page to top when user hits up button
         */
        scrollTop: function () {
            var Top = this.$('.ui-body').scrollTop();
            this.$('.ui-body').scrollTop(Top - 50);
        },

        /**
         * Scroll page down when user hits down button
         */
        scrollDown: function () {
            var Top = this.$('.ui-body').scrollTop();
            this.$('.ui-body').scrollTop(Top + 50);
        },

        templateHelpers: function() {
            return {
                i18n: $.t,

                getProgress: function() {
                    return Math.floor(this.taskCompleted * 100 / this.taskAll);
                },

                getContent: function()
                {
                    if (this.diagramType === 'note')
                    {
                      return marked(this.content);       
                    }
                    else return '';
                },
                createdDate: function() {
                    return Utils.formatDate(this.created, $.t('DateFormat'));
                }
            };
        }

    });

    return View;
});

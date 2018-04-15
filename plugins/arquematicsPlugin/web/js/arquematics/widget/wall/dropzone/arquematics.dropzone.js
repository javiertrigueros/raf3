    /**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *  
 *  arquematics.dropzone
 *  ---------------------
 *                 
 *  depende de:
 *  - arquematics.mime.js
 *  
 */

/**
 * 
 * @param {type} $
 * @param {type} arquematics
 * @param {type} tmpl
 */
(function ($, arquematics, tmpl, blobUtil, window) {

var crypto;

if (window.crypto && window.crypto.getRandomValues) {
  crypto = window.crypto;
} else if (window.msCrypto && window.msCrypto.getRandomValues) {
  crypto = window.msCrypto;
}  

arquematics.dropzone =  {
	options: {
            extensions: [],
            culture: '',
            //tiene archivos validos en la sesion para enviar
            hasSessionFiles: false,
            //API de Cloud Convert
            cloud_convert_key: '',
             //archivos en la session
            sessionFiles: 0,
            // 0.25MB chunk sizes.
            BYTES_PER_CHUNK: 256 * 1024,

            //controles token fichero completo 
            input_control_csrf_token:         '#ar_drop_file__csrf_token',
            input_control_chunk_csrf_token:   '#ar_drop_file_chunk__csrf_token',
            //controles token de previsualizaciones
            input_control_preview_csrf_token:         '#ar_drop_file_preview__csrf_token',
            input_control_preview_chunk_csrf_token:   '#ar_drop_file_chunk_preview__csrf_token',
            
            //controles conversion
            input_control_cloud_convert:    '#cloud-convert-apikey',
            content:                        '#content',
            //formularios envio fichero completo
            
            form_drop:              '#arquematics-upload',
            form_chunk:             '#arquematics-upload-chunk',
            form_cloud_convert:     '#cloud-convert',
            //formularios envio previsualizaciones
            form_drop_preview:      '#arquematics-upload-preview',
            form_chunk_preview:     '#arquematics-upload-chunk-preview',
            
            content_wrapper:         '#content-wrapper,#main-menu,#main-menu-bg',

            content_item:            '.document-file-item',
            content_item_visor:      '.document-file-visor',
            
            content_container:       '.list-files',
            content_item_text:       '.file-text',
            
           
            content_files_preview:       '.list-files-preview',
            content_files_preview_nofiles: '.default-message-nofiles',
            content_files_preview_item:  '.dropzone-file',
            content_files_preview_image: '.dropzone-file-image',
            content_files_preview_name:  '.dropzone-file-name',
            
            content_files_cancel_all: '.dropzone-line-controls',
            
            template_file:               '#preview-file-drop-template',
            template_no_files:           '#nofiles-template',
                  
            cmd_cancel:                  '.cmd-remove-file',
            cmd_cancel_all:              '.cmd-remove-drop',
            
            tool_focus:             '#dropzone',
            tool_handler:           '#arDrop',
            tool_container:         '#dropzone',
            has_content:            false,
            show_tool:              true
	},
        
        //resetea el contenido del control y lo activa para usar
        reset: function() 
        {
          $(this.options.content_files_preview).empty();
          $(this.options.tool_container).hide();
           
          this.dropzone.removeAllFiles(true);
          this.dropzone.emit("reset");
          //borra los elementos de sesion
          this.options.sessionFiles = 0;
        },
        resetError: function() 
        {
             
        },
            
        hasContent: function()
        {
          return (this.options.sessionFiles > 0) || this.options.hasSessionFiles;    
        },
        
        waitForContent: false,
        dropzone: false,
        mimeTypesAllowed: [],
         
        init: function (options)
        {
           this.options = $.extend({}, this.options, options);
           
           this.mimeTypesAllowed = arquematics.mime.getMimeTypesByExtensions(options.extensions);
           
           this._initControlHandlers();
           
           this._preparePreviewNodes();
           
           this._prepareContentNodes();
        },

        /**
         * inicializa controles estaticos
         */
        _initControlHandlers: function () 
        {
           var that = this,
               options = this.options;
               
               this.dropzone = new Dropzone(options.form_drop, {
                                            previewTemplate: document.querySelector(options.template_file).innerHTML,
                                            parallelUploads: 10,
                                            clickable: true,
                                            thumbnailHeight: 120,
                                            thumbnailWidth: 120,
                                            previewsContainer: options.hasSessionFiles? options.content_files_preview: null,
                                            //tamaño max
                                            maxFilesize: 10,
                                            maxFiles: 7,
                                            //accept: getImage,
                                            url: '/#notes',
                                            //uploadFiles: this.uploadFiles,
                                            filesizeBase: 1024,
                                            thumbnail: this.getThumbnail});
                                        
                                        
              this.dropzone.uploadFiles = this.uploadFiles;

              $(options.cmd_cancel_all).click(function (e)
              {
                  e.preventDefault();
                  
                  if ($(options.tool_container)
                          .find(options.content_files_preview_item)
                          .length === 0)
                 {
                    $(options.tool_container).hide(); 
                 }
              });
              
              $(options.tool_handler).click(function (e) 
              {
                e.preventDefault();
                // si el control esta oculto con la clase 
                // hide esta en su estado 
                // inicial sin archivos correctos subidos en la session
                if ($(options.tool_container).hasClass('hide'))
                {
                  $(options.tool_container).removeClass('hide');
                  $(options.tool_container).show();
                  
                }
                else if ($(options.tool_container)
                          .find(options.content_files_preview_item)
                          .length <= 0)
                {
                        // si no existen ficheros agrega el control
                        if ($(options.content_files_preview_nofiles).length === 0)
                        {
                           $(options.tool_container)
                            .find(options.content_files_preview).append(document.querySelector(options.template_no_files).innerHTML);     
                        }
                        
                        $(options.content_files_preview_nofiles).removeClass('hide');
                        $(options.content_files_preview_nofiles).show();
                        $(options.content_files_preview_nofiles).css('display','flex');   
                        
                        $(options.tool_container).show();
                }
                
                $(options.form_drop).click();
              });

              $('body').bind('changeScrollContent', function (e, $node)
              {
                  var $files = $node.find(options.content_item);
                  $files.each(function() {
                        that._prepareNode($(this));
                  });
              }); 
        },
        
        _preparePreviewNodes: function()
        {
            var options = this.options
            , that = this;
            
            if ($(options.content_files_preview_item).length > 0)
            {
              //ocultar el boton de ocultar todo el control
              $(options.content_files_cancel_all).hide();      
            }

            $(options.content_files_preview_item).each(function() {
                 that._preparePreviewNode($(this));
                 that._preparePreviewNodeHandlers($(this));
            });
        },
        
        _preparePreviewNode: function($node)
        {
            this._decodePreview($node);
            
            var $iconNode = $node.find("[data-dz-thumbnail]") 
            , icon = arquematics.mime.getInputIconByNameType($node.data('document-type'));
            if (icon)
            {
              $iconNode.parent().addClass(icon);
              
              $iconNode.remove();    
            }
        },
        
        _prepareContentNodes: function() 
        {
            var options = this.options
            , $files = $(options.content_item)
            , that = this;
           
           $files.each(function() {
                 that._prepareNode($(this));
           });
	},
        
        _decodePreview: function($node)
        {
            var options = this.options
            , that = this
            , pass = $node.data('content')
            , name = arquematics.simpleCrypt.decryptBase64(pass , $node.data('name'))
            , iconNodeImage = $node.find(options.content_files_preview_image)[0];
          
            $node.find(options.content_files_preview_name).text(name);
            
            if ($node.data('load-url') && arquematics.mime.isImageType($node.data('document-type')))
            {
              
              var arrayData = $node.data('src').split('|')
              , src = '';
               
              for (var i = 0; i < arrayData.length; i++)
              {
                src +=  arquematics.simpleCrypt.decryptBase64(pass ,arrayData[i]);     
              }
               
               
              $.when(arquematics.graphics.createThumbnailFromDataUrl(src, 
                                    'image/png', name,
                                    this.dropzone.options.thumbnailHeight,
                                    this.dropzone.options.thumbnailWidth))
               .done(function (thumbnailMedium, name, documentType){
                  $.when(arquematics.loader.getObjectURLFromDataURL(thumbnailMedium))
                  .done(function (blobURLOBJ){
                      iconNodeImage.src = blobURLOBJ;
                  }); 
               }); 
            }
            
        },
        
        _setIconForFile: function(file, fileType)
        {
            
            var $node = $(file.previewElement.querySelectorAll("[data-dz-thumbnail]"))
            , icon = arquematics.mime.getInputIconByNameType(fileType);

            if (icon)
            {
              $node.parent().addClass(icon);
              
              $node.remove();    
            }
        },
        
        _preparePreviewNodeHandlersError: function($node, file)
        {
            var options = this.options
            , that = this;
            
            $node.find(options.cmd_cancel).on("click",function(e)
            {
               e.preventDefault();
               e.stopPropagation();
               
               var $elem = $(e.currentTarget)
               , $parent = $elem.parents(options.content_files_preview_item);
               
               //animacion
               $parent.animate({'backgroundColor':'#fb6c6c'},300);
               
               $parent.remove();
                       //si no nenemos ningun fichero
                       // mostramos el texto por defecto para enviar nuevos ficheros
               if ($(options.tool_container)
                                .find(options.content_files_preview_item)
                                .length <= 0)
               {
                        // si no existe agrega el control
                        $(options.content_files_cancel_all).show(); 
                        
                        if ($(options.content_files_preview_nofiles).length === 0)
                        {
                           $(options.tool_container)
                            .find(options.content_files_preview).append(document.querySelector(options.template_no_files).innerHTML);     
                        }
                        
                        $(options.content_files_preview_nofiles).removeClass('hide');
                        $(options.content_files_preview_nofiles).show();
                        $(options.content_files_preview_nofiles).css('display','flex');    
               }
                       
               if (file)
               {
                    file.status = Dropzone.CANCELED;
                    that.dropzone.emit("canceled", file);
                    that.dropzone.processQueue();
               }
               
               return false;
            });
            
        },
        
        _preparePreviewNodeHandlers: function($node, file, worker)
        {
            file = file || false;
            worker = worker || false;
            
            var options = this.options
            , that = this;
            
            $node.find(options.cmd_cancel).on("click",function(e)
            {
               e.preventDefault();
               e.stopPropagation();
               
               var $elem = $(e.currentTarget)
               , $parent = $elem.parents(options.content_files_preview_item);
               
               //animacion
               $parent.animate({'backgroundColor':'#fb6c6c'},300);
               
               $.when($.ajax({
                        type: "DELETE",
                        url: $elem.data('url'),
                        datatype: "json",
                        cache: false}))
                    .done(function (){
                      $parent.remove();
                       //si no nenemos ningun fichero
                       // mostramos el texto por defecto para enviar nuevos ficheros
                      if ($(options.tool_container)
                                .find(options.content_files_preview_item)
                                .length <= 0)
                      {
                        // si no existe agrega el control
                        $(options.content_files_cancel_all).show(); 
                        
                        if ($(options.content_files_preview_nofiles).length === 0)
                        {
                           $(options.tool_container)
                            .find(options.content_files_preview).append(document.querySelector(options.template_no_files).innerHTML);     
                        }
                        
                        $(options.content_files_preview_nofiles).removeClass('hide');
                        $(options.content_files_preview_nofiles).show();
                        $(options.content_files_preview_nofiles).css('display','flex');    
                      }
                       
                       if (file && worker)
                       {
                            file.status = Dropzone.CANCELED;
                            that.dropzone.emit("canceled", file);
                            that.dropzone.processQueue();
                            worker.postMessage(JSON.stringify({'cmd': 'stop'}));
                       }
                       
                    })
                    .fail(function (dataJSON){
                        
                    });
               
               return false;
            });
             
        },
        
        _decodeNode: function($node)
        {
            
            var options = this.options
                , pass = $node.data('content')
                , $textNode = $node.find(options.content_item_text)
                , name = arquematics.simpleCrypt.decryptBase64(pass ,$.trim($node.data('name')))
                , src = arquematics.simpleCrypt.decryptBase64(pass ,$.trim($node.data('src')))
                , $image;
             
                 $textNode.text(name);
                 $node.data('name', name);
                 
                 $node.data('src', src); 
                 
                 if ($node.attr('data-image')) {
                   $node.data('image', arquematics.simpleCrypt.decryptBase64(pass ,$.trim($node.data('image')))); 
                 }
                 else
                 {
                   $node.data('image', src);      
                 }
                 //si no tiene load-url es un documento 
                 //de las herramientas
                 if (!$node.data('inline') && 
                     arquematics.mime.isImageType($node.data('document-type'))
                    && (src && (src.length > 0)))
                 {
                       arquematics.graphics.getByDataOrURL(src)
                        .then(function (img) {
                            if (img instanceof HTMLImageElement)
                            {
                                $node.find('.file-text-content')
                                    .remove();
                                $image = $(img);
                                
                                var perCentWidth;
                                
                                if ($node.parents('.document-image-container-single').length > 0)
                                {                        
                                   perCentWidth = $node.width() * 0.3;
                                   
                                   if ($image.width() >= perCentWidth)
                                   {
                                       $image.css('margin-top', '-20px');
                                       $image.width('98%');
                                       $image.css('height', 'auto');      
                                   }     
                                }
                                else
                                {
                                  perCentWidth = $node.width() * 0.6;
                                  if ($image.width() >= perCentWidth)
                                  {
                                    $image.width('98%');
                                    $image.css('height', 'auto');    
                                  }      
                                }
                                
                                $node.append($image); 
                            }
                        });
                 }
                 
        },
        
        _getSizeAndShowWait: function ()
        {
           var usedSizes = this.options.image_sizes
           ,  findSizeByWidth = function (width)
             { 
                 var ret = usedSizes[0];
                
                 for ( var i = 0; (i < usedSizes.length); i++ )
                 {
                     ret = ((usedSizes[i].width > ret.width) 
                            && (usedSizes[i].width < width * 0.8))?usedSizes[i]:ret;
                 }
                 return ret;
             };

           
           $('#modal-full-screen').modal('show');

           
           return findSizeByWidth(Math.max(document.documentElement.clientWidth, window.innerWidth || 0));
        },
       
        _showPhotoSwipe: function(galleryItems, galleryOptions, hideToolBar)
        {
             var  pswpElement = document.querySelectorAll('.pswp')[0]
             , that = this 
             , options = this.options;

             function loadFilesRelated(item)
             {
              var files = item.filesGuids
              , filesLoaded = []
              , d = $.Deferred();
                          
              if (files && (files.length > 0))
              {
                for ( var i = files.length -1, end = files.length;(i >= 0); --i)
                {
                  $.when(arquematics.loader.getFileObject('/doc/note/' + item.guid  + '/file/' + files[i], files[i], item.pass))
                  .done(function (dataObj){

                    filesLoaded.push(dataObj);
                                               
                    end--;
                    if (end === 0)
                    {
                       d.resolve(filesLoaded);
                    }
                  });
                }
              }
              else {
                  d.resolve(filesLoaded);
              }

              return d;
            }
                 
            
            if (this.gallery)
            {
              this.gallery.close();
            }

            var gallery = this.gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, galleryItems, galleryOptions);      

            
            gallery.listen('gettingData', function( index, item ) 
            {
                
            });
            

            gallery.listen('imageLoadComplete', function(index, item)
            {
                $("#cmd-download-image").off();
                $("#cmd-download-image").click(function (e) 
                {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    var index = gallery.getCurrentIndex()
                    , item = gallery.currItem
                    , extension = arquematics.mime.findExtensionByMimeType(item.documentType)
                    , link;
                    
                   if (item.inline)
                   {
                      if (arquematics.document.isRawchartType(item.documentType)
                          && (arquematics.codec.Base64.isBase(item.image)))
                      {
                          link = document.createElement('a');
                          link.href =  item.image;
                          link.download = item.name + '.' + extension;
                          link.target = '_back';

                          if (document.createEvent) 
                          {
                            var customEvent = document.createEvent('MouseEvents');
                            customEvent.initEvent('click', true, true);
                            link.dispatchEvent(customEvent);
                          }  
                      }
                      else if (arquematics.mime.isTextType(item.documentType))
                      {
                        $.when(loadFilesRelated(item))
                        .done(function (filesRelated){
                          
                          for (var i = filesRelated.length -1;(i >= 0); --i)
                          {
                              if (item.file && item.file.replace)
                              {
                                item.file = item.file.replace(new RegExp( '\\!\\['  + filesRelated[i].gui.replace('\\-','\\-') + '\\]\\(\\)', 'g'),'!['+ filesRelated[i].gui + '](' + filesRelated[i].blob + ')');      
                              } 
                          }

                          link = document.createElement('a');
                          link.href = 'data:text/plain;charset=utf-8,' + arquematics.codec.encodeURIData(item.file);
                          link.download = item.name + '.md';
                          link.target = '_back';
      
                          if (document.createEvent) 
                          {
                            var customEvent = document.createEvent('MouseEvents');
                            customEvent.initEvent('click', true, true);
                            link.dispatchEvent(customEvent);
                          }  
                        });

                      }
                      else if (arquematics.mime.isSvgImageType(item.documentType))
                      {
                        link = document.createElement('a');
                        link.href = 'data:image/svg+xml;charset=utf-8,' + arquematics.codec.encodeURIData(item.file);
                        link.download = item.name + '.' + extension;
                        link.target = '_back';

                        if (document.createEvent) 
                        {
                            var customEvent = document.createEvent('MouseEvents');
                            customEvent.initEvent('click', true, true);
                            link.dispatchEvent(customEvent);
                        }  
                      }
                      else if (extension && arquematics.codec.isDataURL(item.file))
                      {
                        link = document.createElement('a');
                        link.href = item.file;
                        link.download = item.name + '.' + extension;
                        link.target = '_back';
      
                        if (document.createEvent) 
                        {
                          var customEvent = document.createEvent('MouseEvents');
                          customEvent.initEvent('click', true, true);
                          link.dispatchEvent(customEvent);
                        }      
                      }
                   }
                   else
                   {
                     $.when(that._loadFullFile(item.load_url, item.pass))
                    .done(function (restoredFile){
                       
                       $.when(arquematics.loader.getBase64String(restoredFile, item.documentType))
                        .done(function (base64String){
                            
                            link = document.createElement('a');
                            
                            link.href = 'data:' + item.documentType + ';base64,' + base64String;
                            link.download = item.name;
                            link.target = '_back';
      
                            if (document.createEvent) {
                                var customEvent = document.createEvent('MouseEvents');
                                customEvent.initEvent('click', true, true);
                                link.dispatchEvent(customEvent);
                            }
                        });
                        
                     });      
                   }
                   return false;
                });  

                
                if (arquematics.document.isRawchartType(item.documentType))
                {
                    setTimeout(function(){
                            var appFrame = $('#appViewer-' + item.id)[0];
                                appFrame.onload = function() 
                                {
                                    if (appFrame.contentWindow.view)
                                    {
                                      
                                      appFrame.contentWindow.view(item.file, options.culture );      
                                    }
                                };
                    },20); 
                }
                else if (arquematics.mime.isMimeVisorType(item.documentType))
                {
                    //tipos para visor de PDF
                     if (arquematics.mime.isPDFType(item.documentType)
                        || arquematics.mime.isOfficeType(item.documentType))
                     {
                       setTimeout(function(){

                           var appFrame = $('#appViewer-' + item.id)[0];
                              appFrame.onload = function()
                              {
                                appFrame.contentWindow.PDFViewerApplication.open(item.file);
                                
                                $(this).contents().find('.toolbar').hide();  
                              };
                        },2); 
                    }
                    else if (arquematics.mime.isCompressedType(item.documentType))
                    {
                        setTimeout(function(){
                            var appFrame = $('#appViewer-' + item.id)[0];
                                appFrame.onload = function() 
                                {
                                    if (appFrame.contentWindow.extract)
                                    {
                                      appFrame.contentWindow.extract(item.file, item.documentType, options.culture );      
                                    }
                                };
                        },20); 
                            
                    }
                    else if (arquematics.mime.isSvgImageType(item.documentType))
                    {
                        
                        setTimeout(function(){
                          var appFrame = $('#appViewer-' + item.id)[0];

                          appFrame.onload = function() 
                          {
                            var parser = new DOMParser()
                            , doc = parser.parseFromString(item.file, 'image/svg+xml');

                            $(this)
                              .contents()
                              .find('#svg-content')
                              .append(doc.documentElement);
                          }; 

                        },10); 
                        
                    }
                    else if (arquematics.mime.isTextType(item.documentType))
                    {
                      setTimeout(function(){
                        var appFrame = $('#appViewer-' + item.id)[0];

                        appFrame.onload = function() 
                        {
                            if (appFrame.contentWindow.loadDoc)
                            {
                                appFrame.contentWindow.loadDoc(item.file, item.files);      
                            }
                        }; 
                      },20); 
                       
                    }
                    else if (arquematics.mime.isPsd(item.documentType))
                    {
                        setTimeout(function(){
                            var appFrame = $('#appViewer-' + item.id)[0];

                                appFrame.onload = function() 
                                {
                                    if (appFrame.contentWindow.loadPSD)
                                    {
                                      appFrame.contentWindow.loadPSD(item.file);      
                                    }
                                };
                        },20); 
                    }
                    //tipos para visor OpenOffice
                    else if (arquematics.mime.isOpenOfficeType(item.documentType))
                    {
                      setTimeout(function(){
                       var appFrame = $('#appViewer-' + item.id)[0];
                       appFrame.onload = function() 
                       {
                          appFrame.contentWindow.odfDoc('data:' + item.documentType + ';base64,' + item.file);
                          
                          $(this).contents().find('#titlebar').hide();
                          $(this).contents().find('#toolbarContainer').hide();
                       };
                      },2);  
                   }
                   else if (arquematics.mime.is3DSTL(item.documentType))
                   {
                       setTimeout(function(){
                       var appFrame = $('#appViewer-' + item.id)[0];
                       appFrame.onload = function() 
                       {
                          appFrame.contentWindow.load3DDoc( item.file, appFrame.contentWindow);
                       }
                      },2);  
                   }
                }
            });
            
            gallery.listen('afterInit', function() {
               //quita la segunda barra
               $(options.content_wrapper)
                            .hide(); 
             
                if (hideToolBar)
                {
                  $('.pswp__top-bar').addClass('hide');     
                }
                else
                {
                  $('.pswp__top-bar').removeClass('hide');     
                }
            });
            
            gallery.listen('close', function() {
                clearInterval(window.photoswipeSlideshow);
                $('button.pswp__button--slideshow' ).off('click');
                $('.pswp').addClass('hide').hide();

                $('#modal-full-screen').modal('hide');
                
                $(options.content_wrapper)
                    .show()
                    .removeClass('hide');

            });
            
            // beforeResize event fires each time size of gallery viewport updates
            gallery.listen('beforeResize', function() {
            
            });
            
                  
            gallery.init();
                  
        },
        
        _addGalleryNodeHandlers: function ($node)
        {
            var options = this.options
            , that = this;
           
            $node.on("click",function(e)
            {
               e.preventDefault();
               var $elem = $(e.currentTarget)
               , docId = $elem.data('id')
               , $galleryNode = $elem.parents(options.content_container)
               , $contentItems =  $galleryNode.find(options.content_item_visor)
               , $contentItem
               //tamaño a cargar para los elementos
               , galleryItems = []
               , i = 0
               , start = new Date().getTime()
               , galleryOptions = {index:i,
                                   jQueryMobile: true}
                               
               , addToGallery = function(item)
               {
                  galleryItems.push(item);
                  
                  var ret = (galleryItems.length >= $contentItems.length);
                  if (ret)
                  {
                      $galleryNode.data('loaded', true);
                  }
                  return ret;
               };
               
               if (($contentItems.length > 0) && !$galleryNode.data('loaded'))
               {
                  var usedSize = that._getSizeAndShowWait()
                  , entropy = new Uint32Array(32)
                  , itemsToLoad = $contentItems.length
                  , workrsRun = 0
                  , workrQueue = [];
                        
                        crypto.getRandomValues(entropy);
                        
                        $contentItems.each(function() 
                        {
                            var $contentItem = $(this);
                            if ($contentItem.data('inline'))
                            {
                               if (arquematics.document.isRawchartType($contentItem.data('document-type')))
                               {
                                   $contentItem.data('dataFile',$contentItem.data('src'));
                                   itemsToLoad--;
                                   if ((itemsToLoad === 0)
                                        && (workrQueue.length === 0))
                                   {
                                        $galleryNode.data('loaded', true);
                                        $elem.click();
                                   }
                               }
                               else if (arquematics.mime.isSvgImageType($contentItem.data('document-type')))
                               {
                                  $contentItem.data('dataFile',arquematics.codec.decodeURIData($contentItem.data('src')));
                                  itemsToLoad--;
                                  if ((itemsToLoad === 0)
                                    && (workrQueue.length === 0))
                                  {
                                    $galleryNode.data('loaded', true);
                                    $elem.click();
                                  }
                               }
                               else if (arquematics.mime.isImageType($contentItem.data('document-type')))
                               {
                                   $.when(arquematics.loader.getObjectURLFromDataURL($contentItem.data('src')))
                                    .done(function (blobURLOBJ){
                                        
                                        $contentItem.data('dataFile', $contentItem.data('src'));
                                        $contentItem.attr('href', blobURLOBJ);
                                        
                                        itemsToLoad--;
                                        if ((itemsToLoad === 0)
                                        && (workrQueue.length === 0))
                                        {
                                            $galleryNode.data('loaded', true);

                                            $elem.click();
                                        }
                                    });
                               }
                               else if (arquematics.mime.isTextType($contentItem.data('document-type')))
                               {
                                   $contentItem.data('dataFile', $contentItem.data('src'));
                                   
                                   var files = $contentItem.data('files');
                                 
                                   if (files && (files.length > 0))
                                   {
                                        for ( var i = files.length -1, end = files.length;(i >= 0); --i)
                                        {
                                            $.when(arquematics.loader.getFileObjectURL('/doc/note/' + $contentItem.data('guid')  + '/file/' + files[i],$contentItem.data('guid'), files[i], $contentItem.data('content')))
                                            .done(function (dataObj){
                                                var $noteNode = $galleryNode.find(options.content_item_visor + "[data-guid='" + dataObj.gui +"']")
                                                , filesLoaded = $noteNode.data('files-loaded');
                                                
                                                filesLoaded.push(dataObj);
                                                $noteNode.data('files-loaded', filesLoaded);

                                                end--;
                                                if (end === 0)
                                                {
                                                  itemsToLoad--;
                                                }
                                                
                                                if ((end === 0)
                                                    && (itemsToLoad === 0)
                                                    && (workrQueue.length === 0))
                                                {
                                                    $galleryNode.data('loaded', true); 
                                                    
                                                    $elem.click();  
                                                }
                                            });
                                        }
                                   }
                                   else
                                   {
                                        itemsToLoad--;
                                        if ((itemsToLoad === 0)
                                            && (workrQueue.length === 0))
                                        {
                                            $galleryNode.data('loaded', true); 
                                            
                                            $elem.click();
                                        }
                                   }
                               }
                            }
                            else
                            {
                                var worker = new Worker('/arquematicsPlugin/js/arquematics/widget/wall/dropzone/work.downloadBase64.js');

                                worker.addEventListener('message', function(e) {
                                    var data = e.data;
                                    if (data && data.byteLength) //ok tiene datos
                                    {
                                    
                                        var $node = $galleryNode.find(options.content_item_visor + "[data-id='" + worker.workerId +"']");
                                     
                                        //si es una imagen la carga en memoria
                                        if (arquematics.mime.isImageType($node.data('document-type')))
                                        {
                                          $.when(arquematics.loader.getObjectURL(data, $node.data('document-type')))
                                            .done(function (blobURLOBJ){
                                                
                                                $node.attr('href', blobURLOBJ);
                                                worker.postMessage({'cmd': 'stop'});
                                          }); 
                                        }
                                        else if (arquematics.mime.isDxf($node.data('document-type'))
                                            || arquematics.mime.isDwg($node.data('document-type')))
                                        {
                                            $.when(arquematics.loader.getObjectURL(data, 'image/png'))
                                            .done(function (blobURLOBJ){
                                                $node.attr('href', blobURLOBJ);
                                                worker.postMessage({'cmd': 'stop'});
                                            });
                                            
                                        }
                                        else if (arquematics.mime.isPsd($node.data('document-type')))
                                        {
                                            $.when(arquematics.loader.getBlob(data, $node.data('document-type')))
                                            .done(function (data){
                                                $node.data('dataFile', data);
                                                worker.postMessage({'cmd': 'stop'});
                                            });
                                        }
                                        else if (arquematics.mime.isPDFType($node.data('document-type'))
                                              || arquematics.mime.isOfficeType($node.data('document-type')))
                                        {
                                            //los documentos office estan en PDF
                                            $.when(arquematics.loader.getUint8Array(data, 'application/pdf'))
                                            .done(function (data){
                                                $node.data('dataFile', data);
                                                worker.postMessage({'cmd': 'stop'});
                                            });     
                                        }
                                        else if (arquematics.mime.isCompressedType($node.data('document-type')))
                                        {
                                            $.when(arquematics.loader.getBlob(data, $node.data('document-type')))
                                            .done(function (data){
                                                
                                                $node.data('dataFile', data);
                                                worker.postMessage({'cmd': 'stop'});
                                            });    
                                        }
                                        else if (arquematics.mime.isOpenOfficeType($node.data('document-type')))
                                        {
                                            $.when(arquematics.loader.getBase64String(data))
                                            .done(function (data){
                                                
                                                $node.data('dataFile', data);
                                                worker.postMessage({'cmd': 'stop'});
                                            }); 
                                            
                                        }
                                        else if (arquematics.mime.is3DSTL($node.data('document-type')))
                                        {
                                            $.when(arquematics.loader.getBinaryString(data))
                                            .done(function (data){
                                                
                                                $node.data('dataFile', data);
                                                worker.postMessage({'cmd': 'stop'});
                                            }); 

                                        }
                                        else
                                        {
                                          $node.data('dataFile', data);
                                          //parar el worker
                                          worker.postMessage({'cmd': 'stop'});      
                                        }
                                    }
                                    else
                                    {
                                        switch (data.cmd) {
                                            case 'ready':
                                            //identificacion del worker  
                                            worker.workerId = $contentItem.data('id');
                                            worker.fileURL = $contentItem.data('load-url');
                                            worker.fileMime = $contentItem.data('document-type');
                                            worker.usedSize = usedSize.name;
                                            worker.pass = $contentItem.data('content');       
                                            if (workrsRun > 2)
                                            {
                                                workrQueue.push(worker);      
                                            }
                                            else
                                            {
                                                //inicia el worker
                                                worker.postMessage(JSON.stringify({'cmd': 'start', 
                                                    'fileURL': worker.fileURL,
                                                    'fileMime': worker.fileMime,
                                                    'usedSize': worker.usedSize,
                                                    'pass': worker.pass}));
                                            }
                                        break;
                                        case 'start':
                                            worker.start = true;
                                            workrsRun++;
                                        break;
                                        case 'debug':
                                            if (console)
                                            {
                                                console.log(data);      
                                            }
                                        break;
                                        case 'err':
                                            if (console)
                                            {
                                                console.log('error');
                                                console.log(data);  
                                            }
                                        break;
                                    
                                        case 'stop':
                                            //termina el hilo
                                            worker.start = false;
                                            //se han cargado todos los elementos
                                            itemsToLoad--;
                                            //lanza el primer work de la cola 
                                            if (workrQueue.length > 0)
                                            {
                                                var workrQueueItem = workrQueue.shift();
                                                //inicia el worker
                                                workrQueueItem.postMessage(JSON.stringify({'cmd': 'start', 
                                                    'fileURL': workrQueueItem.fileURL,
                                                    'fileMime':workrQueueItem.fileMime,
                                                    'usedSize': workrQueueItem.usedSize,
                                                    'pass': workrQueueItem.pass}));
                                            
                                            }       
                                            else if ((itemsToLoad === 0)
                                                && (workrQueue.length === 0))
                                            {
                                                $galleryNode.data('loaded', true);
                                                
                                                var end = new Date().getTime();
                                                var time = end - start;
                                                
                                                if (console)
                                                {
                                                  console.log('Execution time: ' + time);      
                                                }
        
                                                //muestra las imagenes
                                                $elem.click();
                                           
                                            }
                                            else
                                            {
                                                workrsRun--;
                                            }
                                            break;
                                          }      
                                        }
                                
                                    }, false);
                                    //inicializacion del worker
                                    worker.postMessage({
                                        cmd: 'entropy',
                                        value: entropy,
                                        size: 1024,
                                        source: 'crypto.getRandomValues'
                                    });  
                            }
                        });

               }
               else if (($contentItems.length > 0) && $galleryNode.data('loaded'))
               {
                   $contentItems.each(function() 
                   {
                        var $contentItem = $(this);
                      
                        if (docId == $contentItem.data('id'))
                        {
                          galleryOptions.index = i;
                        }
                        i++;
                        
                        if (arquematics.document.isRawchartType($contentItem.data('document-type')))
                        {
                          if (addToGallery({
                                        editUrl: $contentItem.data('load-url'),
                                        inline: $contentItem.data('inline'),
                                        load_url: $contentItem.data('load-url'),
                                        hasPrev: ($contentItems.length === 1) ? false: true,
                                        hasNext: ($contentItems.length === 1) ? false: true,
                                        name: $contentItem.data('name'),
                                        image: $contentItem.data('image'),
                                        id: $contentItem.data('id'),
                                        html: '<iframe id="appViewer-' + $contentItem.data('id') + '" src="/arquematicsPlugin/js/components/rawchart/index.html" style="width: 100%; height: 100%;" allowfullscreen="" webkitallowfullscreen=""></iframe>',
                                        documentType: $contentItem.data('document-type'),
                                        pass: $contentItem.data('content'),
                                        file: $contentItem.data('dataFile')
                                 }))
                             {
                                  that._showPhotoSwipe(galleryItems, galleryOptions, false);  
                             }
                        }
                        else if (arquematics.mime.isPDFType($contentItem.data('document-type'))
                           || arquematics.mime.isOfficeType($contentItem.data('document-type')))
                        {
                            if (addToGallery({
                                        editUrl: false,
                                        inline: $contentItem.data('inline'),
                                        load_url: $contentItem.data('load-url'),
                                        hasPrev: ($contentItems.length === 1) ? false: true,
                                        hasNext: ($contentItems.length === 1) ? false: true,
                                        name: $contentItem.data('name'),
                                        id: $contentItem.data('id'),
                                        html: '<iframe id="appViewer-' + $contentItem.data('id') + '" src="/arquematicsPlugin/js/components/pdf/build/generic/web/viewer.html" style="width: 100%; height: 100%;" allowfullscreen="" webkitallowfullscreen=""></iframe>',
                                        documentType: $contentItem.data('document-type'),
                                        pass: $contentItem.data('content'),
                                        file: $contentItem.data('dataFile')
                                 }))
                             {
                                  that._showPhotoSwipe(galleryItems, galleryOptions, false);  
                              }
                                 
                                 
                        }
                        else if (arquematics.mime.isTextType($contentItem.data('document-type')))
                        {
                        
                          if (addToGallery({
                                        editUrl: $contentItem.data('load-url'),
                                        inline: $contentItem.data('inline'),
                                        load_url: $contentItem.data('load-url'),
                                        hasPrev: ($contentItems.length === 1) ? false: true,
                                        hasNext: ($contentItems.length === 1) ? false: true,
                                        name: $contentItem.data('name'),
                                        id: $contentItem.data('id'),
                                        html: '<iframe id="appViewer-' + $contentItem.data('id') + '" src="/arquematicsPlugin/js/components/markdown/index.html" style="width: 100%; height: 100%;" allowfullscreen="" webkitallowfullscreen=""></iframe>',
                                        documentType: $contentItem.data('document-type'),
                                        pass: $contentItem.data('content'),
                                        guid: $contentItem.data('guid'),
                                        filesGuids: $contentItem.data('files'),
                                        files: $contentItem.data('files-loaded'),
                                        file: $contentItem.data('dataFile')
                                 }))
                                {
                                   that._showPhotoSwipe(galleryItems, galleryOptions, false);  
                                }
                        }
                        else if (arquematics.mime.isSvgImageType($contentItem.data('document-type')))
                        {
                          if (addToGallery({
                                        editUrl: $contentItem.data('load-url'),
                                        inline: $contentItem.data('inline'),
                                        load_url: $contentItem.data('load-url'),
                                        hasPrev: ($contentItems.length === 1) ? false: true,
                                        hasNext: ($contentItems.length === 1) ? false: true,
                                        name: $contentItem.data('name'),
                                        id: $contentItem.data('id'),
                                        html: '<iframe id="appViewer-' + $contentItem.data('id') + '" src="/arquematicsPlugin/js/components/svg/index.html" style="width: 100%; height: 100%;" allowfullscreen="" webkitallowfullscreen=""></iframe>',
                                        documentType: $contentItem.data('document-type'),
                                        pass: $contentItem.data('content'),
                                        file: $contentItem.data('dataFile')
                                 }))
                             {
                                  that._showPhotoSwipe(galleryItems, galleryOptions, false);  
                             }
                        }
                        else if (arquematics.mime.isPsd($contentItem.data('document-type')))
                        {
                            if (addToGallery({
                                        editUrl: false,
                                        inline: $contentItem.data('inline'),
                                        load_url: $contentItem.data('load-url'),
                                        hasPrev: ($contentItems.length === 1) ? false: true,
                                        hasNext: ($contentItems.length === 1) ? false: true,
                                        name: $contentItem.data('name'),
                                        id: $contentItem.data('id'),
                                        html: '<iframe id="appViewer-' + $contentItem.data('id') + '" src="/arquematicsPlugin/js/components/psd/dist/index.html" style="width: 100%; height: 100%;" allowfullscreen="" webkitallowfullscreen=""></iframe>',
                                        documentType: $contentItem.data('document-type'),
                                        pass: $contentItem.data('content'),
                                        file: $contentItem.data('dataFile')
                                 }))
                                {
                                   that._showPhotoSwipe(galleryItems, galleryOptions, false);
                                }
                        }
                        else if (arquematics.mime.isCompressedType($contentItem.data('document-type')))
                        {
                            if (addToGallery({
                                        editUrl: false,
                                        inline: $contentItem.data('inline'),
                                        load_url: $contentItem.data('load-url'),
                                        hasPrev: ($contentItems.length === 1) ? false: true,
                                        hasNext: ($contentItems.length === 1) ? false: true,
                                        name: $contentItem.data('name'),
                                        id: $contentItem.data('id'),
                                        html: '<iframe id="appViewer-' + $contentItem.data('id') + '" src="/arquematicsPlugin/js/components/ViewCompressed/index.html" style="width: 100%; height: 100%;" allowfullscreen="" webkitallowfullscreen=""></iframe>',
                                        documentType: $contentItem.data('document-type'),
                                        pass: $contentItem.data('content'),
                                        file: $contentItem.data('dataFile')
                                 }))
                                {
                                  that._showPhotoSwipe(galleryItems, galleryOptions, false);  
                                }
                        }
                        else if (arquematics.mime.isOpenOfficeType($contentItem.data('document-type')))
                        {
                            if (addToGallery({
                                        editUrl: false,
                                        inline: $contentItem.data('inline'),
                                        load_url: $contentItem.data('load-url'),
                                        hasPrev: ($contentItems.length === 1) ? false: true,
                                        hasNext: ($contentItems.length === 1) ? false: true,
                                        name: $contentItem.data('name'),
                                        id: $contentItem.data('id'),
                                        html: '<iframe id="appViewer-' + $contentItem.data('id') + '" src="/arquematicsPlugin/js/components/ViewerJS/index.html" style="width: 100%; height: 100%;" allowfullscreen="" webkitallowfullscreen=""></iframe>',
                                        documentType: $contentItem.data('document-type'),
                                        pass: $contentItem.data('content'),
                                        file: $contentItem.data('dataFile')
                                 }))
                                {
                                  that._showPhotoSwipe(galleryItems, galleryOptions, false); 
                                }
                        }
                        else if (arquematics.mime.is3DSTL($contentItem.data('document-type')))
                        {
                            if (addToGallery({
                                        editUrl: false,
                                        inline: $contentItem.data('inline'),
                                        load_url: $contentItem.data('load-url'),
                                        hasPrev: ($contentItems.length === 1) ? false: true,
                                        hasNext: ($contentItems.length === 1) ? false: true,
                                        name: $contentItem.data('name'),
                                        id: $contentItem.data('id'),
                                        html: '<iframe id="appViewer-' + $contentItem.data('id') + '" src="/arquematicsPlugin/js/components/stlviewer/index.html" style="width: 100%; height: 100%;" allowfullscreen="" webkitallowfullscreen=""></iframe>',
                                        documentType: $contentItem.data('document-type'),
                                        pass: $contentItem.data('content'),
                                        file: $contentItem.data('dataFile')
                                 }))
                                {
                                  that._showPhotoSwipe(galleryItems, galleryOptions, false);
                                }
                        }
                        else if (arquematics.mime.isDxf($contentItem.data('document-type'))
                              || arquematics.mime.isDwg($contentItem.data('document-type')))
                        {
                            var image  = new Image();
                             image.onload = function() {
                                
                                if (addToGallery({
                                    editUrl: false,
                                    inline: $contentItem.data('inline'),
                                    load_url: $contentItem.data('load-url'),
                                    hasPrev: ($contentItems.length === 1) ? false: true,
                                    hasNext: ($contentItems.length === 1) ? false: true,
                                    name: $contentItem.data('name'),
                                    id: $contentItem.data('id'),
                                    documentType: 'image/png',
                                    pass: $contentItem.data('content'),
                                    src: $contentItem.attr('href'),
                                    file: $contentItem.data('dataFile'),
                                    w: this.width, // image width
                                    h: this.height // image height
                                }))
                                {
                                  that._showPhotoSwipe(galleryItems, galleryOptions, false);    
                                }
                                
                            };
                            
                            image.src = $contentItem.attr('href');
                        }
                        else if (arquematics.mime.isImageType($contentItem.data('document-type')))
                        {
                             var image  = new Image();
                             image.onload = function() {
                                
                                if (addToGallery({
                                    inline: $contentItem.data('inline'),
                                    editUrl: $contentItem.data('url'),
                                    load_url: $contentItem.data('load-url'),
                                    hasPrev: ($contentItems.length === 1) ? false: true,
                                    hasNext: ($contentItems.length === 1) ? false: true,
                                    name: $contentItem.data('name'),
                                    id: $contentItem.data('id'),
                                    documentType: $contentItem.data('document-type'),
                                    pass: $contentItem.data('content'),
                                    file: $contentItem.data('dataFile'),
                                    src: $contentItem.attr('href'),
                                    w: this.width, // image width
                                    h: this.height // image height
                                }))
                                {
                                   that._showPhotoSwipe(galleryItems, galleryOptions, false);
                                }
                                
                            };
                            
                            image.onerror = function(e){
                                //display error
                                if (console)
                                {
                                   console.log(e);      
                                }
                            };

                            image.src = $contentItem.attr('href');
                        } 
                    });
               }
            });
        },
        
        _loadFullFile: function(url, pass)
        {
           var worker = new Worker('/arquematicsPlugin/js/arquematics/widget/wall/dropzone/work.downloadBase64.js')
           , entropy = new Uint32Array(32)
           , d = $.Deferred();
                        
           worker.addEventListener('message', function(e) {
                var data = e.data;
                //esto posiblemente tenga que cambiarlo por compatibilidad
                if (data && data.byteLength) //ok tiene datos
                {
                   d.resolve(data);                 
                }
                else
                {
                    switch (data.cmd) {
                        case 'ready':
                            //inicia el worker
                            worker.postMessage(JSON.stringify({'cmd': 'start', 
                             'fileURL': url,
                             'pass': pass}));    
                        break;
                        case 'start':
                        break;
                        case 'debug':
                            if (console)
                            {
                              console.log(data);      
                            }
                        break;
                        case 'err':
                            d.reject(data); 
                        break;
                        case 'stop':
                        break;
                    }
                }
           }, false);
           
           crypto.getRandomValues(entropy);
           //inicializacion del worker
           worker.postMessage({
                cmd: 'entropy',
                value: entropy,
                 size: 1024,
                 source: 'crypto.getRandomValues'
           });
           
           return d;
        },
        
        _prepareNode: function($node)
        {
             var imageClass = this.options.content_item_visor.replace(/\./g, '');
             
             this._decodeNode($node);
             
             if ($node.hasClass(imageClass))
             {
               this._addGalleryNodeHandlers($node);      
             }
             
        },
        
        getThumbnail: function(file, dataUrl)
        {
            if (file.previewElement)
            {
                var images = file.previewElement.querySelectorAll("[data-dz-thumbnail]");
                
                file.previewElement.classList.remove("dz-file-preview");
                
                for (var ii = 0; ii < images.length; ii++)
                {
                        var $thumbnailElement = $(images[ii]);
                        
                        $thumbnailElement.attr('alt',file.name);
                        $thumbnailElement.attr('src', dataUrl);
                }
                setTimeout(function() { file.previewElement.classList.add("dz-image-preview"); }, 1);
            }
            //genera el nuevo elemento de icono
            else
            {
                    
            }
        },
        
        uploadThumbnails: function(data, file, pass)
        {
            var self = this
            ,  d = $.Deferred()
            ,  imageSizes = this.options.image_sizes
            ,  imageSize;
                   
            for ( var i = imageSizes.length -1, countItems = imageSizes.length; i >= 0; --i )
            {
               imageSize = imageSizes[i];
               
               $.when(arquematics.graphics.createThumbnailFromDataUrl(data.src , file.type, imageSize.name, imageSize.width, imageSize.height))
               //$.when(self.dropzone.createThumbnailFromUrlNoEvents(data.src,imageSize.width, imageSize.height, imageSize.name))
                 .done(function (dataUrl, name){
                      $.when(self.sendFilePreview({
                                   size: function ()
                                   {
                                     return this.src.length;  
                                   }, 
                                   src: dataUrl,
                                   type: data.type, 
                                   dropFileId: data.fileId,
                                   style: name
                        },pass))
                      .done(function (){
                            
                            countItems--;
                            //actualiza la barra de progreso
                            self.dropzone.emit("uploadprogress", file, 100 * ((imageSizes.length - 1) - countItems) / (imageSizes.length - 1));
                            if (countItems <= 0)
                            {
                                d.resolve(true);     
                            }        
                        })
                      .fail(function (){
                        d.reject();          
                        });
                 })
                .fail(function (){
                     d.reject();         
                });
            }
            
            return d;
        },
        
        getMimeType: function(file)
        {
            var fileMimeType = arquematics.mime.findRealMimeType(file.type);
            if (fileMimeType === '')
            {
                fileMimeType = arquematics.mime.findRealMimeTypeByFileName(file.name); 
            }
            return fileMimeType;
        },
        
        uploadFiles: function (files) 
        {
            var that = this  
            , reader = new FileReader()
            , dropzone = arquematics.dropzone
            , options = dropzone.options
            
            //, pass = dropzone.randomGenerator.get()
            
            , itemsToSave = files.length
            , workrsRun = 0
            , workrQueue = []
            , fileMimeType = '';

            //oculta la ventana predetermina para cuando no tiene
            //archivos si no esta oculta ya
            $(options.content_files_preview_nofiles).hide();
            
            options.sessionFiles += itemsToSave;
            
            //espera a que termine la peticion anterior
            arquematics.dropzone.waitForContent = true;
            
            function parseOptions(options){
                return JSON.stringify({
                   BYTES_PER_CHUNK: options.BYTES_PER_CHUNK,
                   sendURL:  $(options.form_drop).attr('action'),
                   csrfTokenSend: $(options.input_control_csrf_token).val(),
                   sendPreviewURL: $(options.form_drop_preview).attr('action'),
                   csrfTokenPreview: $(options.input_control_preview_csrf_token).val(), 
                   chunkPreviewURL: $(options.form_chunk_preview).attr('action'),
                   csrfTokenChunkPreview: $(options.input_control_preview_chunk_csrf_token).val(),
                   chunkURL: $(options.form_chunk).attr('action'),
                   csrfTokenChunk: $(options.input_control_chunk_csrf_token).val(),
                   sizes: options.image_sizes
                });
            }
            
            if (files.length > 0)
            {
              //ocultar el boton de ocultar todo el control
              $(options.content_files_cancel_all).hide();
              
              for (var i = 0, file; i < files.length; i++)
              {
                file =  files[i];
                file.id = i;
                
                reader.onload = function (event) {

                   fileMimeType = dropzone.getMimeType(file);
                   
                   if ((fileMimeType.length > 0) 
                       && (arquematics.mime.isFileType(fileMimeType, dropzone.mimeTypesAllowed)))
                   {
                       var entropy = new Uint32Array(32)
                       , worker = new Worker('/arquematicsPlugin/js/arquematics/widget/wall/dropzone/work.uploadBase64.js');
                        
                        worker.addEventListener('message', function(e) {
                                var data = e.data
                                , endFile;
                           
                                switch (data.cmd) {
                                    case 'ready':
                                     //identificacion del worker 
                                     // con la id del index del fichero 
                                     worker.workerId = file.id;
                                     worker.data = arquematics.codec.ArrayBuffer.toBase64String(event.target.result, fileMimeType);
                                     worker.type = fileMimeType;
                                     worker.name = file.name;
                                     worker.size = file.size;
                                     worker.pass = arquematics.utils.randomKeyString(50);
                                     worker.url = '';
                                     
                                     if (workrsRun >= 3)
                                     {
                                       workrQueue.push(worker);      
                                     }
                                     else
                                     {
                                        //inicia el worker
                                        worker.postMessage(
                                              {
                                                cmd: 'start',
                                                type: worker.type,
                                                name: worker.name,
                                                size: worker.size,
                                                pass: worker.pass,
                                                options: parseOptions(options),
                                                fileArrayBuf: worker.data
                                              }
                                        );
                                     }
                                    break;
                                    case 'start':
                                        worker.start = true;
                                        workrsRun++;
                                    break;
                                    case 'fileCreated':
                                        endFile = files[worker.workerId];
                                      
                                        var $cmdCancel = $(endFile.previewElement).find(options.cmd_cancel)
                                        , $parent = $cmdCancel.parent(); 
                                        
                                        $cmdCancel.data('url', data.url);
                                        
                                        dropzone._preparePreviewNodeHandlers($parent, endFile, worker);
                                        
                                        if (!arquematics.mime.isImageType(worker.type))
                                        {
                                            //esto lo hago asi porque
                                            //endFile.type es de solo lectura y puede
                                            //no esta bien la detección del tipo mime 
                                            dropzone._setIconForFile(endFile, worker.type);       
                                        }
                                        
                                    break;
                                    case 'debug':
                                        if (console)
                                        {
                                          console.log(data);      
                                        }
                                        
                                    break;
                                    case 'err':
                                        if (console)
                                        {
                                            console.log('error');        
                                        }
                                        endFile = files[worker.workerId];

                                        endFile.status = Dropzone.ERROR;
                                        that.emit("error", endFile, 'error', null);
                                        that.emit("complete", endFile);
                                        that.processQueue();
                                        
                                        options.sessionFiles--;
                                        itemsToSave--;
                                        
                                         //se han guardado todos los ficheros
                                        if ((itemsToSave === 0)
                                            && (workrQueue.length === 0)
                                            && (options.sessionFiles === 0))
                                        {
                                            //ha terminado 
                                            arquematics.dropzone.waitForContent = false;
                                        }
                                    break;
                                    case 'createThumbnails':
                                        var images = []
                                        , fileData = data.extraContent? data.fileData:worker.data
                                        , fileType = data.extraContent? data.dataType:worker.type;
                                        
                                        for ( var i = options.image_sizes.length -1, thumbnailCount = 0; i >= 0; --i )
                                        {
                                            var imageSize = options.image_sizes[i];
                                            $.when(arquematics.graphics.createThumbnailFromDataUrl(fileData , fileType, imageSize.name, imageSize.width, imageSize.height))
                                            .done(function (dataUrl, name, mimeType){
                                                
                                                images.push({name: name, src: dataUrl, type: mimeType});
                                                
                                                thumbnailCount++;
                                                if (thumbnailCount === options.image_sizes.length)
                                                {
                                                   worker.postMessage({cmd: 'sendThumbnails',
                                                                     fileId: data.fileId,
                                                                     //URL del fichero no dataURL
                                                                     url: data.url,
                                                                     type: worker.type,
                                                                     name: worker.name,
                                                                     images: JSON.stringify(images),
                                                                     pass: worker.pass,
                                                                     options: parseOptions(options)});     
                                                }
                                            });
                                        }  
                                    break;
                                    case 'uploadprogress':

                                        var progressFile = files[worker.workerId];
                                        that.emit("uploadprogress", progressFile, data.progress);
                                    break;
                                    case 'stop':
                                        //termina el hilo
                                        worker.start = false;
                                        //mensaje a Dropzone para que de por terminada
                                        //la descarga
                                        endFile = files[worker.workerId];

                                        endFile.status = Dropzone.SUCCESS;
                                        
                                        that.emit("success", endFile, 'success', null);
                                        that.emit("complete", endFile);
                                        that.processQueue();

                                        options.sessionFiles--;
                                        itemsToSave--;
                                        //se han guardado todos los ficheros
                                        if ((itemsToSave === 0)
                                             && (workrQueue.length === 0)
                                             && (options.sessionFiles === 0) 
                                        )
                                        {
                                          //ha terminado 
                                          arquematics.dropzone.waitForContent = false;
                                        }
                                        //lanza el primer work de la cola 
                                        else if (workrQueue.length > 0)
                                        {
                                            
                                           var workrQueueItem = workrQueue.shift();
                                            //inicia el worker
                                            workrQueueItem.postMessage(
                                              {
                                                cmd: 'start',
                                                type: worker.type,
                                                name: worker.name,
                                                size: worker.size,
                                                pass: workrQueueItem.pass,
                                                options: parseOptions(options),
                                                fileArrayBuf: worker.data
                                              }
                                            );
                                        }
                                        else
                                        {
                                           workrsRun--;
                                        }
                                    break;
                                   }      
                                
                            }, false);
                            
                        crypto.getRandomValues(entropy);
                        //inicializacion del worker
                        worker.postMessage({
                            cmd: 'initCrypt',
                            value: entropy,
                            size: 1024,
                            source: 'crypto.getRandomValues',
                            privPublic: arquematics.crypt.getData(),
                            keys: JSON.stringify(arquematics.crypt.getPublicEncKeys())
                        });
                   }
                   //error el tipo mime no esta bien
                   else
                   {   
                     file.status = Dropzone.ERROR;
                     that.emit("error", file, 'error', null);
                     that.emit("complete", file);
                     that.processQueue();

                     options.sessionFiles--;
                     itemsToSave--;
                     
                     //se han guardado todos los ficheros
                     if ((itemsToSave === 0)
                        && (workrQueue.length === 0)
                        && (options.sessionFiles === 0))
                     {
                       //ha terminado 
                       arquematics.dropzone.waitForContent = false;
                     }
                     
                      var $cmdCancel = $(file.previewElement).find(options.cmd_cancel)
                     , $parent = $cmdCancel.parent(); 
                     
                     dropzone._preparePreviewNodeHandlersError($parent, file);
                   }  
                };

                reader.readAsArrayBuffer(file);
              }   
            }
        },

        controlName: function()
        {
          return 'drop';
        },
        
        sendContent: function ()
        {
          if (this.options.sessionFiles === 0)
          {
             arquematics.wall.context.next(); 
             //desbloquea las acciones
             this.waitForContent = false;
             //no tiene archivos en la sesion
             this.options.hasSessionFiles = false;
             
             $('body').removeClass('loading');
          }
        },
            
        /**
         * lista de estados disponibles para ejecutar
         * 
         * @returns {array}
         */       
        getAvailableToolStatus: function()
        {
            var ret = [];
            ret.push(new arquematics.dropzone.sendDropContent());

            return ret;
        },
        
        update: function(message) 
        {
          var that = this
          , options = this.options;

          if (message instanceof arquematics.wall.message)
          {
              if (message.getState() === arquematics.wall.messageStatus.ready)
              {
                 var $nodeItem = message.getContent();
              
                 $nodeItem.find(options.content_item).each(function() {
                    that._prepareNode($(this));
                }); 
              }
          }
          //resetea el control
          this.reset();
	}
};

arquematics.dropzone.sendDropContent = function () 
{
    this.name = 'sendDropContent';

    this.go = function (param)
    {
        if (!arquematics.dropzone.waitForContent)
        {
          arquematics.dropzone.sendContent();      
        }
        else
        {
          $('body').addClass('loading');
          //espera 1/2 segundo y vuelve a intentar
          setTimeout($.proxy(function() {
            this.go(param);
          }, this), 500);    
        }
        
    };
};

}(jQuery, arquematics, tmpl, blobUtil, window));

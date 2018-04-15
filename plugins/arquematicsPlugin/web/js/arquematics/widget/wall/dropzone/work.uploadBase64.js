    /**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 */

var arquematics = {};

importScripts('/arquematicsPlugin/js/arquematics/arquematics.formdata.js');
importScripts('/arquematicsPlugin/js/arquematics/arquematics.core.micro.js');
importScripts('/arquematicsPlugin/js/arquematics/arquematics.mime.js');

/*
 * esta parte no funciona nada bien
 * he decidido usar cloudconvert
arquematics.conversor = {
    
    base64DwgToImageDataURI: function(base64Data) {
         var ret = {}
            , dataType
            , arrayBuffer = arquematics.codec.Base64.toArrayBuffer(base64Data)
            , bytes = new DataView(arrayBuffer)
            , offset = bytes.getInt32(0xd, true) + 0x14
            , imageCount = bytes.getUint8(offset)
            , find = false
            , imageType
            , headerStart
            , headerLength;
            
        offset += 1;
        
        function readBmp(headerStart, headerLength) {
            var bitCount, imageLength, colorTableSize, bmpBytes, bmpView, bmpOffset;
            offset += 0xe;
            bitCount = bytes.getUint16(offset, true);
            offset += 2 + 4;
            imageLength = bytes.getUint32(offset, true);
            offset += 4;
            offset = headerStart;
            colorTableSize = Math.floor(bitCount < 9 ? 4 * Math.pow(2, bitCount) : 0);
            bmpBytes = new ArrayBuffer(
              2 +
              4 +
              2 +
              2 +
              4 +
              headerLength);
            bmpView = new DataView(bmpBytes);
            bmpOffset = 0;
            bmpView.setUint16(bmpOffset, 0x4d42, true);
            bmpOffset += 2;
            bmpView.setUint32(bmpOffset, 54 + colorTableSize + imageLength, true);
            bmpOffset += 4 + 2 + 2;
            bmpView.setUint32(bmpOffset, 54 + colorTableSize, true);
            bmpOffset += 4;
            for (var i = 0; i < headerLength; i++) {
                bmpView.setUint8(bmpOffset + i, bytes.getUint8(headerStart + i));
            }
            return bmpBytes;
        }
        
        
        function readPng(headerStart, headerLength) {
            var pngBytes = arrayBuffer.slice(headerStart, headerStart + headerLength);
            return pngBytes;
        }
        
        if (0 === imageCount) return false;
        for (var i = 0; (!find && (i < imageCount)); i += 1) {
            imageType = bytes.getUint8(offset);
            offset += 1;
            headerStart = bytes.getInt32(offset, true);
            offset += 4;
            headerLength = bytes.getInt32(offset, true);
            offset += 4;
            
            switch (imageType) {
                case 1:
                break;
                case 2:
                    find = true;
                    dataType = 'image/bmp';
                    ret = "data:image/bmp;base64," + arquematics.codec.ArrayBuffer.toBase64String(readBmp(headerStart, headerLength));
                break;
                case 3:
                    //return;
                break;
                case 6: 
                    find = true;
                    dataType = 'image/png';
                    ret = "data:image/png;base64," + arquematics.codec.ArrayBuffer.toBase64String(readPng(headerStart, headerLength));
                break;
                default:
            }
        }
        return (find)?{dataType: dataType, data: ret}:false;
    } 
}
*/
arquematics.cloudConvert = function() {
        //conversiones entre ficheros
        this.conversionTypes = {
          //cad
          'dxf': 'png',
          'dwg': 'png',
         //Office
         //word
          'docx': 'pdf',
          'doc':  'pdf',
         //powerpoint
          'ppt':  'pdf',
          'pptx': 'pdf',
          //excel
          'xls': 'csv'}  
};

arquematics.cloudConvert.prototype = {
  getOutputFormatByExt: function (ext)
  {
     return (this.conversionTypes[ext])?this.conversionTypes[ext]: false;
  },  
  createProcess: function(inputformat, outputformat)
  {
    var d = new Deferred()
    , form = new FormData();

    form.append('inputformat', inputformat );
    form.append('outputformat', outputformat);

     Deferred.when(arquematics.HTTP.doPostAjax('/dropfile/createProcess', form))
        .done(function (dataJSON){
          if (dataJSON.status == 500)
          {
            d.reject();       
          }
          else
          {
            d.resolve(dataJSON.url);
          }
        })
        .fail(function (){  
            d.reject(); 
        });


    return d.promise();
  },
  start: function (processURL, file, outputformat)
  {
    var d = new Deferred()
    , form = new FormData();

    form.append('input', 'base64');
    form.append('file', file.src); // fileBase64String
    form.append('filename', file.name);
    form.append('outputformat', outputformat);
    form.append('wait', 'false');
    
    Deferred.when(arquematics.HTTP.doPostAjax('https:' + processURL, form))
        .done(function (process){
          
          d.resolve(process.url);   
        })
        .fail(function (){  
            d.reject(); 
        });

    return d.promise();
  },
  getStatus: function (url)
  {
    var d = new Deferred();

    Deferred.when(arquematics.HTTP.doGetAjax('https:' + url))
        .done(function (dataJSON){
          if (dataJSON.step && ((dataJSON.step == 'finished') || (dataJSON.step == 'output')))
          {
            d.resolve(dataJSON);
          }
          else
          {
            d.reject(dataJSON); 
          }
            
        })
        .fail(function (){  
            d.reject(false); 
        });

    return d.promise();
  }

}


var dropzoneTaskUpload =  {
        getCloudConvertPreview: function(file)
        {
          
          var d = new Deferred()
          , conversionTool = new arquematics.cloudConvert()
          
          //tipo de extension del archivo
          , inputFormat = arquematics.mime.getInputConvertNameType(file.type)
          , outputformat = conversionTool.getOutputFormatByExt(inputFormat);
          
          Deferred.when(conversionTool.createProcess(inputFormat, outputformat))
           .done(function (process){
             if (self.start)
             {
               Deferred.when(conversionTool.start(process, file, outputformat))
                .done(function (processUrl){
                   if (self.start)
                   {
                     Deferred.when(conversionTool.getStatus(processUrl + '?wait'))
                    .done(function (dataJSON){
                       if (self.start)
                       {
                         Deferred.when(arquematics.HTTP.doGet('https:' + dataJSON.output.url, null, 'arraybuffer'))
                         .done(function (arraybuffer){
                            d.resolve(arquematics.codec.ArrayBuffer.toBase64String(arraybuffer));
                          })
                         .fail(function (){
                            d.reject(); 
                          });  
                       } 
                    })
                    .fail(function (){
                        d.reject(); 
                    });  
                  }     
              })
              .fail(function (){
                d.reject(); 
              });
                  
             }
           })
           .fail(function (){
                d.reject(); 
            });

          return d.promise();
        },
   
        prepareFormDataChunkPreview: function (data, postIndex, pass, options)
        {
            var form = new FormData();

            form.append('ar_drop_file_chunk_preview[_csrf_token]', options.csrfTokenChunkPreview );
            form.append('ar_drop_file_chunk_preview[chunkData]', arquematics.simpleCrypt.encryptBase64(pass , data));
            form.append('ar_drop_file_chunk_preview[pos]', postIndex);
            
            return form;
        },
        
        parseFile: function ( imageData, pass, options)
        {
            var start = 0
           , end = options.BYTES_PER_CHUNK
           , imageDataSize = imageData.length
           , chunk = ''
           , chunkIndex = 0
           , ret = [];
            
            if (start < imageDataSize)
            {
               while(start < imageDataSize)
               {
                chunk = imageData.substring(start, end);   
                
                ret.push({
                        pos: chunkIndex,
                        chunkData: arquematics.simpleCrypt.encryptBase64(pass , chunk)  
                });
                
                start = end;
                end = start + options.BYTES_PER_CHUNK;
                
                chunkIndex++;
              }     
           }
           
           return JSON.stringify(ret);
        },
        
        
        sendFileChunksPreview: function (previewID, imageData, pass, options)
        {
            var d = new Deferred()
           , formData
           , start = 0
           , end = options.BYTES_PER_CHUNK
           , imageDataSize = imageData.src.length
           , chunk = ''
           , chunkIndex = 0;
            
            if (start < imageDataSize)
            {
               while(start < imageDataSize)
               {
               
                chunk = imageData.src.substring(start, end);   
                
                formData = this.prepareFormDataChunkPreview(chunk, chunkIndex, pass, options);
                
                if (self.start)
                {
                   Deferred.when(arquematics.HTTP.doPostAjax(options.chunkPreviewURL + previewID, formData))
                    .done(function (dataJSON){
                        if (dataJSON.status == 200)
                        {
                          if (start >= imageDataSize)
                          {
                              d.resolve(true);      
                          }
                        }
                        else
                        {
                          end = imageDataSize;
                          d.reject();      
                        }
                    })
                    .fail(function (){
                       end = imageDataSize;
                       d.reject(); 
                    });          
                }

                start = end;
                end = start + options.BYTES_PER_CHUNK;
                
                chunkIndex++;
              }     
            }
            else
            {
              d.resolve(true);      
            }
           
           return d.promise();
        },

        
        prepareFormDataPreview: function (imageData,  pass, options)
        {
             var src = (imageData.src.length <= options.BYTES_PER_CHUNK)?
                        arquematics.simpleCrypt.encryptBase64(pass , imageData.src): this.parseFile(imageData.src, pass, options)
            , form = new FormData();
            
            form.append('ar_drop_file_preview[_csrf_token]', options.csrfTokenPreview);
            form.append('ar_drop_file_preview[type]', imageData.type);
            form.append('ar_drop_file_preview[size_style]', imageData.name);
            form.append('ar_drop_file_preview[src]', src);
            form.append('ar_drop_file_preview[size]', imageData.src.length);
            form.append('ar_drop_file_preview[guid]', this.guid());
            
            return form;
        },
        
        sendFilePreview: function (file, imageData,  pass, options)
        {
            var d = new Deferred()
           , formData = this.prepareFormDataPreview(imageData,  pass, options);
           
           if (self.start)
           {
               //envia el fichero
               Deferred.when(arquematics.HTTP.doPostAjax(options.sendPreviewURL + file.id, formData))
                    .done(function (dataJSON){
                        
                        (dataJSON.status == 200)?
                            d.resolve(dataJSON.values.id)
                            :d.reject(); 
                              
                    })
                    .fail(function (){
                       d.reject(); 
                }); 
           }
           
           return d.promise();
        },
        
        prepareFormDataChunk: function (data, postIndex, pass, options)
        {
            var form = new FormData();
            
            form.append('ar_drop_file_chunk[_csrf_token]', options.csrfTokenChunk);
            form.append('ar_drop_file_chunk[chunkData]', arquematics.simpleCrypt.encryptBase64(pass , data));
            form.append('ar_drop_file_chunk[pos]', postIndex);
            
            return form;
        },

        sendFileChunks: function (file, fileId, pass, options)
        {
            var d = new Deferred()
           , that = this
           , formData
           , start = 0
           , end = options.BYTES_PER_CHUNK
           , blob = file.src
           , blobSize = blob.length
           , chunkIndex = 0
           , chunk = '';
            
            if (start < blobSize)
            { 
               while(start < blobSize)
               {
               
                chunk = blob.substring(start, end);   
                        
                formData = that.prepareFormDataChunk(chunk, chunkIndex, pass, options)
                
                 if (self.start)
                 {
                     Deferred.when(arquematics.HTTP.doPostAjax(options.chunkURL + '/' + fileId, formData))
                    .done(function (dataJSON){
                        //envia las partes
                        if (dataJSON.status == 200)
                        {
                            //self.postMessage({'cmd': 'uploadprogress', 'progress': 100 * start / blobSize});
                            
                            if (start >= blobSize)
                            {
                              d.resolve(true);      
                            }  
                        }
                        else
                        {
                           d.reject();      
                        }
                    })
                    .fail(function (){
                       d.reject(); 
                    });
                }
                
                start = end;
                end = start + options.BYTES_PER_CHUNK;
                
                chunkIndex++;
              }     
            }
            else
            {
              d.resolve(true);      
            }
           
           return d.promise();
        },

        S4: function () {
           return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
        },

       
        guid: function () {
                return (this.S4()+this.S4()+'-'+this.S4()+'-'+this.S4()+'-'+this.S4()+'-'+this.S4()+this.S4()+this.S4());
        },
        
        prepareFormData: function (file, pass, options)
        {
            var src = (file.src.length <= options.BYTES_PER_CHUNK)?
                        arquematics.simpleCrypt.encryptBase64(pass , file.src): this.parseFile(file.src, pass, options)
            , form = new FormData()
            ,  d = new Deferred();
         
            form.append('ar_drop_file[_csrf_token]', options.csrfTokenSend);
            form.append('ar_drop_file[type]', file.type);
            form.append('ar_drop_file[name]', arquematics.simpleCrypt.encryptBase64(pass ,file.name));
            form.append('ar_drop_file[size]', file.size);
            form.append('ar_drop_file[src]', src);
            form.append('ar_drop_file[guid]', this.guid());
            
            
            Deferred.when(arquematics.utils.encryptAsyn(pass))
            .done(function (data){
                form.append('ar_drop_file[pass]', data);
                
                d.resolve(form);       
            });
                    
            return d.promise();
        },
    
        sendFile: function (file, pass, options)
        {
           var d = new Deferred()
           , that = this;
                
           Deferred.when(this.prepareFormData(file, pass, options))
           .done(function (formData){
            
               //envia el fichero
               Deferred.when(arquematics.HTTP.doPostAjax(options.sendURL, formData))
                   .done(function (dataJSON){
                        self.postMessage({'cmd':'debug','typeData': typeof dataJSON});
                        self.postMessage({'cmd':'debug','allData': dataJSON});
                        self.postMessage({'cmd': 'fileCreated', 'url': dataJSON.values.url});
                        
                        if (dataJSON.status == 200)
                        {
                          d.resolve({id:dataJSON.values.id, 
                                     url: dataJSON.values.url});        
                        }
                        else
                        {
                          d.reject();         
                        }
                    })
                    .fail(function (){
                       d.reject(); 
                    });
            
           });

           return d.promise();
        },
        
        sendImages: function (file, images, pass, options)
        {
             var d = new Deferred();
            
            for ( var i = images.length -1, countItems = images.length; i >= 0; --i )
            {
                 if (self.start)
                 {
                     Deferred.when(this.sendFilePreview(
                                file, images[i],  pass, options))
                    .done(function (){
                            countItems--;
                            //actualiza la barra de progreso
                            self.postMessage({'cmd': 'uploadprogress', 'progress': 100 * ((images.length - 1) - countItems) / (images.length - 1)});
                            
                            if (countItems <= 0)
                            {
                                d.resolve({'cmd': 'stop',  'fileUrl': file.url, 'fileId': file.id });     
                            }        
                    })
                    .fail(function (){
                        d.reject({'cmd': 'error'});
                    });                   
                 }                    
            }
            
            return d.promise();
        },
        /**
         * el fichero esta listo para ser procesado
         */
        sendFileReady: function(url, cmd)
        {
            var d = new Deferred();
            
            Deferred.when(arquematics.HTTP.doPutAjax(url))
                .done(function (){   
                     d.resolve(cmd);   
             })
            .fail(function (){
                 d.reject({'cmd': 'err'});
            });
            
            return d.promise();
        },
        
	sendData: function (fileName, fileType, size, fileArrayBuf, pass, options)
        {
            var d = new Deferred()
            , that = this;

            //self.postMessage({cmd: 'debug', info: 'entra en sendData'});
           
            if (arquematics.mime.isOfficeType(fileType))
            {
                Deferred.when(this.sendFile({
                                        src: fileArrayBuf,
                                        type: fileType,
                                        size:  size,
                                        name: fileName
                                        }, pass, options))
                        .done(function (fileRes){
                            
                           if (self.start)
                           {
                                Deferred.when(that.getCloudConvertPreview({
                                        src: fileArrayBuf,
                                        type: fileType, 
                                        name: fileName
                                        }))
                                .done(function (pdfDataString){
                                  
                                  if (self.start)
                                  {
                                    //envia el PDF generado
                                    Deferred.when(that.sendFilePreview(
                                        {
                                            id: fileRes.id,
                                            src: fileArrayBuf,
                                            type: fileType, 
                                            name: fileName
                                        }, 
                                        {
                                            type: 'application/pdf',
                                            name: 'normal',
                                            src: 'data:application/pdf;base64,' +  pdfDataString
                                        },  
                                        pass, options))
                                    .done(function (){
                                        d.resolve({'cmd': 'stop', 'fileUrl': fileRes.url ,'fileId': fileRes.id});
                                    })
                                    .fail(function (){
                                        d.reject({'cmd': 'err'}); 
                                    });  
                                 }
                             })
                             .fail(function (){
                                d.reject({'cmd': 'err'});
                               });   
                           }
                        })
                        .fail(function (){
                            d.reject({'cmd': 'err'});
                        });    
              }
              else if (arquematics.mime.isDwg(fileType)
                       || arquematics.mime.isDxf(fileType))
               {
                     Deferred.when(this.sendFile({
                                        src: fileArrayBuf,
                                        type: fileType,
                                        size:  size, 
                                        name: fileName
                                        }, pass, options)) 
                     .done(function (fileRes){
                         
                          if (self.start)
                          {
                             Deferred.when(that.getCloudConvertPreview({
                                        src: fileArrayBuf,
                                        type: fileType, 
                                        name: fileName
                                        }))
                                .done(function (convertedDataPNG){
                                  
                                  if (self.start)
                                  {
                                    d.resolve({cmd:'createThumbnails', extraContent: true, fileData: convertedDataPNG, fileType: 'image/png', url: fileRes.url, fileId: fileRes.id });   
                                  }
                             })
                             .fail(function (){
                                d.reject({'cmd': 'err'});
                               });  
                         }
                     })
                     .fail(function (){
                             d.reject({'cmd': 'err'});
                     });   
              }
              else if (arquematics.mime.isImageType(fileType))
              {
                        Deferred.when(this.sendFile({
                                        src: fileArrayBuf,
                                        type: fileType, 
                                        size:  size,
                                        name: fileName
                                        }, pass, options))
                            .done(function (fileRes){
                  
                               self.postMessage({'cmd': 'uploadprogress', 'progress': 12});
                               
                               d.resolve({cmd:'createThumbnails', extraContent: false, fileType: fileType, url: fileRes.url, fileId: fileRes.id });
                            })
                            .fail(function (){
                                d.reject({'cmd': 'err'});
                            });
               }
               else 
               {
                        //por defecto simplemente manda el archivo
                        Deferred.when(this.sendFile({
                                        src: fileArrayBuf,
                                        type: fileType,
                                        size:  size, 
                                        name: fileName
                                        }, pass, options)) 
                        .done(function (fileRes){
                            d.resolve({'cmd': 'stop', 'fileUrl': fileRes.url, 'fileId': fileRes.id});
                        })
                        .fail(function (){
                             d.reject({'cmd': 'err'});
                        });   
              }
    return d.promise();
  }
};


self.addEventListener('message', function(e) {
  var data = e.data;

  //self.postMessage({'cmd':'debug','allData': data});

  switch (data.cmd) {
    case 'initCrypt':
        
        sjcl.random.addEntropy(data.value, data.size, data.source);
        //inicia arquematics.crypt
        arquematics.utils.initEncrypt(data.privPublic, JSON.parse(data.keys));
        
        self.postMessage({'cmd':'ready'});
    break;

    case 'start':
    
    self.start = true;
    //suma un work mas
    self.postMessage({'cmd':'start'});
   
        if (self.start)
        {
            //self.postMessage({'cmd': 'debug', 'data': data});
            
            data.options = JSON.parse(data.options); 
            Deferred.when(dropzoneTaskUpload.sendData( data.name, data.type, data.size, data.fileArrayBuf, data.pass, data.options))
                .done(function (cmd){
                    if (cmd.cmd === 'stop')
                    {
                       Deferred.when(dropzoneTaskUpload.sendFileReady(cmd.fileUrl,cmd))
                       .done(function (cmd){
                           self.postMessage(cmd);
                       })
                      .fail(function (){
                            self.postMessage({'cmd':'err'}); 
                       });
                    }
                    else
                    {
                      self.postMessage(cmd);       
                    }
                })
                .fail(function (){
                    self.postMessage({'cmd':'err'}); 
                });
        }
    break;
      
    case 'sendThumbnails':
        if (self.start)
        {
            Deferred.when(dropzoneTaskUpload.sendImages({id: data.fileId, url: data.url, name: data.name, type: data.type}, JSON.parse(data.images), data.pass, JSON.parse(data.options)))
            .done(function (cmd){
                if (cmd.cmd === 'stop')
                {
                       Deferred.when(dropzoneTaskUpload.sendFileReady(cmd.fileUrl, cmd))
                       .done(function (cmd){
                           self.postMessage(cmd);
                       })
                      .fail(function (){
                            self.postMessage({'cmd':'err'}); 
                       });
                }
                else
                {
                    self.postMessage(cmd);       
                }
            })
            .fail(function (){
                self.postMessage({'cmd':'err'}); 
            });    
        }
    break;
    
    case 'stop':
      self.start = false;
      self.postMessage({'cmd':'stop','text':'WORKER STOPPED:'});
      self.close(); // Terminates the worker.
      break;
  }
}, false);
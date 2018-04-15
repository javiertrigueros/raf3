    /**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 */

var arquematics = {};

importScripts('/arquematicsPlugin/js/arquematics/arquematics.core.micro.js');
importScripts('/arquematicsPlugin/js/arquematics/arquematics.mime.js');

var dropzoneTaskDownload =  {
	
        getData: function (fileURL, pass, fileMime, usedSize)
        {
            fileMime = fileMime || false; 
            usedSize = usedSize || false;
                
            var d = new Deferred()
            , that = this
            , loadFile = function(url, pass){
                  
                 Deferred.when(that._loadAndDecodeFullFile(url, pass))
                       .done(function (imageFile){
                          
                         d.resolve(imageFile);    
                  })
                  .fail(function (){
                       d.reject(); 
                  });
            };
            
            if (!fileMime && !usedSize && fileURL && pass)
            {
               loadFile(fileURL , pass);     
            }
            else if (arquematics.mime.isPDFType(fileMime)
                || arquematics.mime.isOpenOfficeType(fileMime)
                || arquematics.mime.isPsd(fileMime)
                || arquematics.mime.is3DSTL(fileMime)
                || arquematics.mime.isCompressedType(fileMime))
            {
                   loadFile(fileURL , pass);    
            }
            else if (arquematics.mime.isOfficeType(fileMime))
            {
                   loadFile(fileURL  + '/' + 'normal', pass);
            }
            else if (arquematics.mime.isDwg(fileMime)
                    || arquematics.mime.isDxf(fileMime)
                    || arquematics.mime.isImageType(fileMime))
            {
                   loadFile(fileURL  + '/' + usedSize, pass);
            }

            return d.promise();
        },
        
        _loadAndDecodeChunkData: function (fileURL, countParts, pass)
        {
            var d = new Deferred()
            , dataChunk = []
            //indice de posiciones empieza en 0
            , count = countParts -1;

            for (var i = 0, end = 0; (i <= count); i++)
            {
                 
                 Deferred.when(arquematics.HTTP.doGetAjax(fileURL + '/' + i))
                    .done(function (chunk){
                        
                        if (chunk.status && (chunk.status === 500))
                        {
                           d.reject();      
                        }
                        else
                        {
                           chunk.chunk = arquematics.simpleCrypt.decryptBase64(pass , chunk.chunk);
                           
                           
                           dataChunk.push(chunk);
                           
                           end++;
                           if (end > count)
                           {
                               d.resolve(dataChunk);                
                           }     
                        }
                    })
                    .fail(function (){
                       d.reject(); 
                    }); 
            }
            
            return d.promise();
        },
        
        _loadAndDecodeFullFile: function (url, pass)
        {
             var d = new Deferred()
            , that = this;
             
             function search(pos, myArray){
                
                for (var i=0; i < myArray.length; i++) 
                {
                    if (myArray[i].pos == pos) {
                        return myArray[i].chunk;
                    }
                }
                return false;
             }
            
             Deferred.when(arquematics.HTTP.doGetAjax(url))
                    .done(function (dataJSON){
                         
                         //las partes  estan en la misma respuesta
                         if ((dataJSON.values.parts === 0) && 
                              dataJSON.values.chunks && (dataJSON.values.chunks.length > 0))
                         { 
                             var retOneGet = '';
                             for (var ii = 0; ii < dataJSON.values.chunks.length; ii++)
                             {
                                 retOneGet += arquematics.simpleCrypt.decryptBase64(pass ,  dataJSON.values.chunks[ii]);
                             }
                             
                             
                             d.resolve(arquematics.codec.Base64.toArrayBuffer(retOneGet));
                         }
                         else if  (dataJSON.values.parts > 0)
                         { 
                               
                               Deferred.when(that._loadAndDecodeChunkData(url, dataJSON.values.parts,pass)) 
                                .done(function (dataChunks){
              
                                  var ret = '';
                                     
                                   for (var ii = 0; ii < dataChunks.length; ii++)
                                   {
                                      ret += search(ii, dataChunks);
                                   }
                                  
                                   d.resolve(arquematics.codec.Base64.toArrayBuffer(ret));
                                                          
                                });
                         }
                         //solo tenemos una parte
                         else
                         {
                            d.resolve(arquematics.codec.Base64.toArrayBuffer(arquematics.simpleCrypt.decryptBase64(pass ,  dataJSON.values.content)));     
                         }
                    })
                    .fail(function (){
                       d.reject(); 
                    });
             
            
            return d.promise();
        }
};


self.addEventListener('message', function(e) {
  var data;
  
  try{
    data = JSON.parse(e.data);  
  }
  catch(err) {
    data = e.data;    
  }
  
  switch (data.cmd) {
    case 'entropy':
       
        sjcl.random.addEntropy(data.value, data.size, data.source);
        /* mirar esto para hacer el test de enviar contenido
        window.postMessage('', '*', new Float32Array(4).buffer);
        */
        self.postMessage({ 'cmd': 'ready' });
    case 'start':
        
      self.postMessage({'cmd': 'start'});
      
      
      Deferred.when(dropzoneTaskDownload.getData(data.fileURL, data.pass, data.fileMime, data.usedSize ))
      .done(function (blobItem){
         
          self.postMessage(blobItem, [blobItem]);
      })
      .fail(function (){
          self.postMessage({'cmd':'err'}); 
      });
      break;
    case 'stop':
      self.postMessage({'cmd':'stop','text':'WORKER STOPPED:'});
      self.close(); // Terminates the worker.
      break;
  }
}, false);
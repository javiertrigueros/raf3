(function($) {

var filearr = [];
var tablearr = [];

//var output = document.getElementById("output");

zip.workerScriptsPath = '/arquematicsPlugin/js/components/ViewCompressed/js/zip/';

function show(result){
    
    var config = {
        grid: { 
            name: 'grid',
            show: { 
                footer    : true,
                toolbar    : true
            },
            columns: [                
                { field: 'name', caption: 'Name', size: '140px', sortable: true, searchable: 'text', resizable: true },
                { field: 'type', caption: 'Type', size: '140px', sortable: true, searchable: 'text', resizable: true },
                { field: 'size', caption: 'Size', size: '120px', resizable: true, sortable: true, render: 'money' }
            ]
        }
    };
    
    $().w2grid(config.grid);
    
    for (var i = 0; i < result.length; i++) {
        
        w2ui['grid'].records.push({ 
            name: result[i].name,
            type: result[i].type,
            size: result[i].size
        });
    }
    
    w2ui.grid.refresh();
    $('#main').w2render('grid');
}
  
var count = 0;
var entries;

function callback(a,b){

    if(a){
       // output.innerHTML = a; //errors
    } else {
        count++;

        filearr.push(b);

        tablearr.push({
            "name":entries[count-1].name,
            "type":b.type,
            "size":b.size
        });

        if(count==entries.length){
            //console.log("done");
            show(tablearr);
        }
    }
}

//https://github.com/43081j/rar.js
window.unrar = function (rar)
{
    count=0;
    RarArchive(rar, function(err) {
        var self = this;
        if(err && console) {
            console.log(err);
            return;
        }
        this.entries.forEach(function(file) {
            self.get(file,callback);
        });

       entries = this.entries;
    });
}
            
window.unzip = function(blob)
{
    model.getEntries(blob, function(entries) {
     entries.forEach(function(entry) {
        model.getEntryFile(entry, "Blob");
        });
    });
}


            //model for zip.js
            //https://github.com/gildas-lormeau/zip.js

            var model = (function() {
                var acount = 0
                , bcount = 0;

                //compile a list of file extensions and content types
                //http://webdesign.about.com/od/multimedia/a/mime-types-by-content-type.htm
                var mapping = {
                    "pdf":"application/pdf",
                    "zip":"application/zip",
                    "rar":"application/rar",
                    "json":"application/json",
                    "mid":"audio/mid",
                    "mp3":"audio/mpeg",
                    "bmp":"image/bmp",
                    "gif":"image/gif",
                    "png":"image/png",
                    "jpg":"image/jpeg",
                    "jpeg":"image/jpeg",
                    "svg":"image/svg+xml",
                    "xml":"text/xml"
                }


                return {
                    getEntries : function(file, onend) {

                        zip.createReader(new zip.BlobReader(file), function(zipReader) {
                            zipReader.getEntries(onend);
                        }, onerror);
                    },
                    getEntryFile : function(entry, creationMethod, onend, onprogress) {

                        acount++;

                        var writer, zipFileEntry;

                        function getData() {
                            entry.getData(writer, function(blob) {

                                bcount++;

                                filearr.push(blob);

                                tablearr.push({
                                    "name":entry.filename,
                                    "type":blob.type,
                                    "size":blob.size
                                });
                                
                                if(acount == bcount){
                                    show(tablearr);
                                }
                         
                            }, onprogress);
                        }
                            
                            //console.log(entry);
                            var extension = entry.filename.substring(entry.filename.indexOf(".")+1);
                            var mime = mapping[extension] || 'text/plain';
                            //console.log(mime);

                            writer = new zip.BlobWriter(mime);
                            getData();
                    }
                };
            })();

})(window.parent.jQuery);

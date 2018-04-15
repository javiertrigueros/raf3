// http://stackoverflow.com/questions/6507293/convert-xml-to-string-with-jquery
function xmlToString(xmlData) {
    var xmlString;
    if (window.ActiveXObject){
        // IE
        xmlString = xmlData.xml;
    } else {
        // Mozilla, Firefox, Opera, etc.
        xmlString = (new XMLSerializer()).serializeToString(xmlData);
    }
    return xmlString;
}

// Parse an SVG string as XML
function stringToXML(svgString) {
    // Replace any leading whitespace which will mess up XML parsing
    svgString =  svgString.replace(/^[\s\n]*/, "");

    if (!svgString) { return; }

    // Parse SVG as XML
    var svgDoc;
    try {
        svgDoc = $.parseXML(svgString);
    } catch (err) {
        alert("Unable to parse SVG");
        console.log(svgString);
    }

    return svgDoc;
}

// Get an SVG from the server and add a string version to textarea
// Quite inefficient as we're going to convert it back to a XML object later.
function getExampleSVG(filename) {
    $.get("../static/" + filename + ".svg", function(data) {
        $("#input-svg").val(xmlToString(data)).trigger('change');
    });
}

// Convert string into filesize
function getFileSize(str) {
    var size = str.length / 1000;
    if (size > 1000) {
        return (Math.round(size / 100) / 10) + " MB";
    } else {
        return (Math.round(size * 10) / 10) + " kB";
    }
}

// Clear element with given selector and add contents
function addContentsToDiv(contents, selector) {
    var div = $(selector);

    if (div.length === 1) {
        div.empty();
        div.append(contents);
    }
}

function addSVGStats(selector, filesize, numElements) {
    var div = $(selector);
    if (div.length === 1) {
        div.empty();
        var ul = $('<ul>');
        div.append(ul);
        ul.append($('<li>Filesize: ' + filesize + '</li>'));
        ul.append($('<li>Elements: ' + numElements + '</li>'));
    }
}

function optimiseSVG(svgObj) {
    // Create the new SVG string
    var svgStringNew = svgObj.toString();

    // Show new SVG image
    addContentsToDiv(svgStringNew, '#svg-after .svg-container');

    // Show SVG information
    var compression = Math.round(1000 * svgStringNew.length / svgObj.originalString.length) / 10;
    addSVGStats('#svg-analysis-after .svg-data', getFileSize(svgStringNew) + " (" + compression + "%)", svgObj.options.numElements);

    // Show code of updated SVG
    $('#code-for-download').text(svgStringNew);

    // Put code in hidden form
    $('#svg-string-form').val(svgStringNew);
}

// Add the interface using optimisationOptions
// To each element bind a function to automatically rerun optimisations
function addOptions(svgObj) {
    var containerEl = $('#options-container');
    containerEl.empty();

    for (var i = 0; i < optimisationOptions.length; i++)
    {
        var option = optimisationOptions[i];
        if (option.type === 'dropdown') {
            var dropdownEl = $('<div class="optimisation-option">' + option.name + ': </div>');
            var selectEl = $('<select name="' + option.id + '">');
            
            for (var j = 0; j < option.options.length; j++) {
                var opt = option.options[j];
                var optionEl = $('<option value="' + opt + '"' + (option.defaultValue === opt ? ' selected' : '') + '>' + opt + '</option>');
                selectEl.append(optionEl);
            }

            dropdownEl.append(selectEl);
            
            if (option.optimiseType) {
                dropdownEl.append($('<span> ' + option.optimiseType + '</span>'));
            }
            
            containerEl.append(dropdownEl);
        } else if (option.type === 'checkbox') {
            var checkboxEl = $('<input name="' + option.id + '" type="checkbox"/>' + option.name + '<br/>');
            if (option.defaultValue) {
                checkboxEl.prop('checked', true);
            }
            containerEl.append(checkboxEl);
        }
    }

    // Add update functions

    // Dropdowns
    $('.optimisation-option select').change(function() {
        var id = $(this).attr('name');
        svgObj.options[id] = $(this).val();
        optimiseSVG(svgObj);
    });

    // Checkboxes
    $('#options-container :checkbox').change(function() {
        var id = $(this).attr('name');
        svgObj.options[id] = this.checked;
        optimiseSVG(svgObj);
    });
}

// Parse an SVG string as XML and convert to jQuery object
function loadSVG(svgString) {
    var svgDoc = stringToXML(svgString);

    if (!svgDoc) { return; }

    var jQuerySVG = $(svgDoc).children();
    var svgObj = new SVG_Object(jQuerySVG[0]);
    svgObj.originalString = svgString;

    // Output a nicely formatted file
    //svgObj.options.whitespace = 'pretty';

    // Remove ids
    svgObj.options.removeIDs = true;

    // Add original SVG image
    addContentsToDiv(svgString, '#svg-before .svg-container');
    addSVGStats('#svg-analysis-before .svg-data', getFileSize(svgString), jQuerySVG.find("*").length);

    // Add new SVG image
    optimiseSVG(svgObj);

    // Update interface
    //$('#upload-container').hide("fast");
    addOptions(svgObj);
    $('#optimise-container').show();
    $('#download-container').show();
    $('.no-file-warning').hide();
}

$(document).ready(function() {
    $('#optimise-container').hide();
    $('#download-container').hide();

    var lastValue = '';
    var svgTextArea = $("#input-svg");

    svgTextArea.on('keyup paste mouseup change', function() {
        if (svgTextArea.val() != lastValue) {
            lastValue = $(this).val();
            loadSVG(lastValue);
        }
    });

    // If we have uploaded a file, textarea will have code, so start optimisation
    if (svgTextArea.val()) { svgTextArea.trigger('keyup'); }

    // Expand and contract boxes where SVG code is copied and pasted
    $('.svg-code-box').focus(function() {
      $(this).animate({ height: "160px" }, 200);
    });

    $('.svg-code-box').blur(function() {
      $(this).animate({ height: "32px" }, 200);
    });

    $('#test-post').click(function() {
        $.post("post-svg", { svg: "Hello"}).done(function(data) {
          console.log(data);
        });
    });
});
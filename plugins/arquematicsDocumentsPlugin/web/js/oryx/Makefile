# Set the source directory
srcdir = ./

# Create the list of modules
coreModules =   ${srcdir}lib/prototype-1.5.1.js\
	    ${srcdir}lib/path_parser.js\
	    ${srcdir}lib/ext-2.0.2/adapter/ext/ext-base.js\
	    ${srcdir}lib/ext-2.0.2/ext-all.js\
	    ${srcdir}lib/ext-2.0.2/color-field.js\
	    ${srcdir}Core/oryx.core1.js\
	    ${srcdir}Core/oryx.core2.js\
	    ${srcdir}Core/oryx.core3.js\
	    ${srcdir}Core/oryx.core4.js\
	    ${srcdir}Core/oryx.core5.js\
	    ${srcdir}Core/oryx.core6.js\
	    ${srcdir}Core/oryx.core7.js\
	    ${srcdir}Core/oryx.core8.js\
	    ${srcdir}Core/oryx.core9.js\
	    ${srcdir}Core/oryx.core10.js\
	    ${srcdir}Core/oryx.core11.js\
	    ${srcdir}Core/oryx.core12.js\
	    ${srcdir}Plugins/BPMN11.js\
	    ${srcdir}Plugins/BPMN.js\
	    ${srcdir}Plugins/CreateProcessVariant.js\
	    ${srcdir}Plugins/ShapeRepository.js\
	    ${srcdir}Plugins/PropertyWindow.js\
	    ${srcdir}Plugins/ComplexListField.js\
	    ${srcdir}Plugins/ComplexTextField.js\
	    ${srcdir}Plugins/CanvasResize.js\
	    ${srcdir}Plugins/CanvasResizeButton.js\
	    ${srcdir}Plugins/DragDropResize.js\
	    ${srcdir}Plugins/SelectedRect.js\
	    ${srcdir}Plugins/GridLine.js\
	    ${srcdir}Core/Command/Move.js\
	    ${srcdir}Plugins/Resizer.js\
	    ${srcdir}Plugins/RenameShapes.js\
	    ${srcdir}Plugins/Undo.js\
	    ${srcdir}Plugins/Arrangement.js\
	    ${srcdir}Plugins/Wireframe.js\
	    ${srcdir}Plugins/Grouping.js\
	    ${srcdir}Plugins/ShapeHighlighting.js\
	    ${srcdir}Plugins/HighlightingSelectedShapes.js\
	    ${srcdir}Plugins/DragDocker.js\
	    ${srcdir}Plugins/DockerCreation.js\
	    ${srcdir}Plugins/SelectionFrame.js\
	    ${srcdir}Plugins/ShapeHighlighting.js\
	    ${srcdir}Plugins/HighlightingSelectedShapes.js\
	    ${srcdir}Plugins/Overlay.js\
	    ${srcdir}Plugins/Edit.js\
	    ${srcdir}Plugins/KeysMove.js\
	    ${srcdir}Plugins/File.js\
	    ${srcdir}Plugins/Save.js\
	    ${srcdir}Plugins/ContainerLayouter.js\
	    ${srcdir}Plugins/EdgeLayouter.js\
	    ${srcdir}Plugins/Toolbar.js\
	    ${srcdir}Plugins/ShapeMenuPlugin.js\
	    ${srcdir}Plugins/Loading.js\

all: clean build
 
clean:
	rm -f arquematics.oryx.min.js

build:
	cat ${coreModules} > arquematics.oryx.js 
	uglifyjs --compress --mangle -- arquematics.oryx.js > arquematics.oryx.min.js

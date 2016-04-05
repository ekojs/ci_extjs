Ext.define('EJS.controller.Main', {
    extend: 'Ext.app.Controller',

    refs: [
        {
            ref: 'viewport',
            selector: 'viewport'
        },
        {
            ref: 'navigation',
            selector: 'navigation'
        },
        {
            ref: 'contentPanel',
            selector: '#contentPanel'
        }
    ],

    exampleRe: /^\s*\/\/\s*(\<\/?example>)\s*$/,

    init: function() {
        this.control({
            'navigation': {
                selectionchange: 'onNavSelectionChange'
            },
            'viewport': {
                afterlayout: 'afterViewportLayout'
            },
            'contentPanel': {
                resize: 'centerContent'
            }
        });
    },

    afterViewportLayout: function() {
        if (!this.navigationSelected) {
            var id = location.hash.substring(1),
                navigation = this.getNavigation(),
                store = navigation.getStore(),
                node;

            node = id ? store.getNodeById(id) : store.getRootNode().firstChild.firstChild;			
            navigation.getSelectionModel().select(node);
            navigation.getView().focusNode(node);
            this.navigationSelected = true;
        }
    },

    onNavSelectionChange: function(selModel, records) {
        var record = records[0],
            text = record.get('text'),
            xtype = record.get('id'),
            alias = 'widget.' + xtype,
            contentPanel = this.getContentPanel(),
            cmp;

        if (xtype) {
            contentPanel.removeAll(true);			
            document.title = document.title.split(' - ')[0] + ' - ' + text;
            location.hash = xtype;
			this.setActiveExample(xtype,this.classNameFromRecord(record), text);
        }
    },
	
	setActiveExample: function(xtype,className, title) {
        var contentPanel = this.getContentPanel(),
            path, example, className;
        
        if (!title) {
            title = className.split('.').reverse()[0];
        }
        contentPanel.setTitle(title);
        location.hash = xtype.toLowerCase().split(' ').join('-');
        document.title = document.title.split(' - ')[0] + ' - ' + title;
        example = Ext.create(className);
        contentPanel.removeAll(true);
        contentPanel.add(example);
    },

    filePathFromRecord: function(record) {
        var parentNode = record.parentNode,
            path = record.get('id');
        
        while (parentNode && parentNode.get('text') != "Root") {
            path = parentNode.get('text') + '/' + Ext.String.capitalize(path);

            parentNode = parentNode.parentNode;
        }

        return this.formatPath(path);
    },

    classNameFromRecord: function(record) {
        var path = this.filePathFromRecord(record);
		path = 'EJS.view.' + path.split('/').join('.');
        return path;
    },

    formatPath: function(string) {
    	var result = string.split(' ')[0].charAt(0) + string.split(' ')[0].substr(1),
	        paths = string.split(' '),
	        ln = paths.length,
	        i;

	    for (i = 1; i < ln; i++) {
	        result = result + Ext.String.capitalize(paths[i]);
	    }
	    
        return result.toLowerCase();
    },

    centerContent: function() {
        var contentPanel = this.getContentPanel(),
            body = contentPanel.body,
            item = contentPanel.items.getAt(0),
            align = 'c-c',
            overflowX,
            overflowY,
            offsets;

        if (item) {
            overflowX = (body.getWidth() < (item.getWidth() + 40));
            overflowY = (body.getHeight() < (item.getHeight() + 40));

            if (overflowX && overflowY) {
                align = 'tl-tl',
                offsets = [20, 20];
            } else if (overflowX) {
                align = 'l-l';
                offsets = [20, 0];
            } else if (overflowY) {
                align = 't-t';
                offsets = [0, 20];
            }

            item.alignTo(contentPanel.body, align, offsets);
        }
    }
});

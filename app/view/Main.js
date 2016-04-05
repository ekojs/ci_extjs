Ext.define('EJS.view.Main', {
    extend: 'Ext.container.Container',
    requires:[
        'Ext.layout.container.Border',
		'Ext.layout.container.Card',
		'EJS.view.Navigation'
    ],
    
    xtype: 'app-main',

    layout: {
        type: 'border'
    },

    items: [{
        region: 'west',
        xtype: 'navigation',
        title: 'Form Permintaan Pengguna',
        width: 185,
        split: true,
		collapsed: false,
        stateful: true,
        collapsible: true
    },{
        region: 'center',
        layout: {
            type : 'hbox',
            align: 'stretch'
        },
        items:[{
            flex: 1,
            //title: '&nbsp;',
            id   : 'contentPanel',
            layout: {
                type: 'card'
                //align: 'center',
                //pack: 'center'
            },
            overflowY: 'auto',
            bodyPadding: 0
        }]
    }]
});
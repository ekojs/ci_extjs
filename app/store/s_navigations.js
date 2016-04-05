Ext.define('EKOJS.store.s_navigations', {
    extend: 'Ext.data.TreeStore',
	alias	: 'widget.s_navigationsStore',

    autoLoad	: false,
    autoSync	: false,
	
	storeId		: 's_navigations',
    
    proxy: {
        type: 'ajax',
        // url	: './assets/menu.json'
        url	: 'c_menus/getMenus',
		pageParam: false, //to remove param "page"
        startParam: false, //to remove param "start"
        limitParam: false, //to remove param "limit"
        noCache: false //to remove param "_dc"
    },
    
    constructor: function(){
    	this.callParent(arguments);
    }
   
});

/**
 * Class	: VE_pengarang
 * 
 * Table	: pengarang
 *  
 * @author Eko Junaidi Salam
 *
 */
 Ext.define('EJS.view.master.pengarang', {
	extend: 'Ext.tab.Panel',
	
	alias	: 'widget.pengarang',
	
	//title	: 'pengarang',
	margins: 0,
	tabPosition: 'right',
	activeTab: 0,
	
	initComponent: function(){
		Ext.apply(this, {
            items: [{
				xtype	: 'Listpengarang'
			}, {
				xtype: 'v_pengarang_form',
				disabled: true
			}]
        });
		this.callParent(arguments);
	}
	
});
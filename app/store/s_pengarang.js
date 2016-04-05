/**
 * Class	: CSE_pengarang
 * 
 * Table	: pengarang
 *  
 * @author Eko Junaidi Salam
 *
 */
 Ext.define('EJS.store.s_pengarang', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.pengarangStore',
	model	: 'EJS.model.m_pengarang',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'pengarang',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_pengarang/getAll',
			create	: 'c_pengarang/save',
			update	: 'c_pengarang/save',
			destroy	: 'c_pengarang/delete'
		},
		actionMethods: {
			read    : 'POST',
			create	: 'POST',
			update	: 'POST',
			destroy	: 'POST'
		},
		reader: {
			type            : 'json',
			root            : 'data',
			rootProperty    : 'data',
			successProperty : 'success',
			messageProperty : 'message'
		},
		writer: {
			type            : 'json',
			writeAllFields  : true,
			root            : 'data',
			encode          : true
		},
		listeners: {
			exception: function(proxy, response, operation){
				Ext.MessageBox.show({
					title: 'REMOTE EXCEPTION',
					msg: operation.getError(),
					icon: Ext.MessageBox.ERROR,
					buttons: Ext.Msg.OK
				});
			}
		}
	},
	
	constructor: function(){
		this.callParent(arguments);
	}
	
});
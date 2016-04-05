/**
 * Class	: CVE_pengarang
 * 
 * Table	: pengarang
 *  
 * @author Eko Junaidi Salam
 *
 */
 Ext.define('EJS.view.master.v_pengarang', {
	extend: 'Ext.grid.Panel',
	requires: ['EJS.store.s_pengarang'],
	
	title		: 'pengarang',
	itemId		: 'Listpengarang',
	alias       : 'widget.Listpengarang',
	store 		: 's_pengarang',
	columnLines : true,
	frame		: false,
	
	margin		: 0,
	selectedIndex : -1,
	
	initComponent: function(){
		var me = this;
		var plug = Ext.create('Ext.ux.ProgressBarPager');
		var docktool = Ext.create('Ext.toolbar.Paging', {
			store: 's_pengarang',
			dock: 'bottom',
			displayInfo: true,
            plugins: plug
		});		
		this.columns = [
			{
				header: 'id',filterable:true,
				dataIndex: 'id'
			},{
				header: 'nama',filterable:true,
				dataIndex: 'nama'
			},{
				header: 'email',filterable:true,
				dataIndex: 'email'
			},{
				header: 'telp',filterable:true,
				dataIndex: 'telp'
			},{
				header: 'foto',filterable:true,
				dataIndex: 'foto'
			}];
		this.features = [{
				ftype: 'filters',
				autoReload: true,
				encode: false,
				local: true
			}
		];
		this.dockedItems = [
			Ext.create('Ext.toolbar.Toolbar', {
				items: [{
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [{
						itemId	: 'btncreate',
						text	: 'Add',
						iconCls	: 'icon-add',
						action	: 'create'
					}, {
						xtype: 'splitter'
					}, {
						itemId	: 'btndelete',
						text	: 'Delete',
						iconCls	: 'icon-remove',
						action	: 'delete',
						disabled: true
					}]
				}, '-', {
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [{
						iconCls: 'icon-excel',
						itemId: 'export',
						text: 'Export',
						action	: 'export'
					},{
						xtype: 'splitter'
					},{
						itemId	: 'btnprint',
						text	: 'Cetak',
						iconCls	: 'icon-print',
						action	: 'print'
					}]
				}]
			}),docktool
		];
		
		docktool.add([{
				text: 'Clear Filter Data',
				handler: function () {
					me.filters.clearFilters();
				}
			}
		]);
		
		this.callParent(arguments);
		
		this.on('itemclick', this.gridSelection);
		this.getView().on('refresh', this.refreshSelection, this);
	},	
	
	gridSelection: function(me, record, item, index, e, eOpts){
		//me.getSelectionModel().select(index);
		this.selectedIndex = index;
		this.getView().saveScrollState();
	},
	
	refreshSelection: function() {
        this.getSelectionModel().select(this.selectedIndex);   /*Ext.defer(this.setScrollTop, 30, this, [this.getView().scrollState.top]);*/
    }

});
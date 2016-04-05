/**
 * Class	: CE_pengarang
 * 
 * Table	: pengarang
 *  
 * @author Eko Junaidi Salam
 *
 */
 Ext.define('EJS.controller.pengarang',{
	extend: 'Ext.app.Controller',
	views: ['master.v_pengarang','master.v_pengarang_form'],
	models: ['m_pengarang'],
	stores: ['s_pengarang'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listpengarang',
		selector: 'Listpengarang'
	}, {
		ref: 'v_pengarang_form',
		selector: 'v_pengarang_form'
	}, {
		ref: 'Savepengarang',
		selector: 'v_pengarang_form #save'
	}, {
		ref: 'Createpengarang',
		selector: 'v_pengarang_form #create'
	}, {
		ref: 'pengarang',
		selector: 'pengarang'
	}],


	init: function(){
		this.control({
			'pengarang': {
				'afterrender': this.pengarangAfterRender
			},
			'v_pengarang_form': {
				'afterlayout': this.pengarangAfterLayout
			},
			'Listpengarang': {
				'selectionchange': this.enableDelete,
				'itemdblclick': this.updateListpengarang
			},
			'Listpengarang button[action=create]': {
				click: this.createRecord
			},
			'Listpengarang button[action=delete]': {
				click: this.deleteRecord
			},
			'Listpengarang button[action=export]': {
				click: this.export2Excel
			},
			'Listpengarang button[action=print]': {
				click: this.printRecords
			},
			'v_pengarang_form button[action=save]': {
				click: this.saveV_pengarang_form
			},
			'v_pengarang_form button[action=create]': {
				click: this.saveV_pengarang_form
			},
			'v_pengarang_form button[action=cancel]': {
				click: this.cancelV_pengarang_form
			}
		});
	},
	
	pengarangAfterRender: function(){
		var pengarangStore = this.getListpengarang().getStore();
		pengarangStore.load();
		var view= this.getListpengarang().getView();
		var tip = Ext.create('Ext.tip.ToolTip', {
			target: view.el,
			delegate: view.itemSelector,
			trackMouse: true,
			renderTo: Ext.getBody(),
			listeners: {
				beforeshow: function updateTipBody(tip) {tip.update(' id : ' + view.getRecord(tip.triggerElement).get('id')+'<br />'+' nama : ' + view.getRecord(tip.triggerElement).get('nama')+'<br />'+' email : ' + view.getRecord(tip.triggerElement).get('email')+'<br />'+' telp : ' + view.getRecord(tip.triggerElement).get('telp')+'<br />'+' foto : ' + view.getRecord(tip.triggerElement).get('foto')+'<br />');}
			}
		});
	},
	
	pengarangAfterLayout: function(){this.getV_pengarang_form().down('#id_field').focus(false, true);
	},
	
	export2Excel: function(){
		var getstore = this.getListpengarang().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_pengarang/export2Excel',
			params: {data: jsonData},
			timeout: 600000,
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	createRecord: function(){
		var getListpengarang	= this.getListpengarang();
		var getV_pengarang_form= this.getV_pengarang_form(),
			form			= getV_pengarang_form.getForm();
		var getSavepengarang	= this.getSavepengarang();
		var getCreatepengarang	= this.getCreatepengarang();
		
		/* grid-panel */
		getListpengarang.setDisabled(true);
        
		/* form-panel */
		form.reset();
		getV_pengarang_form.down('#id_field').setReadOnly(false);
		getSavepengarang.setDisabled(true);
		getCreatepengarang.setDisabled(false);
		getV_pengarang_form.setDisabled(false);
		
		this.getPengarang().setActiveTab(getV_pengarang_form);		
	},
	
	enableDelete: function(dataview, selections){
		this.getListpengarang().down('#btndelete').setDisabled(!selections.length);
	},
	
	updateListpengarang: function(me, record, item, index, e){
		var getPengarang		= this.getPengarang();
		var getListpengarang	= this.getListpengarang();
		var getV_pengarang_form= this.getV_pengarang_form(),
			form			= getV_pengarang_form.getForm();
		var getSavepengarang	= this.getSavepengarang();
		var getCreatepengarang	= this.getCreatepengarang();
		
		getSavepengarang.setDisabled(false);
		getCreatepengarang.setDisabled(true);
		getV_pengarang_form.down('#id_field').setReadOnly(true);		
		getV_pengarang_form.loadRecord(record);
		
		getListpengarang.setDisabled(true);
		getV_pengarang_form.setDisabled(false);
		getPengarang.setActiveTab(getV_pengarang_form);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListpengarang().getStore();
		var selection = this.getListpengarang().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: "id" = "'+selection.data.id+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	printRecords: function(){
		var getstore = this.getListpengarang().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_pengarang/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/pengarang.html','pengarang_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
					break;
				default:
					Ext.MessageBox.show({
						title: 'Warning',
						msg: 'Unable to print the grid!',
						buttons: Ext.MessageBox.OK,
						animEl: 'save',
						icon: Ext.MessageBox.WARNING
					});
					break;
				}  
			}
		});
	},
	
	saveV_pengarang_form: function(){
		var getPengarang		= this.getPengarang();
		var getListpengarang 	= this.getListpengarang();
		var getV_pengarang_form= this.getV_pengarang_form(),
			form			= getV_pengarang_form.getForm(),
			values			= getV_pengarang_form.getValues();
		var store 			= this.getStore('s_pengarang');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_pengarang/save',
				params: {data: jsonData},
				success: function(response){
					store.reload({
						callback: function(){
							var newRecordIndex = store.findBy(
								function(record, id) {
									if (record.get('id') === values.id) {
										return true;
									}
									return false;
								}
							);
							/* getListpengarang.getView().select(recordIndex); */
							getListpengarang.getSelectionModel().select(newRecordIndex);
						}
					});
					
					getV_pengarang_form.setDisabled(true);
					getListpengarang.setDisabled(false);
					getPengarang.setActiveTab(getListpengarang);
				}
			});
		}
	},
	
	createV_pengarang_form: function(){
		var getPengarang		= this.getPengarang();
		var getListpengarang 	= this.getListpengarang();
		var getV_pengarang_form= this.getV_pengarang_form(),
			form			= getV_pengarang_form.getForm(),
			values			= getV_pengarang_form.getValues();
		var store 			= this.getStore('s_pengarang');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_pengarang/save',
				params: {data: jsonData},
				success: function(response){
					store.reload();
					
					getV_pengarang_form.setDisabled(true);
					getListpengarang.setDisabled(false);
					getPengarang.setActiveTab(getListpengarang);
				}
			});
		}
	},
	
	cancelV_pengarang_form: function(){
		var getPengarang		= this.getPengarang();
		var getListpengarang	= this.getListpengarang();
		var getV_pengarang_form= this.getV_pengarang_form(),
			form			= getV_pengarang_form.getForm();
			
		form.reset();
		getV_pengarang_form.setDisabled(true);
		getListpengarang.setDisabled(false);
		getPengarang.setActiveTab(getListpengarang);
	}
	
});
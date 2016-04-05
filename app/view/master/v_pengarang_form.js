/**
 * Class	: CFE_pengarang
 * 
 * Table	: pengarang
 *  
 * @author Eko Junaidi Salam
 *
 */
 Ext.define('EJS.view.master.v_pengarang_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_pengarang_form',
	
	//region:'east',
	//id: 'east-region-container',
	
	title		: 'Create/Update pengarang',
    bodyPadding	: 5,
    autoScroll	: true,
    
    initComponent: function(){
    	/*
		 * Deklarasi variable setiap field
		 */
		 
			var id_field = Ext.create('Ext.form.field.Number', {
				itemId: 'id_field',
				name: 'id', /* column name of table */
				fieldLabel: 'id',
				allowBlank: false /* jika primary_key */,
				maxLength: 11});
		var nama_field = Ext.create('Ext.form.field.Text', {
			name: 'nama',
			fieldLabel: 'nama',maxLength: 45
		});
		var email_field = Ext.create('Ext.form.field.Text', {
			name: 'email',
			fieldLabel: 'email',maxLength: 45
		});
		var telp_field = Ext.create('Ext.form.field.Text', {
			name: 'telp',
			fieldLabel: 'telp',maxLength: 15
		});
		var foto_field = Ext.create('Ext.form.field.Text', {
			name: 'foto',
			fieldLabel: 'foto',maxLength: 45
		});		
        Ext.apply(this, {
            fieldDefaults: {
                labelAlign: 'right',
                labelWidth: 120,
                msgTarget: 'qtip',
				anchor: '100%'
            },
			defaultType: 'textfield',
            items: [id_field,nama_field,email_field,telp_field,foto_field],
			
	        buttons: [{
                iconCls: 'icon-save',
                itemId: 'save',
                text: 'Save',
                disabled: true,
                action: 'save'
            }, {
                iconCls: 'icon-add',
				itemId: 'create',
                text: 'Create',
                action: 'create'
            }, {
                iconCls: 'icon-reset',
                text: 'Cancel',
                action: 'cancel'
            }]
        });
        
        this.callParent();
    }
});
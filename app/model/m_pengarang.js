/**
 * Class	: CME_pengarang
 * 
 * Table	: pengarang
 *  
 * @author Eko Junaidi Salam
 *
 */
 Ext.define('EJS.model.m_pengarang', {
	extend: 'Ext.data.Model',
	alias		: 'widget.pengarangModel',
	fields		: [{name: 'id',type:'int'},'nama','email','telp','foto'],
	idProperty	: 'id'	
});
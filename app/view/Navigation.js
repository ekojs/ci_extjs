Ext.define('EJS.view.Navigation', {
					extend: 'Ext.tree.Panel',
					xtype: 'navigation',
					title: 'List Menu',
					rootVisible: false,
					lines: false,
					useArrows: true,					
					root: {
						expanded: true,
						children: [{"text":"CONTOH","expanded":true,"children":[{"id":"menu","text":"Contoh Menu","leaf":true}]}]
					}
				});
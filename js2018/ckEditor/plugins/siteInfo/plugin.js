/**
 * サイト情報
 * 
 */

 CKEDITOR.plugins.add( 'siteinfo', {
	 requires: "richcombo",
	 init: function( editor ) {
		 var config = editor.config;

		 for(var values = config.siteinfo_value.split(';'), idx = 0, itemHash = {}, f = {}, k = new CKEDITOR.style(config.siteinfo_style); idx < values.length; idx++) {
			 var items = values[idx].split("::");
			 var text = items[0];

			 itemHash[text] = items[1];
			 f[text] = new CKEDITOR.style(config.siteinfo_style);
			 f[text]._.definition.name = text;
		 }

		 var title = '';
		 if (config.siteinfo_title) {
			 title = config.siteinfo_title;
		 } else {
			 title = editor.lang.linklist.label;
		 }

		 editor.ui.addRichCombo('siteInfoCmb', {
			 label: title,
			 title: title,
			 toolbar: "styles," + "300",
			 allowedContent: k,
			 requiredContent: k,
			 panel: {
				 css: [CKEDITOR.skin.getPath("editor")].concat(editor.config.contentsCss),
				 multiSelect: false
			 }, 

			 
			 init: function() {
				 for(var item in itemHash) {
					 this.add(item);
				 }
			 },

			 onClick: function(s) {
				 var insertVal;

				if(s == '電話番号表示') {
					// 電話番号表示
					 insertVal = itemHash[s];
				} else {
					// 架電
					 insertVal = '<a href="tel:' + itemHash[s] + '">' + s + '</a>';
				}
				 editor.insertHtml(insertVal);
			 },

			 onRender: function() {
			 },

			 refresh: function() {
				 editor.activeFilter.check(k) || this.setState(CKEDITOR.TRISTATE_DISABLED);
			 }
		 });
	 }
 });

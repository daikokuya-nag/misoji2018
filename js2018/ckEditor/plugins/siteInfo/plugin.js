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

					// 選択範囲の取得
					// 現在の設定を取得
					var selection  = editor.getSelection();
					var element    = selection.getStartElement();
					var selectText = '';

					// 選択しているテキストの取得
					if( selection.getNative() ){
						selectText = selection.getNative();
					} else{
						// 上記で取得できない（IEなど）の場合は下記から取得
						selectText = selection._.cache.selectedText;
					}

								//insertVal = '<a href="tel:' + itemHash[s] + '">' + s + '</a>';
					insertVal = '<a href="tel:' + itemHash[s] + '">' + selectText + '</a>';
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

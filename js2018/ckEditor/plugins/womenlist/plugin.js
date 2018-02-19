/**
 * 女性リスト
 * 
 */

 CKEDITOR.plugins.add( 'womenlist', {
	requires: "richcombo",
	init: function( editor ) {
		var config = editor.config;

		for(var values = config.womenlist_value.split(';'), idx = 0, itemHash = {}, f = {}, k = new CKEDITOR.style(config.womenlist_style); idx < values.length; idx++) {
			var items = values[idx].split("::");
			var text = items[0];

			itemHash[text] = items[1];
			f[text] = new CKEDITOR.style(config.womenlist_style);
			f[text]._.definition.name = text;
		}
		var title = '';
		if (config.womenlist_title) {
			title = config.womenlist_title;
		} else {
			title = editor.lang.linklist.label;
		}

		editor.ui.addRichCombo('womenlistCmb', {
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

				var imgUrl    = itemHash[s];
								//var insertVal = '<a href="' + imgUrl + '">' + s + '</a>';
				var insertVal = '<a href="' + imgUrl + '">' + selectText + '</a>';
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

CKEDITOR.dialog.add( 'hyperlinkDialog', function( editor ) {
    return {
        title: '外部リンク指定',
        minWidth  : 500 ,
        minHeight : 200 ,
        contents  : [
            {
                id       : 'tab-basic'      ,
                label    : 'Basic Settings' ,
                elements : [
                    {
                        type  : 'text'      ,
                        id    : 'hyperLink' ,
                        label : 'リンク先'  ,
                        validate : CKEDITOR.dialog.validate.notEmpty( "リンク先を入力してください" )
                    }
                ]
            }
        ] ,

        onOk : function() {

            // 選択範囲の取得
            // 現在の設定を取得
            var editor     = this.getParentEditor();
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

            var url  = this.getValueOf( 'tab-basic', 'hyperLink' ) ;
//            var text = this.getValueOf( 'tab-basic', 'title' ) ;

            text = '<a href="' + url + '">' + selectText + '</a>';
            editor.insertHtml(text);
        }
    };
});

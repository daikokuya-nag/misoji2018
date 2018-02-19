/**
 * サイト情報
 * 
 */

 CKEDITOR.plugins.add( 'hyperlink', {
    icons : 'hyperlink' ,
    label : 'リンク' ,

    init: function( editor ) {
        editor.addCommand( 'hyperlink', new CKEDITOR.dialogCommand( 'hyperlinkDialog' ) );
        editor.ui.addButton( 'hyperlink', {
            toolbar: 'insert' ,
        });

        CKEDITOR.dialog.add( 'hyperlinkDialog', this.path + 'dialogs/hyperlink.js' );
    }
 });





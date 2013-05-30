jQuery(document).ready(function(){
	
	var CMB_Code_Editor = {

		init : function( el ) {
			
			var t = this;

			t.el        = el;
			t.container = el.find( 'pre' ).get(0);
			t.field     = el.find( 'textarea' ).get(0);
			t.theme     = el.attr( 'data-theme' );
			t.mode      = el.attr( 'data-mode' );

			t.createEd();

		},

		createEd : function() {

			var t = this;

			t.editor = ace.edit( t.container );
			
			t.editor.setTheme("ace/theme/" + t.theme );
			t.editor.getSession().setMode("ace/mode/" + t.mode );
			t.editor.setShowPrintMargin(false);
			t.editor.getSession().setUseWrapMode(true);

			// Onload - insert value from hidden field & clear selection.
			t.editor.setValue( jQuery( t.field ).val() );
			t.editor.clearSelection();

			// When changing the editor - update hidden field.
			t.editor.getSession().on('change', function(e) {
			    jQuery( t.field ).val( t.editor.getValue() );
			});

		}

	}
	
	jQuery( ".cmb-code-editor" ).each( function() {
		CMB_Code_Editor.init( jQuery(this) );	
	} );
	

});
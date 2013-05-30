<?php

define( 'CMB_CODE_PATH', str_replace( '\\', '/', dirname( __FILE__ ) ) );
define( 'CMB_CODE_URL', str_replace( str_replace( '\\', '/', WP_CONTENT_DIR ), str_replace( '\\', '/', WP_CONTENT_URL ), CMB_CODE_PATH ) );

/*
Plugin Name: CMB Code Editor
*/
	
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
add_filter( 'cmb_meta_boxes', function ( array $meta_boxes ) {

	$meta_boxes[] = array(
		'title' => 'CMB Test - all fields',
		'pages' => 'post',
		'fields' => array(
			array( 'id' => 'field-1',  'name' => 'Text input field', 'type' => 'code_editor' ),
		)
	);
	
	return $meta_boxes;

} );

add_filter( 'cmb_field_types', function($classes) {
	
	return array_merge( $classes, array( 'code_editor' => 'CMB_Code_Editor', ) );	

} );

add_action( 'plugins_loaded', function() {
	
	class CMB_Code_Editor extends CMB_Field {

		public $unique_id;

		public function __construct( $name, $title, array $values, $args = array() ) {	

			$this->unique_id = uniqid();

			add_action( 'admin_head', array( $this, 'admin_head' ) );
			add_action( 'admin_footer', array( $this, 'admin_footer' ) );
			parent::__construct( $name, $title, $values, $args );

		}

		function admin_head() {

			?>

			<style>
				.cmb-code-editor { 
					position: relative;
					width: 100%;
					height: 200px;
					border: 1px solid #DFDFDF;
					border-radius: 2px;
					padding: 0 0 10px 0;
					resize:vertical;
					overflow:auto;
					background: #EAEAEA;
				}
				.cmb-code-editor pre {
					position: absolute;
					top: 0;
					right: 0;
					bottom: 0;
					left: 0;
					margin: 0 0 20px;
					font-family: Consolas,Monaco,monospace;
					line-height: 150%;
					resize:none;
					border-bottom: 1px solid #DFDFDF;
				}
				.cmb-code-editor::after{
					content: 'HTML Editor';
					display: block;
					position: absolute;
					left: 7px;
					bottom: -1px;
					line-height: 20px;
					font-size: 11px;
					vertical-align: middle;
					color: #777;
				}
					
				.cmb-code-editor-field {
					display: none;
				}
			</style>

			<?php
		}

		function admin_footer() {

			?>

			<script>
					jQuery(document).ready(function(){
						
						var myEditor = ace.edit( "<?php echo $this->get_the_id_attr( '-editor' ); ?>" );
						var myField  = jQuery('#<?php echo $this->get_the_id_attr(); ?>');

						myEditor.setTheme("ace/theme/github");
						myEditor.getSession().setMode("ace/mode/php");
						myEditor.setShowPrintMargin(false);
						myEditor.getSession().setUseWrapMode(true);

						myEditor.setValue( myField.val() );
						myEditor.clearSelection();

						myEditor.getSession().on('change', function(e) {
						    myField.val( myEditor.getValue() );
						});myEditor

						// Remove this - not v.efficient?
						window.setInterval(function() {
							myEditor.resize();
						}, 100 );

					});
			</script>

			<?php
		}

		function enqueue_scripts() {

			parent::enqueue_scripts();
			wp_enqueue_script( 'cmb-code-ace', CMB_CODE_URL . '/ace-builds/src-noconflict/ace.js', null, true );

		}

		public function html() { ?>

			<textarea <?php $this->id_attr(); ?> <?php $this->boolean_attr(); ?> <?php $this->class_attr( 'cmb-code-editor-field' ); ?> rows="<?php echo ! empty( $this->args['rows'] ) ? esc_attr( $this->args['rows'] ) : 4; ?>" <?php $this->name_attr(); ?>><?php echo esc_html( $this->value ); ?></textarea>

			<div class="cmb-code-editor">
				<pre <?php $this->id_attr('-editor'); ?> <?php $this->class_attr(); ?>><?php echo esc_html( $this->value ); ?></pre>
			</div>

		<?php }

	}
} );

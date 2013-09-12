<?php
/*
Plugin Name: CMB Code Editor
*/
	

define( 'CMB_CODE_PATH', str_replace( '\\', '/', dirname( __FILE__ ) ) );
define( 'CMB_CODE_URL', str_replace( str_replace( '\\', '/', WP_CONTENT_DIR ), str_replace( '\\', '/', WP_CONTENT_URL ), CMB_CODE_PATH ) );

add_filter( 'cmb_field_types', 'cmb_code_editor_register' );
add_filter( 'plugins_loaded', 'cmb_code_editor_define_class' );

function cmb_code_editor_register ($classes) {
	
	return array_merge( $classes, array( 'code_editor' => 'CMB_Code_Editor', ) );	

}

function cmb_code_editor_define_class() {
	
	class CMB_Code_Editor extends CMB_Field {

		function enqueue_scripts() {

			parent::enqueue_scripts();
			wp_enqueue_script( 'cmb-code-editor-ace', CMB_CODE_URL . '/ace-editor/src-min/ace.js', null, true );
			wp_enqueue_script( 'cmb-code-editor', CMB_CODE_URL . '/plugin.js', null, true );
			
			wp_enqueue_style( 'cmb-code-editor', CMB_CODE_URL . '/plugin.css', null );

		}

		public function html() { 

			$defaults = array(
				'editor_theme'  => 'cloud',
				'editor_mode'   => 'html', // language - used to determine syntax highlighting etc.
				'editor_height' => 200
			);

			$this->args = wp_parse_args( $this->args, $defaults );

			?>

			<div class="cmb-code-editor" data-mode="<?php echo esc_attr( $this->args['editor_mode'] ); ?>" data-theme="<?php echo esc_attr( $this->args['editor_theme'] ); ?>">

				<textarea <?php $this->id_attr(); ?> <?php $this->boolean_attr(); ?> <?php $this->class_attr( 'cmb-code-editor-field' ); ?> rows="<?php echo ! empty( $this->args['rows'] ) ? esc_attr( $this->args['rows'] ) : 4; ?>" <?php $this->name_attr(); ?>><?php echo esc_html( $this->value ); ?></textarea>

				<div class="cmb-code-editor-container" style="height: <?php echo esc_attr( $this->args['editor_height'] ); ?>px;" data-description="<?php echo strtoupper( esc_attr( $this->args['editor_mode'] ) ); ?> Editor">
					<pre></pre>
				</div>

			</div>

		<?php }

	}

}

function cmb_code_editor_example_field ( array $meta_boxes ) {

	$meta_boxes[] = array(
		'title' => 'CMB Test - all fields',
		'pages' => 'post',
		'fields' => array(
			array( 'id' => 'field-1',  'name' => 'Text input field', 'type' => 'code_editor', 'editor_mode' => 'php', 'editor_theme' => 'twilight' ),
			array( 'id' => 'field-2',  'name' => 'Text input field', 'type' => 'code_editor', 'editor_mode' => 'javascript', 'editor_height' => 400 ),
		)
	);
	
	return $meta_boxes;

}
//add_filter( 'cmb_meta_boxes', 'cmb_code_editor_example_field' );

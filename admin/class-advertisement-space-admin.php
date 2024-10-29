<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       chandan
 * @since      1.0.0
 *
 * @package    Advertisement_Space
 * @subpackage Advertisement_Space/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Advertisement_Space
 * @subpackage Advertisement_Space/admin
 * @author     Chandan <chandanaug13@gmail.com>
 */
class Advertisement_Space_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Advertisement_Space_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Advertisement_Space_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/advertisement-space-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Advertisement_Space_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Advertisement_Space_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/advertisement-space-admin.js', array( 'jquery' ), $this->version, false );

	}
	 // Register Custom Post Type
     function advertisement_space() {
	$args = [
		'label'  => esc_html__( 'Advertisements', 'text-domain' ),
		'labels' => [
			'menu_name'          => esc_html__( 'Advertisements', 'your-textdomain' ),
			'name_admin_bar'     => esc_html__( 'Advertisement', 'your-textdomain' ),
			'add_new'            => esc_html__( 'Add Advertisement', 'your-textdomain' ),
			'add_new_item'       => esc_html__( 'Add new Advertisement', 'your-textdomain' ),
			'new_item'           => esc_html__( 'New Advertisement', 'your-textdomain' ),
			'edit_item'          => esc_html__( 'Edit Advertisement', 'your-textdomain' ),
			'view_item'          => esc_html__( 'View Advertisement', 'your-textdomain' ),
			'update_item'        => esc_html__( 'View Advertisement', 'your-textdomain' ),
			'all_items'          => esc_html__( 'All Advertisements', 'your-textdomain' ),
			'search_items'       => esc_html__( 'Search Advertisements', 'your-textdomain' ),
			'parent_item_colon'  => esc_html__( 'Parent Advertisement', 'your-textdomain' ),
			'not_found'          => esc_html__( 'No Advertisements found', 'your-textdomain' ),
			'not_found_in_trash' => esc_html__( 'No Advertisements found in Trash', 'your-textdomain' ),
			'name'               => esc_html__( 'Advertisements', 'your-textdomain' ),
			'singular_name'      => esc_html__( 'Advertisement', 'your-textdomain' ),
			],
			'public'              => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'show_in_rest'        => false,
			'capability_type'     => 'post',
			'hierarchical'        => true,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite_no_front'    => false,
			'show_in_menu'        => true,
			'menu_icon'           => 'dashicons-analytics',
			'supports' => [
				'title',
				'thumbnail',
				'custom-fields',
			],
			
			'rewrite' => true
		];

		register_post_type( 'advertisement', $args );
	}

	private $screens = array(
		'advertisement',
	);
	private $fields = array(
		array(
			'id' => 'advertisement-img',
			'label' => 'Advertisement',
			'type' => 'media',
		),
		array(
			'id' => 'advertisements-url',
			'label' => 'Advertisements Url',
			'type' => 'url',
		),
		array(
			'id' => 'enable-advertisement',
			'label' => 'Enable Advertisement',
			'type' => 'checkbox',
		),
		array(
			'id' => 'start-date',
			'label' => 'Start Date',
			'type' => 'date',
		),
		array(
			'id' => 'end-date',
			'label' => 'End Date',
			'type' => 'date',
		),
	);

	/**
	 * Hooks into WordPress' add_meta_boxes function.
	 * Goes through screens (post types) and adds the meta box.
	 */
	public function add_meta_boxes() {
		foreach ( $this->screens as $screen ) {
			add_meta_box(
				'advertisement',
				__( 'Advertisement', 'text-domain' ),
				array( $this, 'add_meta_box_callback' ),
				$screen,
				'normal',
				'default'
			);
		}
	}

	/**
	 * Generates the HTML for the meta box
	 * 
	 * @param object $post WordPress post object
	 */
	public function add_meta_box_callback( $post ) {
		wp_nonce_field( 'advertisement_data', 'advertisement_nonce' );
		$this->generate_fields( $post );
	}

	/**
	 * Hooks into WordPress' admin_footer function.
	 * Adds scripts for media uploader.
	 */
	public function admin_footer() {
		?><script>
			// https://codestag.com/how-to-use-wordpress-3-5-media-uploader-in-theme-options/
			jQuery(document).ready(function($){
				if ( typeof wp.media !== 'undefined' ) {
					var _custom_media = true,
					_orig_send_attachment = wp.media.editor.send.attachment;
					$('.rational-metabox-media').click(function(e) {
						var send_attachment_bkp = wp.media.editor.send.attachment;
						var button = $(this);
						var id = button.attr('id').replace('_button', '');
						console.log(id);
						_custom_media = true;
							wp.media.editor.send.attachment = function(props, attachment){
							if ( _custom_media ) {
								$("#"+id).val(attachment.url);
								$("#advtImg").attr('src',attachment.url);
							} else {
								return _orig_send_attachment.apply( this, [props, attachment] );
							};
						}
						wp.media.editor.open(button);
						return false;
					});
					$('.add_media').on('click', function(){
						_custom_media = false;
					});
				}
			});
		</script><?php
	}

	/**
	 * Generates the field's HTML for the meta box.
	 */
	public function generate_fields( $post ) {
		$output = '';
		foreach ( $this->fields as $field ) {
			$label = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
			$db_value = get_post_meta( $post->ID, 'advertisement_' . $field['id'], true );
			switch ( $field['type'] ) {
				case 'checkbox':
					$input = sprintf(
						'<input %s id="%s" name="%s" type="checkbox" value="1">',
						$db_value === '1' ? 'checked' : '',
						$field['id'],
						$field['id']
					);
					break;
				case 'media':
					$input = '<img id="advtImg" src="'.$db_value.'" style="width: 15%;display: block;margin-bottom: 12px;">';
					$input .= sprintf(
						'<input class="regular-text" id="%s" name="%s" type="text" value="%s"> <input class="button rational-metabox-media" id="%s_button" name="%s_button" type="button" value="Upload" />',
						$field['id'],
						$field['id'],
						$db_value,
						$field['id'],
						$field['id']
					);
					break;
				default:
					$input = sprintf(
						'<input %s id="%s" name="%s" type="%s" value="%s">',
						$field['type'] !== 'color' ? 'class="regular-text"' : '',
						$field['id'],
						$field['id'],
						$field['type'],
						$db_value
					);
			}
			$output .= $this->row_format( $label, $input );
		}
		echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
	}

	/**
	 * Generates the HTML for table rows.
	 */
	public function row_format( $label, $input ) {
		return sprintf(
			'<tr><th scope="row">%s</th><td>%s</td></tr>',
			$label,
			$input
		);
	}
	/**
	 * Hooks into WordPress' save_post function
	 */
	public function save_post( $post_id ) {
		if ( ! isset( $_POST['advertisement_nonce'] ) )
			return $post_id;

		$nonce = $_POST['advertisement_nonce'];
		if ( !wp_verify_nonce( $nonce, 'advertisement_data' ) )
			return $post_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;
		foreach ( $this->fields as $field ) {
			if ( isset( $_POST[ $field['id'] ] ) ) {
				switch ( $field['type'] ) {
					// case 'email':
					// 	update_post_meta( $post_id, 'advertisement_' . $field['id'], sanitize_text_field( $_POST[ $field['id'] ] ) );
					// 	break;
					case 'text':
						update_post_meta( $post_id, 'advertisement_' . $field['id'],sanitize_text_field( $_POST[ $field['id'] ] ) );
						break;
					case 'date':
						update_post_meta( $post_id, 'advertisement_' . $field['id'],sanitize_text_field( $_POST[ $field['id'] ] ) );
						break;
					case 'url':
						update_post_meta( $post_id, 'advertisement_' . $field['id'],sanitize_url( $_POST[ $field['id'] ] ) );
						break;
					case 'media':
						update_post_meta( $post_id, 'advertisement_' . $field['id'],sanitize_url( $_POST[ $field['id'] ] ) );
						break;
					case 'checkbox':
						update_post_meta( $post_id, 'advertisement_' . $field['id'],sanitize_text_field( $_POST[ $field['id'] ] ) );
						break;
				}
				
			} else if ( $field['type'] === 'checkbox' ) {
				update_post_meta( $post_id, 'advertisement_' . $field['id'], '0' );
			}
		}
	}

	public function advertisement_filter_posts_columns($columns)
	{
		$columns = array(
	      'cb' => $columns['cb'],
	      'image' => __( 'Image' ),
	      'title' => __( 'Title' ),
	      'active' => __( 'Status', 'advertisement' ),
	      'expire' => __( 'Expiring', 'advertisement' ),
	    );
	  return $columns;

  
	}

	public function advertisement_column($column, $post_id)
	{
		 if ( 'image' === $column ) {
	    	$image = get_post_meta( $post_id, 'advertisement_advertisement-img', true );
	    	 if ( ! $image ) {
			      _e( 'n/a' );  
		    } else {
		      echo '<img src="'.$image.'" style="width: 50%;"/>';
		    }
	  	}
	  	if ( 'active' === $column ) {
	    	$status = get_post_meta( $post_id, 'advertisement_enable-advertisement', true );
	    	 if ( ! $status ) {
			      echo "Inactive";
		    } else {
		      if ($status == '1') {
		      	echo "Active";
		      } else {
		      	echo "Inactive";
		      }
		      
		    }
	  	}
	  	if ( 'expire' === $column ) {
	    	$expire = get_post_meta( $post_id, 'advertisement_end-date', true );
	    	 if ( ! $expire ) {
			      _e( 'n/a' );  
		    } else {
		      echo $expire;
		    }
	  	}
	}


	public function add_advertisement_form_meta_box() {
	    add_meta_box(
	    	'contact-form-meta-box-id',
	     	'Advertisement analytics',
	     	array( $this, 'contact_form_meta_box' ),
	      	'advertisement', 
	      	'side', 
	      	'low');
	}

	public function contact_form_meta_box($post) {
	    $viewMeta = get_post_meta( $post->ID, 'advertisement_total_view', true );
    	if ( ! empty($viewMeta) ) {
		      $views = $viewMeta;  
	    } else {
	      $views =  0;
	    }
	    $clickMeta = get_post_meta( $post->ID, 'advertisement_total_click', true );
    	 if ( ! empty($clickMeta) ) {
		     $click =  $clickMeta;  
	    } else {
	      $click = 0;
	    }

	    ?>
	    	<table>
			    <tr>
			    	<th>Total Clicks: </th>
			    	<td><?php echo $click; ?></td>
			    </tr>
			    <tr>
			    	<th>Total View</th>
			    	<td><?php echo $views; ?></td>
			    </tr>
			</table>
	    <?php
	}
	public function advertisment_add_meta_box() {
		add_meta_box(
			'advertisment-advertisment',
			__( 'Advertisment Type', 'advertisment' ),
			array($this,'advertisment_html'),
			'advertisement',
			'normal',
			'default'
		);
	}
	public function advertisment_html( $post) {
		wp_nonce_field( '_advertisment_nonce', 'advertisment_nonce' ); ?>
	
		<p>
	
			<input type="radio" name="advertisment_advertisement_type" id="advertisment_advertisement_type_0" value="popup" <?php echo ( $this->advertisment_get_meta( 'advertisment_advertisement_type' ) === 'popup' ) ? 'checked' : ''; ?>>
	<label for="advertisment_advertisement_type_0">Flash ads</label><br>
	
			<input type="radio" name="advertisment_advertisement_type" id="advertisment_advertisement_type_1" value="header_large" <?php echo ( $this->advertisment_get_meta( 'advertisment_advertisement_type' ) === 'header_large' ) ? 'checked' : ''; ?>>
	<label for="advertisment_advertisement_type_1">header large</label><br>
	
			<input type="radio" name="advertisment_advertisement_type" id="advertisment_advertisement_type_2" value="header_small" <?php echo ( $this->advertisment_get_meta( 'advertisment_advertisement_type' ) === 'header_small' ) ? 'checked' : ''; ?>>
	<label for="advertisment_advertisement_type_2">header small</label><br>
		</p><?php
	}

	public function advertisment_get_meta( $value ) {
		global $post;
	
		$field = get_post_meta( $post->ID, $value, true );
		if ( ! empty( $field ) ) {
			return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
		} else {
			return false;
		}
	}
	public function advertisment_save( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( ! isset( $_POST['advertisment_nonce'] ) || ! wp_verify_nonce( $_POST['advertisment_nonce'], '_advertisment_nonce' ) ) return;
		if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	
		if ( isset( $_POST['advertisment_advertisement_type'] ) )
			update_post_meta( $post_id, 'advertisment_advertisement_type', sanitize_text_field( $_POST['advertisment_advertisement_type'] ) );
	}

}

<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       chandan
 * @since      1.0.0
 *
 * @package    Advertisement_Space
 * @subpackage Advertisement_Space/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Advertisement_Space
 * @subpackage Advertisement_Space/public
 * @author     Chandan <chandanaug13@gmail.com>
 */
class Advertisement_Space_Public {

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
	 * Array with post type objects (ads)
	 */
	private $ads = array();

	/**
	 * Array with post type objects (ads)
	 */
	private $largeAds = array();


	/**
	 * Array with post type objects (ads)
	 */
	private $smallAds = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/advertisement-space-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/advertisement-space-public.js', array( 'jquery' ), $this->version, false );

		$nonce = wp_create_nonce( 'advertisement_space' );
	    wp_localize_script( 'advertisement-space', 'my_ajax_obj', array(
	       'ajax_url' => admin_url( 'admin-ajax.php' ),
	        'nonce'    => $nonce,
	    ) );

	}

	/**
	 * Return all ads from this group
	 *
	 * @since 1.0.0
	 */
	public function get_all_ads() {
		if ( count( $this->ads ) > 0 ) {
			return $this->ads; }
		else {
			return $this->load_all_ads(); }
	}
	
	/**
	 * Use post ids as keys for ad array
	 *
	 * @since 1.0.0
	 * @param arr $ads array with post objects
	 * @return arr $ads array with post objects with post id as their key
	 * @todo check, if there isn’t a WP function for this already
	 */
	private function add_post_ids(array $ads){

		$ads_with_id = array();
		foreach ( $ads as $_ad ){
			$ads_with_id[$_ad->ID] = $_ad;
		}

		return $ads_with_id;
	}

	/**
	 * Load all public ads for this group
	 *
	 * @since 1.0.0
	 * @update 1.1.0 load only public ads
	 * @update allow to cache groups for few minutes
	 * @return arr $ads array with ad (post) objects
	 */
	private function load_all_ads() {

		$this->ads = array();

		$args = array(
			'post_type' => 'advertisement',
			'post_status' => 'publish',
			'order' => 'ASC',
			'orderby' => 'ID',
			'posts_per_page' => -1,
			'meta_query' => array(
				'0' => array(
					'key' => 'advertisement_enable-advertisement',
					'value' => '1',
					'compare' => '=',
				),
				'1' => array(
					'key' => 'advertisement_start-date',
					'value' => date("Y-m-d"),
					'compare' => '<=',
				),
				'2' => array(
					'key' => 'advertisement_end-date',
					'value' => date("Y-m-d"),
					'compare' => '>=',
				),
				'3' => array(
					'key' => 'advertisment_advertisement_type',
					'value' => 'popup',
					'compare' => '=',
				),
				'relation' => 'AND',
			),
		);

		$found = false;
		$key = 'advertisement_space';
		$ads = wp_cache_get( $key,'ads-space', false, $found );
		if ( $found ) {
			$this->ads = $ads;
		} else {
			$ads = new WP_Query( $args );

			if ( $ads->have_posts() ) {
				$this->ads = $this->add_post_ids( $ads->posts );
			}
			wp_cache_set( $key, $this->ads,'ads-space', 720);
		}

		return $this->ads;
	}


	/**
	 * Load all public ads for this group
	 *
	 * @since 1.0.0
	 * @update 1.1.0 load only public ads
	 * @update allow to cache groups for few minutes
	 * @return arr $ads array with ad (post) objects
	 */
	private function load_large_ads() {

		$this->largeAds = array();

		$args = array(
			'post_type' => 'advertisement',
			'post_status' => 'publish',
			'order' => 'ASC',
			'orderby' => 'ID',
			'posts_per_page' => -1,
			'meta_query' => array(
				'0' => array(
					'key' => 'advertisement_enable-advertisement',
					'value' => '1',
					'compare' => '=',
				),
				'1' => array(
					'key' => 'advertisement_start-date',
					'value' => date("Y-m-d"),
					'compare' => '<=',
				),
				'2' => array(
					'key' => 'advertisement_end-date',
					'value' => date("Y-m-d"),
					'compare' => '>=',
				),
				'3' => array(
					'key' => 'advertisment_advertisement_type',
					'value' => 'header_large',
					'compare' => '=',
				),
				'relation' => 'AND',
			),
		);

		$found = false;
		$key = 'advertisement_space_large';
		$ads = wp_cache_get( $key,'ads-space-large', false, $found );
		if ( $found ) {
			$this->largeAds = $ads;
		} else {
			$ads = new WP_Query( $args );

			if ( $ads->have_posts() ) {
				$this->largeAds = $this->add_post_ids( $ads->posts );
			}
			wp_cache_set( $key, $this->ads,'ads-space-large', 720);
		}

		return $this->largeAds;
	}
	/**
	 * Load all public ads for this group
	 *
	 * @since 1.0.0
	 * @update 1.1.0 load only public ads
	 * @update allow to cache groups for few minutes
	 * @return arr $ads array with ad (post) objects
	 */
	private function load_small_ads() {

		$this->smallAds = array();

		$args = array(
			'post_type' => 'advertisement',
			'post_status' => 'publish',
			'order' => 'ASC',
			'orderby' => 'ID',
			'posts_per_page' => -1,
			'meta_query' => array(
				'0' => array(
					'key' => 'advertisement_enable-advertisement',
					'value' => '1',
					'compare' => '=',
				),
				'1' => array(
					'key' => 'advertisement_start-date',
					'value' => date("Y-m-d"),
					'compare' => '<=',
				),
				'2' => array(
					'key' => 'advertisement_end-date',
					'value' => date("Y-m-d"),
					'compare' => '>=',
				),
				'3' => array(
					'key' => 'advertisment_advertisement_type',
					'value' => 'header_small',
					'compare' => '=',
				),
				'relation' => 'AND',
			),
		);

		$found = false;
		$key = 'advertisement_space_small';
		$ads = wp_cache_get( $key,'ads-space-small', false, $found );
		if ( $found ) {
			$this->smallAds = $ads;
		} else {
			$ads = new WP_Query( $args );

			if ( $ads->have_posts() ) {
				$this->smallAds = $this->add_post_ids( $ads->posts );
			}
			wp_cache_set( $key, $this->ads,'ads-space-small', 720);
		}

		return $this->smallAds;
	}

	public function showAdvt(){
		$largeads = $this->load_large_ads();
		$randAdv = $largeads[array_rand($largeads)];
		$img = get_post_meta($randAdv->ID,'advertisement_advertisement-img',true);
		$url = get_post_meta($randAdv->ID,'advertisement_advertisements-url',true);
		$viewMeta = get_post_meta( $randAdv->ID, 'advertisement_total_view', true );
		if (!empty($viewMeta)) {
			$view = $viewMeta + 1;
		}else{
			$view = 1;
		}

		$smallads = $this->load_small_ads();
		$randAdv_small = $smallads[array_rand($smallads)];
		$img_small = get_post_meta($randAdv_small->ID,'advertisement_advertisement-img',true);
		$url_small = get_post_meta($randAdv_small->ID,'advertisement_advertisements-url',true);
		$viewMeta_small = get_post_meta( $randAdv_small->ID, 'advertisement_total_view', true );
		if (!empty($viewMeta_small)) {
			$view_small= $viewMeta_small + 1;
		}else{
			$view_small = 1;
		}
		//update_post_meta( $randAdv->ID, 'advertisement_total_view', $view );
	    ?>
			<div class=" advHeader row">
				<a id="advClick"  data-url ="<?php echo esc_url($url) ; ?>" data-id ="<?php echo $randAdv->ID ; ?>">
					<img class="advertisement-content" id="advImg1 img01" src="<?php echo $img ;?>">
				</a>
				<a id="advClick"  data-url ="<?php echo esc_url($url_small) ; ?>" data-id ="<?php echo $randAdv_small->ID ; ?>">
					<img class="advertisement-content" id="iadvImg2 mg01" src="<?php echo $img_small ;?>">
				</a>
			</div>
	    <?php 
	}
	public function randAdv ()
	{
		$ads = $this->get_all_ads();
		$randAdv = $ads[array_rand($ads)];
		$img = get_post_meta($randAdv->ID,'advertisement_advertisement-img',true);
		$url = get_post_meta($randAdv->ID,'advertisement_advertisements-url',true);
		echo "string".$url." ".$img;
	}
	public function advertisement_space_popup() {

		$ads = $this->get_all_ads();
		$randAdv = $ads[array_rand($ads)];
		$img = get_post_meta($randAdv->ID,'advertisement_advertisement-img',true);
		$url = get_post_meta($randAdv->ID,'advertisement_advertisements-url',true);
		$viewMeta = get_post_meta( $randAdv->ID, 'advertisement_total_view', true );
		if (!empty($viewMeta)) {
			$view = $viewMeta + 1;
		}else{
			$view = 1;
		}

		
	    if(1) { ?>
	    	<?php update_post_meta( $randAdv->ID, 'advertisement_total_view', $view ); ?>
			<div class="modal fade" id="advtModel" role="dialog" tabindex="-1" role="dialog" style="display: block;opacity:1">
			    <div class="modal-dialog">
			      <div class="modal-content" style="margin-top: 2%;">
			        <div class="modal-body" style="padding:0px;">
			        	 <button type="button" class="close" data-dismiss="modal">&times;<span id='autoClose'>(0:0)</span></button>
			          <a id="advClick"  data-url ="<?php echo esc_url($url) ; ?>" data-id ="<?php echo $randAdv->ID ; ?>">
					  	<img class="modal-content" id="img01" src="<?php echo $img ;?>">
					  </a>
			        </div>
			      </div>
			      
			    </div>
  			</div> 
	    <?php }
	}

	function set_advertisement_space_cookie() {
    // check_ajax_referer( 'advertisement_space' );

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { 
        setcookie('advertisement_space', true, 0, COOKIEPATH, COOKIE_DOMAIN );
    }
    die();
}

	public function set_advertisement_space_click()
	{
		// $id = $_POST['id'];
		$id = intval( $_POST['id'] );
		$viewMeta = get_post_meta( $id, 'advertisement_total_click', true );
		if (!empty($viewMeta)) {
			$view = $viewMeta + 1;
		}else{
			$view = 1;
		}
		update_post_meta( $id, 'advertisement_total_click', $view );
	}

}

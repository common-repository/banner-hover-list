<?php
/*
Plugin Name: Banner Hover List
Plugin URL: http://beautiful-module.com/demo/banner-hover-list/
Description: A simple Responsive Banner Hover List
Version: 1.0
Author: Module Express
Author URI: http://beautiful-module.com
Contributors: Module Express
*/
/*
 * Register CPT sp_banner.hover.list
 *
 */
if(!class_exists('Banner_Hover_List')) {
	class Banner_Hover_List {

		function __construct() {
		    if(!function_exists('add_shortcode')) {
		            return;
		    }
			add_action ( 'init' , array( $this , 'bhl_responsive_gallery_setup_post_types' ));

			/* Include style and script */
			add_action ( 'wp_enqueue_scripts' , array( $this , 'bhl_register_style_script' ));
			
			/* Register Taxonomy */
			add_action ( 'init' , array( $this , 'bhl_responsive_gallery_taxonomies' ));
			add_action ( 'add_meta_boxes' , array( $this , 'bhl_rsris_add_meta_box_gallery' ));
			add_action ( 'save_post' , array( $this , 'bhl_rsris_save_meta_box_data_gallery' ));
			register_activation_hook( __FILE__, 'bhl_responsive_gallery_rewrite_flush' );


			// Manage Category Shortcode Columns
			add_filter ( 'manage_responsive_bhl_slider-category_custom_column' , array( $this , 'bhl_responsive_gallery_category_columns' ), 10, 3);
			add_filter ( 'manage_edit-responsive_bhl_slider-category_columns' , array( $this , 'bhl_responsive_gallery_category_manage_columns' ));
			require_once( 'bhl_gallery_admin_settings_center.php' );
		    add_shortcode ( 'sp_banner.hover.list' , array( $this , 'bhl_responsivegallery_shortcode' ));
		}


		function bhl_responsive_gallery_setup_post_types() {

			$responsive_gallery_labels =  apply_filters( 'sp_banner_hover_list_labels', array(
				'name'                => 'Banner Hover List',
				'singular_name'       => 'Banner Hover List',
				'add_new'             => __('Add New', 'sp_banner_hover_list'),
				'add_new_item'        => __('Add New Image', 'sp_banner_hover_list'),
				'edit_item'           => __('Edit Image', 'sp_banner_hover_list'),
				'new_item'            => __('New Image', 'sp_banner_hover_list'),
				'all_items'           => __('All Images', 'sp_banner_hover_list'),
				'view_item'           => __('View Image', 'sp_banner_hover_list'),
				'search_items'        => __('Search Image', 'sp_banner_hover_list'),
				'not_found'           => __('No Image found', 'sp_banner_hover_list'),
				'not_found_in_trash'  => __('No Image found in Trash', 'sp_banner_hover_list'),
				'parent_item_colon'   => '',
				'menu_name'           => __('Banner Hover List', 'sp_banner_hover_list'),
				'exclude_from_search' => true
			) );


			$responsiveslider_args = array(
				'labels' 			=> $responsive_gallery_labels,
				'public' 			=> true,
				'publicly_queryable'		=> true,
				'show_ui' 			=> true,
				'show_in_menu' 		=> true,
				'query_var' 		=> true,
				'capability_type' 	=> 'post',
				'has_archive' 		=> true,
				'hierarchical' 		=> false,
				'menu_icon'   => 'dashicons-format-gallery',
				'supports' => array('title','editor','thumbnail')
				
			);
			register_post_type( 'sp_banner_hover_list', apply_filters( 'sp_faq_post_type_args', $responsiveslider_args ) );

		}
		
		function bhl_register_style_script() {
		    wp_enqueue_style( 'bhl_responsiveimgslider',  plugin_dir_url( __FILE__ ). 'css/responsiveimgslider.css' );
			/*   REGISTER ALL CSS FOR SITE */
			wp_enqueue_style( 'bhl_featurelist',  plugin_dir_url( __FILE__ ). 'css/hovelist.css' );

			/*   REGISTER ALL JS FOR SITE */			
			wp_enqueue_script( 'bhl_jssor.core', plugin_dir_url( __FILE__ ) . 'js/jssor.core.js', array( 'jquery' ));
			wp_enqueue_script( 'bhl_jssor.utils', plugin_dir_url( __FILE__ ) . 'js/jssor.utils.js', array( 'jquery' ));
			wp_enqueue_script( 'bhl_jssor.slider', plugin_dir_url( __FILE__ ) . 'js/jssor.slider.js', array( 'jquery' ));
			
		}
		
		
		function bhl_responsive_gallery_taxonomies() {
		    $labels = array(
		        'name'              => _x( 'Category', 'taxonomy general name' ),
		        'singular_name'     => _x( 'Category', 'taxonomy singular name' ),
		        'search_items'      => __( 'Search Category' ),
		        'all_items'         => __( 'All Category' ),
		        'parent_item'       => __( 'Parent Category' ),
		        'parent_item_colon' => __( 'Parent Category:' ),
		        'edit_item'         => __( 'Edit Category' ),
		        'update_item'       => __( 'Update Category' ),
		        'add_new_item'      => __( 'Add New Category' ),
		        'new_item_name'     => __( 'New Category Name' ),
		        'menu_name'         => __( 'Gallery Category' ),
		    );

		    $args = array(
		        'hierarchical'      => true,
		        'labels'            => $labels,
		        'show_ui'           => true,
		        'show_admin_column' => true,
		        'query_var'         => true,
		        'rewrite'           => array( 'slug' => 'responsive_bhl_slider-category' ),
		    );

		    register_taxonomy( 'responsive_bhl_slider-category', array( 'sp_banner_hover_list' ), $args );
		}

		function bhl_responsive_gallery_rewrite_flush() {  
				bhl_responsive_gallery_setup_post_types();
		    flush_rewrite_rules();
		}


		function bhl_responsive_gallery_category_manage_columns($theme_columns) {
		    $new_columns = array(
		            'cb' => '<input type="checkbox" />',
		            'name' => __('Name'),
		            'gallery_bhl_shortcode' => __( 'Gallery Category Shortcode', 'bhl_slick_slider' ),
		            'slug' => __('Slug'),
		            'posts' => __('Posts')
					);

		    return $new_columns;
		}

		function bhl_responsive_gallery_category_columns($out, $column_name, $theme_id) {
		    $theme = get_term($theme_id, 'responsive_bhl_slider-category');

		    switch ($column_name) {      
		        case 'title':
		            echo get_the_title();
		        break;
		        case 'gallery_bhl_shortcode':
					echo '[sp_banner.hover.list cat_id="' . $theme_id. '"]';			  	  

		        break;
		        default:
		            break;
		    }
		    return $out;   

		}

		/* Custom meta box for slider link */
		function bhl_rsris_add_meta_box_gallery() {
			add_meta_box('custom-metabox',__( 'LINK URL', 'link_textdomain' ),array( $this , 'bhl_rsris_gallery_box_callback' ),'sp_banner_hover_list');			
		}
		
		function bhl_rsris_gallery_box_callback( $post ) {
			wp_nonce_field( 'bhl_rsris_save_meta_box_data_gallery', 'rsris_meta_box_nonce' );
			$value = get_post_meta( $post->ID, 'rsris_bhl_link', true );
			echo '<input type="url" id="rsris_bhl_link" name="rsris_bhl_link" value="' . esc_attr( $value ) . '" size="25" /><br />';
			echo 'ie http://www.google.com';
		}
		
		function bhl_rsris_save_meta_box_data_gallery( $post_id ) {
			if ( ! isset( $_POST['rsris_meta_box_nonce'] ) ) {
				return;
			}
			if ( ! wp_verify_nonce( $_POST['rsris_meta_box_nonce'], 'bhl_rsris_save_meta_box_data_gallery' ) ) {
				return;
			}
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			if ( isset( $_POST['post_type'] ) && 'sp_banner_hover_list' == $_POST['post_type'] ) {

				if ( ! current_user_can( 'edit_page', $post_id ) ) {
					return;
				}
			} else {

				if ( ! current_user_can( 'edit_post', $post_id ) ) {
					return;
				}
			}
			if ( ! isset( $_POST['rsris_bhl_link'] ) ) {
				return;
			}
			$link_data = sanitize_text_field( $_POST['rsris_bhl_link'] );
			update_post_meta( $post_id, 'rsris_bhl_link', $link_data );
		}
		
		/*
		 * Add [sp_banner.hover.list] shortcode
		 *
		 */
		function bhl_responsivegallery_shortcode( $atts, $content = null ) {
			
			extract(shortcode_atts(array(
				"limit"  => '',
				"cat_id" => '',
				"autoplay" => '',
				"autoplay_interval" => ''
			), $atts));
			
			if( $limit ) { 
				$posts_per_page = $limit; 
			} else {
				$posts_per_page = '-1';
			}
			if( $cat_id ) { 
				$cat = $cat_id; 
			} else {
				$cat = '';
			}
			
			if( $autoplay ) { 
				$autoplay_slider = $autoplay; 
			} else {
				$autoplay_slider = 'true';
			}	 	
			
			if( $autoplay_interval ) { 
				$autoplay_intervalslider = $autoplay_interval; 
			} else {
				$autoplay_intervalslider = '4000';
			}
						

			ob_start();
			// Create the Query
			$post_type 		= 'sp_banner_hover_list';
			$orderby 		= 'post_date';
			$order 			= 'DESC';
						
			 $args = array ( 
		            'post_type'      => $post_type, 
		            'orderby'        => $orderby, 
		            'order'          => $order,
		            'posts_per_page' => $posts_per_page,  
		           
		            );
			if($cat != ""){
		            	$args['tax_query'] = array( array( 'taxonomy' => 'responsive_bhl_slider-category', 'field' => 'id', 'terms' => $cat) );
		            }        
		      $query = new WP_Query($args);

			$post_count = $query->post_count;
			$i = 1;

			if( $post_count > 0) :
			?>
					<div id="bhl_slider1_container" style="position: relative; width: 600px;height: 300px;">

					<div u="loading" style="position: absolute; top: 0px; left: 0px;">
						<div style="filter: alpha(opacity=70); opacity:0.7; position: absolute; display: block;
							background-color: #000; top: 0px; left: 0px;width: 100%;height:100%;">
						</div>
						<div class="bhl_loading_screen">
						</div>
					</div>

					<div u="slides" style="cursor: move; position: absolute; left: 0px; top: 0px; width: 600px; height: 300px;
						overflow: hidden;">
						<?php								
								while ($query->have_posts()) : $query->the_post();
									include('designs/design-1.php');
									
								$i++;
								endwhile;									
						?>
					</div>

					<div u="thumbnavigator" class="slider1-T" style="position: absolute; bottom: 0px; left: 0px; height:60px; width:600px;">
						<div style="filter: alpha(opacity=40); opacity:0.4; position: absolute; display: block;
							background-color: #ffffff; top: 0px; left: 0px; width: 100%; height: 100%;">
						</div>
						<div u="slides">
							<div u="prototype" style="POSITION: absolute; WIDTH: 600px; HEIGHT: 60px; TOP: 0; LEFT: 0;">
								<thumbnailtemplate style="font-family: verdana; font-weight: normal; POSITION: absolute; WIDTH: 100%; HEIGHT: 100%; TOP: 0; LEFT: 0; color:#000; line-height: 60px; font-size:20px; padding-left:10px;"></thumbnailtemplate>
							</div>
						</div>
					</div>
					<div u="navigator" class="jssorn01" style="position: absolute; bottom: 16px; right: 10px;">
						<div u="prototype" style="POSITION: absolute; WIDTH: 12px; HEIGHT: 12px;"></div>
					</div>
					<span u="arrowleft" class="jssord05l" style="width: 40px; height: 40px; top: 123px; left: 8px;">
					</span>
					<span u="arrowright" class="jssord05r" style="width: 40px; height: 40px; top: 123px; right: 8px">
					</span>
				</div>			
			
				<?php
				endif;
				// Reset query to prevent conflicts
				wp_reset_query();
			?>							
			<script type="text/javascript">
					jQuery(document).ready(function ($) {

					var _SlideshowTransitions = [
					//Fade Fly in R
					{$Duration: 1200, $During: { $Left: [0.3, 0.7] }, $FlyDirection: 2, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $ScaleHorizontal: 0.3, $Opacity: 2, $Outside: true }
					//Fade Fly out L
					, { $Duration: 1200, $SlideOut: true, $FlyDirection: 1, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $ScaleHorizontal: 0.3, $Opacity: 2, $Outside: true }
					];

					var _CaptionTransitions = [];
					_CaptionTransitions["L"] = { $Duration: 800, $FlyDirection: 1, $Easing: $JssorEasing$.$EaseInCubic };
					_CaptionTransitions["R"] = { $Duration: 800, $FlyDirection: 2, $Easing: $JssorEasing$.$EaseInCubic };
					_CaptionTransitions["T"] = { $Duration: 800, $FlyDirection: 4, $Easing: $JssorEasing$.$EaseInCubic };
					_CaptionTransitions["B"] = { $Duration: 800, $FlyDirection: 8, $Easing: $JssorEasing$.$EaseInCubic };
					_CaptionTransitions["TL"] = { $Duration: 800, $FlyDirection: 5, $Easing: $JssorEasing$.$EaseInCubic };
					_CaptionTransitions["TR"] = { $Duration: 800, $FlyDirection: 6, $Easing: $JssorEasing$.$EaseInCubic };
					_CaptionTransitions["BL"] = { $Duration: 800, $FlyDirection: 9, $Easing: $JssorEasing$.$EaseInCubic };
					_CaptionTransitions["BR"] = { $Duration: 800, $FlyDirection: 10, $Easing: $JssorEasing$.$EaseInCubic };

					_CaptionTransitions["CLIP|LR"] = { $Duration: 600, $Clip: 3, $Easing: $JssorEasing$.$EaseInOutCubic };
					_CaptionTransitions["MCLIP|L"] = { $Duration: 600, $Clip: 1, $Move: true, $Easing: $JssorEasing$.$EaseInOutCubic };
					_CaptionTransitions["LISTH|L"] = { $Duration: 1000, $Clip: 1, $FlyDirection: 1, $Easing: $JssorEasing$.$EaseInOutCubic, $ScaleHorizontal: 0.8, $ScaleClip: 0.8, $During: { $Left: [0.4, 0.6], $Clip: [0, 0.4]} };
					_CaptionTransitions["WAVE|L"] = { $Duration: 1300, $FlyDirection: 5, $Easing: { $Left: $JssorEasing$.$EaseLinear, $Top: $JssorEasing$.$EaseInWave }, $ScaleVertical: 0.3, $Round: { $Top: 2.5} };
					_CaptionTransitions["JUMPDN|R"] = { $Duration: 1000, $FlyDirection: 6, $Easing: { $Left: $JssorEasing$.$EaseLinear, $Top: $JssorEasing$.$EaseOutJump }, $ScaleHorizontal: 0.6, $ScaleVertical: 0.4, $Round: { $Top: 1.5} };
					_CaptionTransitions["DDG|TR"] = { $Duration: 1200, $Clip: 15, $FlyDirection: 6, $Easing: { $Left: $JssorEasing$.$EaseInJump, $Top: $JssorEasing$.$EaseInJump, $Clip: $JssorEasing$.$EaseSwing }, $ScaleHorizontal: 0.3, $ScaleVertical: 0.3, $During: { $Left: [0, 0.8], $Top: [0, 0.8] }, $Round: { $Left: 0.8, $Top: 0.8} };
					_CaptionTransitions["DODGEDANCE|L"] = { $Duration: 1200, $Clip: 15, $FlyDirection: 9, $Easing: { $Left: $JssorEasing$.$EaseInJump, $Top: $JssorEasing$.$EaseInJump, $Clip: $JssorEasing$.$EaseOutQuad }, $ScaleHorizontal: 0.3, $ScaleVertical: 0.3, $During: { $Left: [0, 0.8], $Top: [0, 0.8] }, $Round: { $Left: 0.8, $Top: 2.5} };
					_CaptionTransitions["FLUTTER|L"] = { $Duration: 600, $FlyDirection: 9, $Easing: { $Left: $JssorEasing$.$EaseLinear, $Top: $JssorEasing$.$EaseOutWave, $Opacity: $JssorEasing$.$EaseLinear }, $ScaleHorizontal: 0.2, $ScaleVertical: 0.1, $Opacity: 2, $Round: { $Top: 1.3} };
					_CaptionTransitions["TORTUOUS|VB"] = { $Duration: 1200, $Clip: 15, $FlyDirection: 8, $Easing: { $Top: $JssorEasing$.$EaseOutWave, $Clip: $JssorEasing$.$EaseOutCubic }, $ScaleVertical: 0.2, $During: { $Top: [0, 0.7] }, $Round: { $Top: 1.3} };
					_CaptionTransitions["FADE"] = { $Duration: 600, $Opacity: 2 };
					_CaptionTransitions["ZMF|10"] = { $Duration: 600, $Zoom: 11, $Easing: { $Zoom: $JssorEasing$.$EaseInExpo, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 };
					_CaptionTransitions["RTT|10"] = { $Duration: 600, $Zoom: 11, $Rotate: true, $Easing: { $Zoom: $JssorEasing$.$EaseInExpo, $Opacity: $JssorEasing$.$EaseLinear, $Rotate: $JssorEasing$.$EaseInExpo }, $Opacity: 2, $Round: { $Rotate: 0.8} };
					_CaptionTransitions["RTTL|BR"] = { $Duration: 600, $Zoom: 11, $Rotate: true, $FlyDirection: 10, $Easing: { $Left: $JssorEasing$.$EaseInExpo, $Top: $JssorEasing$.$EaseInExpo, $Zoom: $JssorEasing$.$EaseInExpo, $Opacity: $JssorEasing$.$EaseLinear, $Rotate: $JssorEasing$.$EaseInExpo }, $ScaleHorizontal: 0.6, $ScaleVertical: 0.6, $Opacity: 2, $Round: { $Rotate: 0.8} };

					var options = {
						$AutoPlay: <?php if($autoplay_slider == "false") { echo 'false';} else { echo 'true'; } ?>,                                    //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
						$AutoPlaySteps: 1,                                  //[Optional] Steps to go for each navigation request (this options applys only when slideshow disabled), the default value is 1
						$AutoPlayInterval: <?php echo $autoplay_intervalslider; ?>,                            //[Optional] Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000
						$PauseOnHover: 0,                               //[Optional] Whether to pause when mouse over if a slider is auto playing, 0 no pause, 1 pause for desktop, 2 pause for touch device, 3 pause for desktop and touch device, default value is 3

						$ArrowKeyNavigation: true,   			            //[Optional] Allows keyboard (arrow key) navigation or not, default value is false
						$SlideDuration: 500,                                //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
						$MinDragOffsetToSlide: 20,                          //[Optional] Minimum drag offset to trigger slide , default value is 20
						//$SlideWidth: 600,                                 //[Optional] Width of every slide in pixels, default value is width of 'slides' container
						//$SlideHeight: 300,                                //[Optional] Height of every slide in pixels, default value is height of 'slides' container
						$SlideSpacing: 0, 					                //[Optional] Space between each slide in pixels, default value is 0
						$DisplayPieces: 1,                                  //[Optional] Number of pieces to display (the slideshow would be disabled if the value is set to greater than 1), the default value is 1
						$ParkingPosition: 0,                                //[Optional] The offset position to park slide (this options applys only when slideshow disabled), default value is 0.
						$UISearchMode: 1,                                   //[Optional] The way (0 parellel, 1 recursive, default value is 1) to search UI components (slides container, loading screen, navigator container, direction navigator container, thumbnail navigator container etc).
						$PlayOrientation: 1,                                //[Optional] Orientation to play slide (for auto play, navigation), 1 horizental, 2 vertical, default value is 1
						$DragOrientation: 3,                                //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $DisplayPieces is greater than 1, or parking position is not 0)

						$SlideshowOptions: {                                //[Optional] Options to specify and enable slideshow or not
							$Class: $JssorSlideshowRunner$,                 //[Required] Class to create instance of slideshow
							$Transitions: _SlideshowTransitions,            //[Required] An array of slideshow transitions to play slideshow
							$TransitionsOrder: 1,                           //[Optional] The way to choose transition to play slide, 1 Sequence, 0 Random
							$ShowLink: true                                    //[Optional] Whether to bring slide link on top of the slider when slideshow is running, default value is false
						},

						$CaptionSliderOptions: {                            //[Optional] Options which specifies how to animate caption
							$Class: $JssorCaptionSlider$,                   //[Required] Class to create instance to animate caption
							$CaptionTransitions: _CaptionTransitions,       //[Required] An array of caption transitions to play caption, see caption transition section at jssor slideshow transition builder
							$PlayInMode: 1,                                 //[Optional] 0 None (no play), 1 Chain (goes after main slide), 3 Chain Flatten (goes after main slide and flatten all caption animations), default value is 1
							$PlayOutMode: 3                                 //[Optional] 0 None (no play), 1 Chain (goes before main slide), 3 Chain Flatten (goes before main slide and flatten all caption animations), default value is 1
						},

						$NavigatorOptions: {                                //[Optional] Options to specify and enable navigator or not
							$Class: $JssorNavigator$,                       //[Required] Class to create navigator instance
							$ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
							$ActionMode: 3,                                 //[Optional] 0 None, 1 act by click, 2 act by mouse hover, 3 both, default value is 1
							$Lanes: 2,                                      //[Optional] Specify lanes to arrange items, default value is 1
							$SpacingX: 10,                                   //[Optional] Horizontal space between each item in pixel, default value is 0
							$SpacingY: 10                                    //[Optional] Vertical space between each item in pixel, default value is 0
						},

						$DirectionNavigatorOptions: {
							$Class: $JssorDirectionNavigator$,              //[Requried] Class to create direction navigator instance
							$ChanceToShow: 1                                //[Required] 0 Never, 1 Mouse Over, 2 Always
						},

						$ThumbnailNavigatorOptions: {
							$Class: $JssorThumbnailNavigator$,              //[Required] Class to create thumbnail navigator instance
							$ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
							$ActionMode: 0,                                 //[Optional] 0 None, 1 act by click, 2 act by mouse hover, 3 both, default value is 1
							$DisableDrag: true,                             //[Optional] Disable drag or not, default value is false
							$Orientation: 2                                 //[Optional] Orientation to arrange thumbnails, 1 horizental, 2 vertical, default value is 1
						}
					};

					var jssor_slider1 = new $JssorSlider$("bhl_slider1_container", options);
					//responsive code begin
					//you can remove responsive code if you don't want the slider scales while window resizes
					function ScaleSlider() {
						var parentWidth = jssor_slider1.$Elmt.parentNode.clientWidth;
						if (parentWidth)
							jssor_slider1.$SetScaleWidth(Math.min(parentWidth, 600));
						else
							window.setTimeout(ScaleSlider, 30);
					}

					ScaleSlider();

					if (!navigator.userAgent.match(/(iPhone|iPod|iPad|BlackBerry|IEMobile)/)) {
						$(window).bind('resize', ScaleSlider);
					}
					//responsive code end
				});
			</script>
			<?php
			return ob_get_clean();
		}		
	}
}
	
function bhl_master_gallery_images_load() {
        global $mfpd;
        $mfpd = new Banner_Hover_List();
}
add_action( 'plugins_loaded', 'bhl_master_gallery_images_load' );
<?php 
namespace TenUp\A1D_Northeastern_CPT\Core;

/*
 * Register custom post type
 * used to create and display custom events 
 *
 * @uses register_post_type
 * @uses flush_rewrite_rules
 *
 * @returns void
 */

function a1dnecpt_register_custom_post() {

  $options = get_option( 'a1dnecpt_cpt_options' );
  $is_enabled = false;
  if ( array_key_exists('a1dnecpt_cpt_enabled', $options ) ){
    if ( 'enabled' === $options['a1dnecpt_cpt_enabled'] ){
      $is_enabled = true;
    }
  }

  $labels = array(
    'name' => _x( 'Events', 'post type general name' ),
    'singular_name' => _x( 'Event', 'post type singular name' ),
    'add_new' => _x( 'Add New Event', 'Event' ),
    'add_new_item' => __( 'Add New Event' ),
    'edit_item' => __( 'Edit Event' ),
    'new_item' => __( 'New Event' ),
    'all_items' => __( 'All Events' ),
    'view_item' => __( 'View Event' ),
    'search_items' => __( 'Search Events' ),
    'not_found' => __( 'No Events found' ),
    'not_found_in_trash' => __( 'No Events found in Trash' ),
    'parent_item_colon' => '',
    'menu_name' => __( 'Events' )
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'menu_icon' => 'dashicons-calendar-alt',
    'show_ui' => true,
    'show_in_menu' => true,
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'page',
    'has_archive' => true,
    'hierarchical' => false,
    'menu_position' => 20,
    'supports' => array( 'title', 'author', 'excerpt', 'editor', 'thumbnail' ),
    'register_meta_box_cb' => __NAMESPACE__ . '\a1dnecpt_add_metaboxes'
  );
  
  if ( $is_enabled ) {
    register_post_type( 'Events', $args );
    flush_rewrite_rules();
  }
}

/* 
 * Create custom taxonomy for Events
 *
 * @uses register_taxonomy
 *
 * @return void
 */

function a1dnecpt_create_events_taxonomy(){ 
 
  $labels = array(
    'name' => _x( 'Categories', 'taxonomy general name' ),
    'singular_name' => _x( 'Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Categories' ),
    'popular_items' => __( 'Popular Categories' ),
    'all_items' => __( 'All Categories' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Category' ),
    'update_item' => __( 'Update Category' ),
    'add_new_item' => __( 'Add New Category' ),
    'new_item_name' => __( 'New Category Name' ),
    'separate_items_with_commas' => __( 'Separate categories with commas' ),
    'add_or_remove_items' => __( 'Add or remove categories' ),
    'choose_from_most_used' => __( 'Choose from the most used categories' ),
  );
 
  register_taxonomy( 'a1dnecpt_event_category','Events', array(
    'label' => __('Event Category'),
    'labels' => $labels,
    'hierarchical' => true,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'event-category' ),
  ) );
}

/*
 * Generate metaboxes
 *
 * @uses add_meta_box()
 *
 * @returns void
 */

function a1dnecpt_add_metaboxes() {

  add_meta_box(
    'a1dnecpt_event_meta',
    'Event Info',
    __NAMESPACE__ . '\a1dnecpt_event_meta',
    'Events'
  );
}

/*
 * Validate and save meta value(s)
 *
 * @uses update_post_meta
 * @uses wp_verify_nonce
 * @uses current_user_can
 *
 * @return void
 */

function a1dnecpt_save_event( $post_id ){
  
  global $post;

  if ( !wp_verify_nonce( $_POST['a1dnecpt_event_noncename'], plugin_basename(__FILE__) ) ) {
    return $post->ID;
  }
  if ( !current_user_can( 'edit_post', $post->ID ) ) {
    return $post->ID;
  }
  if ( isset( $_POST['a1dnecpt_event_start_date'] ) ) {
    $start_date = strtotime( $_POST['a1dnecpt_event_start_date'] .  '  ' . $_POST['a1dnecpt_event_start_time'] ); 
    update_post_meta( $post_id, 'a1dnecpt_start_date', $start_date ); 
  }
  if ( isset( $_POST['a1dnecpt_event_end_date'] ) ) {
    $end_date = strtotime( $_POST['a1dnecpt_event_end_date'] . ' ' . $_POST['a1dnecpt_event_end_time'] ); 
    update_post_meta( $post_id, 'a1dnecpt_end_date', $end_date ); 
  }

}

add_action( 'save_post', __NAMESPACE__ . '\a1dnecpt_save_event', 10, 1);

/*
 * Retrieve custom meta values, or set if not present
 * Output HTML for metaboxes
 *
 * @uses get_post_custom
 * @uses wp_create_nonce
 *
 * @return string html
 */

function a1dnecpt_event_meta() {

  global $post;
  $current =  get_post_meta($post->ID);
  if ( isset( $current['a1dnecpt_start_date'] ) ){
    $start_date = $current['a1dnecpt_start_date'][0];
    $start_time = $start_date;
    $end_date = $current['a1dnecpt_end_date'][0];
    $end_time = $end_date;
  } else {
    // TODO get users current locale to set timezone
    $start_date = time();
    $end_date = $start_date;
    $start_time = 0;
    $end_time = 0;
  }
  $output = '';

  /* 
   * Convert timestamps into readable formats
   */
  $formatted_start_date = date( 'Y-m-d', $start_date );
  $formatted_start_time = date( 'h:i:s', $start_date );
  $formatted_end_date = date( 'Y-m-d', $end_date );
  $formatted_end_time = date( 'h:i:s', $end_date );

  /*
   * Ensure the ability to validate submission came from appropriate party
   */
  $output .= "<input type='hidden' name='a1dnecpt_event_noncename' id='a1dmonitor_site_url_noncename' value='" . wp_create_nonce( plugin_basename(__FILE__) ) . "' />";
  echo $output;

/*
 * Output HTML content
 */
?>
<ul class="a1dnecpt_event_meta">
  <li><label for="a1dnecpt_event_start_date">Event Start Time:</label><input name="a1dnecpt_event_start_date" type="date" class="a1dnecpt_input_date" value="<?php echo $formatted_start_date; ?>"></li>
  <li><label for="a1dnecpt_event_start_time">Event Start Time:</label><input name="a1dnecpt_event_start_time" type="time" class="a1dnecpt_input_time" value="<?php echo $formatted_start_time; ?>"></li>
 <li><label for="a1dnecpt_event_end_date">Event end Time:</label><input name="a1dnecpt_event_end_date" type="date" class="a1dnecpt_input_date" value="<?php echo $formatted_end_date; ?>"></li>
  <li><label for="a1dnecpt_event_end_time">Event end Time:</label><input name="a1dnecpt_event_end_time" type="time" class="a1dnecpt_input_time" value="<?php echo $formatted_end_time; ?>"></li>
</ul>

<?php } ?>

<?php
/*
Plugin Name: Mission Network News Daily Headlines Widget
Description: Creates a widget which shows the most recent daily news headlines from <a href="http://mnnonline.org">Mission Network News</a>.
Author: Topher
Author URI: http://topher1kenobe.com
Version: 1.0
Text Domain: mnn-headlines-widget
License: GPL
*/

/**
 * Provides a WordPress widget that renders recent daily headline from Mission Network News
 *
 * @package MNN_Headlines_Widget
 * @since   MNN_Headlines_Widget 1.0
 * @author  Topher
 */

/**
 * Adds MNN_Headlines_Widget widget.
 *
 * @class   MNN_Headlines_Widget
 * @version 1.0.0
 * @since   1.0
 * @package MNN_Headlines_Widget
 * @author  Topher
 */
class MNN_Headlines_Widget extends WP_Widget {

	/**
	* Holds the source URL for the data
	*
	* @access private
	* @since  1.0
	* @var    string
	*/
	private $mnn_data_url = NULL;

	/**
	* Holds the data retrieved from the remote server
	*
	* @access private
	* @since  1.0
	* @var    object
	*/
	private $mnn_data = NULL;

	/**
	* MNN_Headlines_Widget Constructor, sets up Widget, gets data
	*
	* @access public
	* @since  1.0
	* @return void
	*/
	public function __construct() {

		//  Build out the widget details
		parent::__construct(
			'mnn-headlines-widget',
			__( 'MNN Daily Headlines', 'mnn-headlines-widget' ),
			array( 'description' => __( 'Renders the most recent daily news headlines from Mission Network News.', 'mnn-headlines-widget' ), )
		);

		// assign the data source URL
		$this->mnn_data_url = 'http://mnnonline.org/rss/daily.rss';

		// go get the data and store it in $this->mnn_data
		$this->data_fetcher();

	}

	/**
	* Data fetcher
	*
	* Runs at instantiation, gets data from remote server.  Caching built in.
	*
	* @access private
	* @since  1.0
	* @return void
	*/
	private function data_fetcher() {

		$rss = fetch_feed( $this->mnn_data_url );

		// Checks that the object is created correctly
		if ( ! is_wp_error( $rss ) ) {

			// Figure out how many total items there are, but limit it to 5. 
			$maxitems = $rss->get_item_quantity( 5 ); 

			// Build an array of all the items, starting with element 0 (first element).
			$rss_items = $rss->get_items( 0, $maxitems );

		}

		// store the data in an attribute
		$this->mnn_data = $rss_items;

	}

	/**
	* Data render
	*
	* Parse the data in $this->mnn_data and turn it into HTML for front end rendering
	*
	* @access private
	* @since  1.0
	* @return string
	*/
	private function data_render() {

		// instantiate $output
		$output = '';

		// see if we have data
		if ( 0 < count( $this->mnn_data ) ) {

			// start an unordered list
			$output .= '<ul>' . "\n";

			// Loop through each feed item and display each item as a hyperlink.
			foreach ( $this->mnn_data as $item ) {

				$output .= '<li>' . "\n";

					// start the link
					$output .= '<a href="' . esc_url( $item->get_permalink() ) . '"' . "\n";

						// make the title attribute in the link
						$output .= 'title="' . sprintf( __( 'Posted %s', 'mnn-headlines-widget' ), $item->get_date( 'j F Y' ) ) . '">' . "\n";

						// print the news headline
						$output .= esc_html( $item->get_title() ) . "\n";

					// end the link
					$output .= '</a>' . "\n";

				$output .= '</li>' . "\n";

			}

			$output .= '</ul>' . "\n\n";

		}

		return $output;
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see   WP_Widget::widget()
	 *
	 * @param array $args	  Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		// instantiate $output
		$output = '';

		// filter the title
		$title	= apply_filters( 'widget_title', $instance['title'] );

		// go get the news
		$output .= $this->data_render();

		// echo the widget title
		echo wp_kses_post( $args['before_widget'] );
		if ( ! empty( $title ) ) {
			echo wp_kses_post( $args['before_title'] ) . esc_html( $title ) . wp_kses_post( $args['after_title'] );
		}

		// echo the widget content
		echo wp_kses_post( $output );

		// echo the after_widget html
		echo wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Back-end widget form.
	 *
	 * @see   WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		// check to see if we have a title, and if so, set it
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = '';
		}

		// make the form for the title field in the admin
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'mnn-headlines-widget' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see    WP_Widget::update()
	 *
	 * @param  array $new_instance Values just sent to be saved.
	 * @param  array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		// set up current instance to hold old_instance data
		$instance = $old_instance;

		// set instance to hold new instance data
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

} // class MNN_Headlines_Widget


// register MNN_Headlines_Widget widget
function register_mnn_headlines_wordpress_widget() {
	register_widget( 'MNN_Headlines_Widget' );
}
add_action( 'widgets_init', 'register_mnn_headlines_wordpress_widget' );

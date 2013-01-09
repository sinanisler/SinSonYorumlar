<?php 
/*
Plugin Name: Sin Last Comments
Plugin URI: http://www.sinanisler.com/
Description: <strong>English</strong>: This plugin add last comments widget your widgets area. Different is; default last comments widget have commenter URL but this widget show only name and last comment text. It cant show url or author web address. <strong>Türkçe</strong>: Bu eklenti Bileşenler kısmına Son Yorumlar "Sin Last Comments" Bileşeni eklemektedir. Bu eklentinin normal son yorumlar bileşeninden farkı; Kişinin girdiği web adresini gizlemesidir. Sadece isim ve yorum özetini gösterir.

Author: Sinan İŞLER
Version: 1.0
Author URI: http://www.sinanisler.com/

Copyright 2012  Sinan İŞLER

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA 
*/






class SinLastComments extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'SinLastComments', // Base ID
			'Sin Last Comments', // Name
			array( 'description' => __( 'Sin Last Comments', 'comments' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

			$title = $instance[ 'title' ];
			$sayi = $instance[ 'sayi' ];
			
			
            ?>	
        	
    		<li class="icerikler-sag-kutu widget yorumlar-widget">
            	<h2 class="widgettitle"><?php echo $title; ?></h2>
                
				<?php
                  global $wpdb;
                  $sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, 
				  comment_approved, comment_type,comment_author_url, SUBSTRING(comment_content,1,90) AS com_excerpt 
				  FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) 
				  WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT $sayi";
                 
                  $comments = $wpdb->get_results($sql);
                  $output = $pre_HTML;
                  $output .= "\n<ul>";
                  foreach ($comments as $comment) {
                    $output .= "\n<li>" . "<a href=\"" . get_permalink($comment->ID)."#comment-" . $comment->comment_ID . "\" title=\"on "
                    .$comment->post_title . "\">" .strip_tags($comment->comment_author)." : " . strip_tags($comment->com_excerpt)."</a></li>";
                  }
                  $output .= "\n</ul>";
                  $output .= $post_HTML;
                  echo $output;
                ?>
		
		
            </li>
            
            
            
            
            
            
		<?php
		
		
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['sayi'] = strip_tags( $new_instance['sayi'] );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
			$title = $instance[ 'title' ];
			$sayi = $instance[ 'sayi' ];
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'sayi' ); ?>"><?php _e( 'Yorum Sayısı:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'sayi' ); ?>" name="<?php echo $this->get_field_name( 'sayi' ); ?>" type="text" value="<?php echo esc_attr( $sayi ); ?>" />
		</p>
		<?php 
	}

} // class Foo_Widget

// register Foo_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "SinLastComments" );' ) );






?>

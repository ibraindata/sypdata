<?php
/**
 * Listing: Labels
 *
 * @since 2.0.0
 *
 * @package Listify
 * @category Widget
 * @author Astoundify
 */
class Listify_Widget_Listing_Labels extends Listify_Widget {

	/**
	 * Register widget settings.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		$this->widget_description = __( 'Display the listing tags.', 'listify' );
		$this->widget_id          = 'listify_widget_panel_listing_tags';
		$this->widget_name        = __( 'Listify - Listing: Labels', 'listify' );
		$this->widget_areas       = array( 'single-job_listing-widget-area', 'single-job_listing' );
		$this->widget_notice      = __( 'Add this widget only in "Single Listing" widget areas.' );

		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Title:', 'listify' ),
			),
			'icon' => array(
				'type'    => 'text',
				'std'     => 'ion-ios-pricetag',
				'label'   => '<a href="http://ionicons.com/">' . __( 'Icon Class:', 'listify' ) . '</a>',
			),
		);

		parent::__construct();
	}

	/**
	 * Echoes the widget content.
	 *
	 * @since 2.0.0
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance The settings for the particular instance of the widget.
	 */
	function widget( $args, $instance ) {
		global $job_preview, $job_manager, $post;

		if ( ! is_singular( 'job_listing' ) && ! $job_preview ) {
			echo $this->widget_areas_notice(); // WPCS: XSS ok.
			return false;
		}

		extract( $args );

		$title = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance['title'] : '', $instance, $this->id_base );
		$icon = isset( $instance['icon'] ) ? $instance['icon'] : null;

		if ( $icon ) {
			if ( strpos( $icon, 'ion-' ) !== false ) {
				$before_title = sprintf( $before_title, $icon );
			} else {
				$before_title = sprintf( $before_title, 'ion-' . $icon );
			}
		}

		$tags = get_the_terms( get_the_ID(), 'job_listing_tag' );

		if ( ! $tags || is_wp_error( $tags ) ) {
			return;
		}

		ob_start();

		echo $before_widget; // WPCS: XSS ok.

		if ( $title ) {
			echo $before_title . $title . $after_title; // WPCS: XSS ok.
		}

		do_action( 'listify_widget_job_listing_tags_before' );

		echo '<div class="job_listing_tag-list">';

		foreach ( $tags as $term ) {
			$icon = get_theme_mod( 'listings-job_listing_tag-' . $term->slug . '-icon' );

			// Some crazy backwards compat.
			if ( ! $icon ) {
				$icon = get_theme_mod( 'listings-job_listing_tag-' . str_replace( '-', '_', $term->slug ) . '-icon' );
			}

			// Some crazy backwards compat.
			if ( ! $icon ) {
				$icon = get_theme_mod( 'listings-job_listing_tag-' . $term->term_id . '-icon', 'pricetag' );
			}

			if ( $icon ) {
				$icon = 'ion-' . $icon;
			}

			echo '<a href="' . esc_url( get_term_link( $term->slug, 'job_listing_tag' ) ) . '" class="' . esc_attr( $icon ) . '">' . esc_attr( $term->name ) . '</a>';
		}

		echo '</div>';

		do_action( 'listify_widget_job_listing_tags_after' );

		echo $after_widget; // WPCS: XSS ok.

		echo apply_filters( $this->widget_id, ob_get_clean() ); // WPCS: XSS ok.
	}

}

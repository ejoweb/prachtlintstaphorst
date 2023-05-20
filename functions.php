<?php
/**
 * Prachtlint Staphorst functions and definitions
 */

/* ### HOOK IT UP ########################################################## */

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @return void
 */
add_action( 'after_setup_theme', function() {

	// Add support for block styles.
	add_theme_support( 'wp-block-styles' );

	// Enqueue editor styles.
	add_editor_style( 'style.css' );

	// Enqueue scripts and styles to frontend
	add_action( 'wp_enqueue_scripts', 'pls_add_frontend_styles_and_scripts' );

	// Enqueue scripts and styles to the editor
	add_action( 'enqueue_block_editor_assets', 'pls_add_editor_styles_and_scripts' );

	// Add class to submit button for contact form 7
	add_filter('do_shortcode_tag', 'pls_wpcf7_add_submit_button_class', 10, 4);

	// Use default featured image on special pages
	// add_filter( 'post_thumbnail_html', 'pls_show_default_image_on_special_pages', 21, 5 );
	
} );


/* ### DEFINE FUNCTIONS ##################################################### */

/**
 * Enqueue styles.
 *
 * @return void
 */
function pls_add_frontend_styles_and_scripts() {

	// Get theme version (for cachebusting)
	$theme_version = wp_get_theme()->get( 'Version' );
	$version_string = is_string( $theme_version ) ? $theme_version : false;

	// Register theme stylesheet.
	wp_register_style(
		'pls-style',
		get_template_directory_uri() . '/style.css',
		array(),
		$version_string
	);

	// Enqueue theme stylesheet
	wp_enqueue_style( 'pls-style' );

	// Register theme script	
	wp_register_script( 
		'pls-script-for-frontend',
        get_template_directory_uri() .'/assets/js/script-for-frontend.js', 
		array(),
		false, 
		$version_string
	);

	// Enqueue theme script
	wp_enqueue_script( 'pls-script-for-frontend' );
}


/**
 * Add scripts and styles (editor)
 */
function pls_add_editor_styles_and_scripts() {

	// Register theme stylesheet.
	$theme_version = wp_get_theme()->get( 'Version' );

	$version_string = is_string( $theme_version ) ? $theme_version : false;
	wp_register_style(
		'pls-style-for-editor',
		get_template_directory_uri() . '/assets/css/style-for-editor.css',
		array(),
		$version_string
	);

	// Enqueue theme stylesheet.
	wp_enqueue_style( 'pls-style-for-editor' );
}



function pls_get_svg( $name = '' ) {

	$svg = '';

	if ($name) {
		
		$context = null; 

		/**
		 * Skip SSL check on local development due to SSL error
		 * 
		 * @link https://stackoverflow.com/questions/26148701/file-get-contents-ssl-operation-failed-with-code-1-failed-to-enable-crypto#26151993
		 */
		if ( wp_get_environment_type() == 'local' ) {

			$contextOptions = array(
			    "ssl" => array(
			        "verify_peer" => false,
			        "verify_peer_name" => false,
			    )
			);

			$context = stream_context_create( $contextOptions );
		}

		$svg = file_get_contents( get_stylesheet_directory() . "/assets/svg/{$name}.svg", false, $context );
		$svg = ($svg) ? $svg : '';
	}

	return $svg;
}

/**
 * Show default featured image on special pages like post-archive and 404
 */
function pls_show_default_image_on_special_pages( $html, $post_id, $post_thumbnail_id, $size, $attr ) {

	error_log('testttt');

	if ( is_404() ) {
		$default_thumbnail_id = get_option( 'dfi_image_id' ); // select the default thumb.
	}

	// Attributes can be a query string, parse that.
	if ( is_string( $attr ) ) {
		wp_parse_str( $attr, $attr );
	}

	if ( isset( $attr['class'] ) ) {
		// There already are classes, we trust those.
		$attr['class'] .= ' default-featured-img';
	} else {
		// No classes in the attributes, try to get them form the HTML.
		$img = new \WP_HTML_Tag_Processor( $html );
		if ( $img->next_tag() ) {
			$attr['class'] = trim( $img->get_attribute( 'class' ) . ' default-featured-img' );
		}
	}

	$html = wp_get_attachment_image( $default_thumbnail_id, $size, false, $attr );
	return apply_filters( 'dfi_thumbnail_html', $html, $post_id, $default_thumbnail_id, $size, $attr );
}


/**
 * Contact form 7 - add class to submit button
 */
function pls_wpcf7_add_submit_button_class($output, $tag, $atts, $m) {
	
    if ($tag === 'contact-form-7') {
        $output = str_replace(
            'wpcf7-submit',
            'wpcf7-submit wp-block-button__link',
            $output
        );
    }

    return $output;
}

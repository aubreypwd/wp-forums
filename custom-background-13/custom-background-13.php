<?php

add_theme_support( 'custom-background' );

function custom_background_size( $wp_customize ) {

	// Add the "panel" (Section).
	// If this section already exists, comment the next 3 lines out.
	$wp_customize->add_section( 'theme_settings', array(
		'title' => __( 'Theme Settings' ),
	) );

	// If they haven't set the background image, don't show these controls.
	if ( ! get_theme_mod( 'background_image' ) ) {
		return;
	}

	// Add your setting.
	$wp_customize->add_setting( 'default-size', array(
		'default' => 'inherit',
	) );

	// Add your control box.
	$wp_customize->add_control( 'default-size', array(
		'label'      => __( 'Background Image Size' ),
		'section'    => 'theme_settings',
		'settings'   => 'default-size',
		'priority'   => 200,
		'type' => 'radio',
		'choices' => array(
			'cover' => __( 'Cover' ),
			'contain' => __( 'Contain' ),
			'inherit' => __( 'Inherit' ),
		)
	) );
}

add_action( 'customize_register', 'custom_background_size' );

function custom_background_size_css() {
	$background_size = get_theme_mod( 'default-size', 'inherit' );
	echo '<style> body.custom-background { background-size: '.$background_size.'; } </style>';
}

add_action( 'wp_head', 'custom_background_size_css', 999 );

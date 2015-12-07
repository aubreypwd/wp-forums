<?php

add_theme_support( 'custom-background' );

add_action( 'customize_register', 'custom_background_size' );

function custom_background_size( $wp_customize ) {

	$wp_customize->add_setting( 'default-size', array(
			'default' => 'inherit',
		) );

	$wp_customize->add_control( 'default-size', array(
			'label'      => 'Background Image Size',
			'section'    => 'background_image',
			'settings'   => 'default-size',
			'priority'   => 200,
			'type' => 'radio',
			'choices' => array(
				'cover' => 'Cover',
				'contain' => 'Contain',
				'inherit' => 'Inherit',
			)
		));
}

add_action( 'wp_head', 'custom_background_size_css', 999 );

function custom_background_size_css() {

	$background_size = get_theme_mod( 'default-size', 'inherit' );

	echo '<style type="text/css"> body.custom-background { background-size:'.$background_size.'; } </style>';

}

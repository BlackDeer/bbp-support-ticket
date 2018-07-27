<?php

/*
 *	Plugin Name: BBP Ticketing System
 *	Plugin URI: #
 *	Description: A ticketing plugin built on bbp
 *	Version: 0.1
 *	Author: Yohan Park
 *	Author URI: http://blackdeerdev.com
 *	License: GPL2
 *
*/

/*
 *	Helpful Links
 *	https://codex.bbpress.org/layout-and-functionality-examples-you-can-use/
 *	https://wp-dreams.com/articles/2013/06/adding-custom-fields-bbpress-topic-form/
 *	
 *	
 *	
 *	
 *
*/


/*
* CUSTOM FIELDS
*
*/

//if it is support ticket forum


//display custom field form
function bbp_custom_fields() {
	$value = get_post_meta( bbp_get_topic_id(), 'bbp_extra_field1', true );
	echo '<label for="bbp_extra_field1">Extra Field 1</label><br>';
	echo '<input type="text" name="bbp_extra_field1" value="' . $value . '">';
}

add_action( 'bbp_theme_before_topic_form_content', 'bbp_custom_fields' );

//process and save
function bbp_save_custom_fields( $topic_id = 0 ){
	if( isset( $_POST ) && $_POST[ 'bbp_extra_field1' ] != '' ){
		update_post_meta( $topic_id, 'bbp_extra_field1', $_POST[ 'bbp_extra_field1' ] );
	}
}
add_action( 'bbp_new_topic', 'bbp_save_custom_fields', 10, 1 ); //
add_action( 'bbp_edit_topic', 'bbp_save_custom_fields', 10, 1 );

//display custom fields content
function bbp_display_custom_fields(){
	$topic_id = bbp_get_topic_id();
	$value1 = get_post_meta( $topic_id, 'bbp_extra_field1', true);
	echo 'Field 1: ' . $value1 . '<br>';
}
add_action( 'bbp_template_before_replies_loop', 'bbp_display_custom_fields' );


/*
* CUSTOM ROLE
*
*/

//add custom role
function bbp_add_custom_role( $bbp_roles ){
	$bbp_roles['ticket_manager'] = array(
		'name'			=>	'Ticket Manager',
		'capabilities'	=>	bbp_get_caps_for_role( bbp_get_moderator_role() ) //custom_capabilities( 'ticket_manager' );
	);
	return $bbp_roles;
}
add_filter( 'bbp_get_dynamic_roles', 'bbp_add_custom_role', 1 );

/* if role is ticket manager use our custom cap
function bbp_add_role_caps_filter( $caps, $role ){
	if ( $role == 'ticket_manager' ){
		$caps = custom_capabilities[ $role ];
	}
	return $caps;
}
add_filter( 'bbp_get_caps_for_role', 'bbp_add_role_caps_filter', 10, 2);
*/

/* custom cap
function bbp_custom_caps( $role ){
	switch( $role ){
		case 'ticket_manager':
            return array(
                // Primary caps
                'spectate'              => true,
                'participate'           => true,
                'moderate'              => true,
                'throttle'              => true,
                'view_trash'            => true,
 
                // Forum caps
                'publish_forums'        => false,
                'edit_forums'           => false,
                'edit_others_forums'    => false,
                'delete_forums'         => false,
                'delete_others_forums'  => false,
                'read_private_forums'   => false,
                'read_hidden_forums'    => false,
 
                // Topic caps
                'publish_topics'        => true,
                'edit_topics'           => true,
                'edit_others_topics'    => true,
                'delete_topics'         => false,
                'delete_others_topics'  => false,
                'read_private_topics'   => true,
 
                // Reply caps
                'publish_replies'       => true,
                'edit_replies'          => true,
                'edit_others_replies'   => false,
                'delete_replies'        => false,
                'delete_others_replies' => false,
                'read_private_replies'  => true,
 
                // Topic tag caps
                'manage_topic_tags'     => true,
                'edit_topic_tags'       => true,
                'delete_topic_tags'     => true,
                'assign_topic_tags'     => true,
            );
             break;
        default :
            return $role;
	}
}
*/



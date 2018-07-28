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
 *	https://codex.bbpress.org/bbpress-conditional-tags/
 *
 *	To Do
 *	-Set Topic Tags
 *	-Custom Topic Type
 *	-Custom Topic Status
*/


/*
* CUSTOM FIELDS
*
*/

//if forum is support ticket forum
function is_our_forum(){
	global $post;
    $our_forum = 'support-tickets';
	if( $post->post_type == 'forum' && $post->post_name == $our_forum ){
		return 'true';
	}else{
		return 'false';
	}
}

//if topic parent is support ticket forum
function is_our_topic(){
    global $wpdb;
    $our_forum_id = $wpdb->get_var("
        SELECT ID
        FROM $wpdb->posts
        WHERE post_type = 'forum'
        AND post_name = 'support-tickets'
    ");
    global $post;
    if( $post->post_parent == $our_forum_id ){
        return 'true';
    }else{
        return 'false';
    }
}

//display custom field form
function bbp_custom_fields() {
	if( is_our_forum() == 'true' ){
    include plugin_dir_path(__FILE__) . 'inc/form.php';
	}
}
add_action( 'bbp_theme_before_topic_form_content', 'bbp_custom_fields' );

//process and save
function bbp_save_custom_fields( $topic_id = 0 ){
    if( isset( $_POST ) && $_POST[ 'bbp_extra_field1' ] != '' ){
        update_post_meta( $topic_id, 'bbp_extra_field1', $_POST[ 'bbp_extra_field1' ] );
    }
	if( isset( $_POST ) && $_POST[ 'department' ] != '' ){
		update_post_meta( $topic_id, 'department', $_POST[ 'department' ] );
	}
}
add_action( 'bbp_new_topic', 'bbp_save_custom_fields', 10, 1 ); //
add_action( 'bbp_edit_topic', 'bbp_save_custom_fields', 10, 1 );

//display custom fields content in topic
function bbp_display_custom_fields(){
	if( is_our_topic() == 'true' ){
		$topic_id = bbp_get_topic_id();
        
        $value1 = get_post_meta( $topic_id, 'bbp_extra_field1', true);
        echo 'Field 1: ' . $value1 . '<br>';

		$department = get_post_meta( $topic_id, 'department', true);
		echo 'Department: ' . $department . '<br>';
	}
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



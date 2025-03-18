<?php


function register_like_route()
{
    register_rest_route('university/v1', 'like', array(
        'methods' => "POST",
        'callback' => 'create_like'
    ));
    register_rest_route('university/v1', 'like', array(
        'methods' => "DELETE",
        'callback' => 'delete_like'
    ));
}

function create_like($data)
{
    if (is_user_logged_in()) {

        $existQuery = new WP_Query(array(
            'author' => get_current_user_id(),
            'post_type' => 'like',
            'meta_query' => array(
                array(
                    'key' => 'liked_professor_id',
                    'compare' => '=',
                    'value' => $data['id']
                )
            )
        ));

        if ($existQuery->found_posts == 0 && get_post_type($data['id']) === 'professor') {
            return wp_insert_post(array(
                'post_type' => 'like',
                'post_status' => 'publish',
                'meta_input' => array(
                    'liked_professor_id' => sanitize_text_field($data['id'])
                )
            ));
        } else {
            return "invalid";
        }
    } else {
        return "please login first";
    }
}

function delete_like($data)
{
    if (is_user_logged_in()) {


        if (get_current_user_id() == get_post_field('post_author', sanitize_text_field($data['id'])) && get_post_type(sanitize_text_field($data['id'])) == 'like') {
            wp_delete_post($data['id'], true);
            return "like deleted";
        } else {
            return "invalid";
        }
    } else {
        return "please login first";
    }
}


add_action("rest_api_init", "register_like_route");
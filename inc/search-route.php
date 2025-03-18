<?php
function register_search_route()
{
    register_rest_route('university/v1', 'search', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'university_search_results'
    ));
}
add_action("rest_api_init", "register_search_route");

function university_search_results($data)
{
    $mainQuery = new WP_Query(array(
        'post_type' => array('professor', 'post', 'page', 'event', 'program'),
        'post_status' => 'publish',
        's' => sanitize_text_field($data['term'])
    ));

    $results = array(
        'general_info' => array(),
        'professors' => array(),
        'programs' => array(),
        'events' => array()
    );
    while ($mainQuery->have_posts()) {
        $mainQuery->the_post();

        if (get_post_type() === "post" or get_post_type() === 'page') {
            array_push($results['general_info'], array(
                'title' => get_the_title(),
                'url' => get_the_permalink(),
                'type' => get_post_type(),
                'authorName' => get_the_author()
            ));
        }
        if (get_post_type() === "professor") {
            array_push($results['professors'], array(
                'title' => get_the_title(),
                'url' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
            ));
        }
        if (get_post_type() === "program") {
            array_push($results['programs'], array(
                'title' => get_the_title(),
                'url' => get_the_permalink(),
                'id' => get_the_ID()
            ));
        }
        if (get_post_type() === "event") {
            $eventDate = new DateTime(get_field('event_date'));
            array_push($results['events'], array(
                'title' => get_the_title(),
                'url' => get_the_permalink(),
                'month' => $eventDate->format('M'),
                'day' => $eventDate->format('d'),
                'excerpt' => wp_trim_words(get_the_content(), 10)
            ));
        }
    }

    if ($results['programs'] or $results['events']) {
        $programsMetaQuery = array();
        foreach ($results['programs'] as $program) {
            array_push($programsMetaQuery, array(
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"' . $program['id'] . '"'
            ));
        }

        wp_reset_postdata();
        $relatedPrograms = new WP_Query(array(
            'post_type' => array('professor', 'event'),
            'post_status' => 'publish',
            'meta_query' => array(
                'relation' => 'or',
                $programsMetaQuery,
            )
        ));

        while ($relatedPrograms->have_posts()) {
            $relatedPrograms->the_post();

            if (get_post_type() === "event") {
                $eventDate = new DateTime(get_field('event_date'));
                array_push($results['events'], array(
                    'title' => get_the_title(),
                    'url' => get_the_permalink(),
                    'month' => $eventDate->format('M'),
                    'day' => $eventDate->format('d'),
                    'excerpt' => wp_trim_words(get_the_content(), 10)
                ));
            }

            if (get_post_type() === "professor") {
                array_push($results['professors'], array(
                    'title' => get_the_title(),
                    'url' => get_the_permalink(),
                    'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
                ));
            }
        }
        $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));
        $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));
    }

    return $results;
}
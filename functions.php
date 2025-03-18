<?php
require get_theme_file_path('/inc/search-route.php');
require get_theme_file_path('/inc/like-route.php');
function university_assets()
{
    wp_enqueue_style("google-font", "//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i");
    wp_enqueue_style("font-awesome", "//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
    wp_enqueue_style("university_main_styles", get_theme_file_uri("/build/style-index.css"));
    wp_enqueue_style("university_extra_styles", get_theme_file_uri("/build/index.css"));

    wp_enqueue_script("university-scripts", get_theme_file_uri("/build/index.js"), array('jquery'), '1.0', true);

    wp_localize_script("university-scripts", "universityData", array(
        'url' => get_site_url(),
        'nonce' => wp_create_nonce('wp_rest')
    ));
}

add_action("wp_enqueue_scripts", "university_assets");


function theme_support()
{
    add_theme_support("title-tag");
    add_theme_support("post-thumbnails");
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
}
add_action("after_setup_theme", "theme_support");

function adjust_query($query)
{

    if (!is_admin() and is_post_type_archive('event') and is_main_query()) {
        $today = date('Ymd');

        $query->set('meta_key', "event_date");
        $query->set('orderby', "meta_value_num");
        $query->set('order', "ASC");
        $query->set('meta_query', array(
            array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
            )
        ));
    }
    if (!is_admin() and is_post_type_archive('program') and is_main_query()) {
        $query->set("orderby", "title");
        $query->set("order", "ASC");
        $query->set("posts_per_page", -1);
    }
}
add_action("pre_get_posts", "adjust_query");


function pageBanner($array = NULL)
{

    if (!isset($array['title'])) {
        $array['title'] = get_the_title();
    }
    if (!isset($array['subtitle'])) {
        $array['subtitle'] = get_field("page_banner_subtitle");
    }
    if (!isset($array['photo'])) {
        if (get_field("page_banner_image")) {
            $array['photo'] = get_field("page_banner_image")['sizes']['pageBanner'];
        } else {

            $array['photo'] = get_theme_file_uri('/images/ocean.jpg');
        }
    }

?>
<div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $array['photo'] ?>)"></div>
    <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php echo $array['title'] ?></h1>
        <div class="page-banner__intro">
            <p><?php echo $array['subtitle'] ?></p>
        </div>
    </div>
</div>
<?php
}
function custom_rest_api()
{
    register_rest_field('post', 'authorName', array(
        'get_callback' => function () {
            return get_the_author();
        }
    ));
    register_rest_field('note', 'noteCount', array(
        'get_callback' => function () {
            return count_user_posts(get_current_user_id(), 'note');
        }
    ));
}
add_action("rest_api_init", "custom_rest_api");


function redirect_subscriber()
{
    $currentUser = wp_get_current_user();
    if (count($currentUser->roles) === 1 && $currentUser->roles[0] === 'subscriber') {
        wp_redirect(site_url('/'));
        exit;
    }
}
add_action("admin_init", "redirect_subscriber");




function disable_adminbar_for_subscriber()
{
    $currentUser = wp_get_current_user();
    if (count($currentUser->roles) === 1 && $currentUser->roles[0] === 'subscriber') {
        show_admin_bar(false);
    }
}
add_action("wp_loaded", "disable_adminbar_for_subscriber");


function headerUrl()
{
    return site_url('/');
}
add_filter("login_headerurl", 'headerUrl');


function loginPageCss()
{
    wp_enqueue_style("google-font", "//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i");
    wp_enqueue_style("font-awesome", "//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
    wp_enqueue_style("university_main_styles", get_theme_file_uri("/build/style-index.css"));
    wp_enqueue_style("university_extra_styles", get_theme_file_uri("/build/index.css"));
}
add_action("login_enqueue_scripts", 'LoginPageCss');

function loginTitle()
{
    return get_bloginfo('name');
}
add_filter("login_headertitle", "loginTitle");

function makeNotesPrivate($notes, $postarr)
{
    if ($notes['post_type'] == 'note') {
        if (count_user_posts(get_current_user_id(), 'note') > 4 && !$postarr['ID']) {
            return die("stop");
        }
    }
    if ($notes['post_type'] == 'note' && $notes['post_status'] != 'trash')  $notes['post_status'] = 'private';
    return $notes;
}
add_filter('wp_insert_post_data', "makeNotesPrivate", 10, 2);
<?php get_header(); ?>
<div class="page-banner">
    <div class="page-banner__bg-image"
        style="background-image: url(<?php echo get_theme_file_uri("images/library-hero.jpg") ?>)"></div>
    <div class="page-banner__content container t-center c-white">
        <h1 class="headline headline--large">Welcome!</h1>
        <h2 class="headline headline--medium">We think you&rsquo;ll like it here.</h2>
        <h3 class="headline headline--small">Why don&rsquo;t you check out the <strong>major</strong> you&rsquo;re
            interested in?</h3>
        <a href="<?php echo site_url('/program') ?>" class="btn btn--large btn--blue">Find Your Major</a>
    </div>
</div>

<div class="full-width-split group">
    <div class="full-width-split__one">
        <div class="full-width-split__inner">
            <h2 class="headline headline--small-plus t-center">Upcoming Events</h2>

            <?php
            $today = date('Ymd');
            $recentEvents = new WP_Query(array(
                'post_type' => 'event',
                'post_status' => 'publish',
                'posts_per_page' => 2,
                'meta_key' => 'event_date',
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
                'meta_query' => array(
                    array(
                        'key' => 'event_date',
                        'compare' => '>=',
                        'value' => $today,
                        'type' => 'numeric'
                    )
                )
            ));
            while ($recentEvents->have_posts()) {
                $recentEvents->the_post();
                get_template_part('template-parts/event');
            }
            wp_reset_postdata();
            ?>
            <p class="t-center no-margin"><a href="<?php echo site_url('event') ?>" class="btn btn--blue">View All
                    Events</a></p>
        </div>
    </div>
    <div class="full-width-split__two">
        <div class="full-width-split__inner">
            <h2 class="headline headline--small-plus t-center">From Our Blogs</h2>
            <?php
            $recentPosts = new WP_Query(array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => 2
            ));
            while ($recentPosts->have_posts()) {
                $recentPosts->the_post(); ?>

            <div class="event-summary">
                <a class="event-summary__date event-summary__date--beige t-center" href="<?php the_permalink() ?>">
                    <span class="event-summary__month"><?php the_time('M') ?></span>
                    <span class="event-summary__day"><?php the_time('d') ?></span>
                </a>
                <div class="event-summary__content">
                    <h5 class="event-summary__title headline headline--tiny"><a
                            href="<?php the_permalink() ?>"><?php the_title() ?></a>
                    </h5>
                    <p><?php echo wp_trim_words(get_the_content(), 18) ?> <a href="<?php the_permalink() ?>"
                            class="nu gray">Read more</a></p>
                </div>
            </div>
            <?php
            }
            wp_reset_postdata();
            ?>
            <p class="t-center no-margin"><a href="<?php echo site_url('/blog') ?>" class="btn btn--yellow">View All
                    Blog Posts</a></p>
        </div>
    </div>
</div>

<div class="hero-slider">
    <div data-glide-el="track" class="glide__track">
        <div class="glide__slides">
            <?php
            $slides = new WP_Query(array(
                'post_type' => 'slide',
                'post_status' => 'publish',
                'posts_per_page' => -1
            ));
            while ($slides->have_posts()) {
                $slides->the_post() ?>
            <div class="hero-slider__slide"
                style="background-image: url(<?php echo  get_field("page_banner_image")['sizes']['pageBanner'] ?>)">
                <div class="hero-slider__interior container">
                    <div class="hero-slider__overlay">
                        <h2 class="headline headline--medium t-center"><?php echo get_the_title() ?></h2>
                        <p class="t-center"><?php echo esc_html(get_field('page_banner_subtitle')) ?></p>
                        <p class="t-center no-margin"><a href="<?php echo esc_url(get_field('link')) ?>"
                                class="btn btn--blue">Learn more</a></p>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>

        </div>
        <div class="slider__bullets glide__bullets" data-glide-el="controls[nav]"></div>
    </div>
</div>
<?php get_footer(); ?>
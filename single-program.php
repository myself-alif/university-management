<?php get_header();

while (have_posts()) {
    the_post();
    pageBanner();
?>
<div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
            <a class="metabox__blog-home-link" href="<?php echo site_url("/program") ?>"><i class="fa fa-home"
                    aria-hidden="true"></i> All Programs</a> <span class="metabox__main"><?php the_title() ?></span>
        </p>
    </div>
    <div class="generic-content"><?php echo get_field("content"); ?></div>


    <?php
        $relatedProfessor = new WP_Query(array(
            'post_type' => 'professor',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_key' => 'related_programs',
            'orderby' => 'title',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'related_programs',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"',

                )
            )
        ));

        if ($relatedProfessor->have_posts()) { ?>
    <hr class="section-break">
    <h2 class="headline headline--medium"><?php the_title() ?> Professor</h2>
    <ul class="professor-cards">
        <?php
                while ($relatedProfessor->have_posts()) {
                    $relatedProfessor->the_post(); ?>
        <li class="professor-card__list-item"><a class="professor-card" href="<?php the_permalink() ?>">
                <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape') ?>">
                <span class="professor-card__name"><?php the_title() ?></span>
            </a></li>
        <?php
                }
                wp_reset_postdata();
                ?>
    </ul>
    <?php
        }


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
                ),
                array(
                    'key' => 'related_programs',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"',

                )
            )
        ));

        if ($recentEvents->have_posts()) { ?>
    <hr class="section-break">
    <h2 class="headline headline--medium">Upcoming <?php the_title() ?> Events</h2>
    <?php
            while ($recentEvents->have_posts()) {
                $recentEvents->the_post();
                get_template_part('template-parts/event');
            }
            wp_reset_postdata();
            ?>
    <?php
        }
        ?>
</div>
<?php
}

get_footer() ?>
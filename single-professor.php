<?php get_header();

while (have_posts()) {
    the_post();
    pageBanner();
?>

<div class="container container--narrow page-section">

    <div class="generic-content">
        <div class="row group">
            <div class="one-third">
                <?php the_post_thumbnail('professorPortrait') ?>
            </div>
            <div class="two-thirds">
                <?php
                    $like_count = new WP_Query(array(
                        'post_type' => 'like',
                        'meta_query' => array(
                            array(
                                'key' => 'liked_professor_id',
                                'compare' => '=',
                                'value' => get_the_ID()
                            )
                        )
                    ));

                    $existStatus = 'no';
                    if (is_user_logged_in()) {
                        $existQuery = new WP_Query(array(
                            'author' => get_current_user_id(),
                            'post_type' => 'like',
                            'meta_query' => array(
                                array(
                                    'key' => 'liked_professor_id',
                                    'compare' => '=',
                                    'value' => get_the_ID()
                                )
                            )
                        ));
                        if ($existQuery->found_posts) $existStatus = 'yes';
                    }

                    ?>
                <span class="like-box" data-exists="<?php echo $existStatus ?>" data-professor="<?php the_ID() ?>"
                    data-like="<?php
                                    if (isset($existQuery->posts[0])) echo $existQuery->posts[0]->ID ?>">
                    <i class="fa fa-heart-o" aria-hidden="true"></i>
                    <i class="fa fa-heart" aria-hidden="true"></i>
                    <span class="like-count"><?php echo $like_count->found_posts ?></span>
            </div>
            <?php the_content() ?>

        </div>
    </div>
    <?php $relatedPrograms = get_field("related_programs");

        if ($relatedPrograms) { ?>
    <hr class="section-break">
    <h2 class="headline headline--medium">Subject(s) Taught</h2>
    <ul class="link-list min-list">
        <?php

                foreach ($relatedPrograms as $program) { ?>

        <li><a href="<?php echo get_the_permalink($program) ?>"><?php echo get_the_title($program) ?></a></li>

        <?php
                }
                ?>
    </ul>
    <?php
        }
        ?>
</div>
</div>
<?php
}

get_footer() ?>
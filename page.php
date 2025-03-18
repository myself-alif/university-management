<?php
get_header();

while (have_posts()) {
    the_post();
    pageBanner();
?>
<div class="container container--narrow page-section">

    <?php
        if (wp_get_post_parent_id(get_the_ID())) { ?>
    <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
            <a class="metabox__blog-home-link"
                href="<?php echo get_permalink(wp_get_post_parent_id(get_the_ID())) ?>"><i class="fa fa-home"
                    aria-hidden="true"></i> Back to
                <?php echo get_the_title(wp_get_post_parent_id(get_the_ID())) ?></a> <span
                class="metabox__main"><?php the_title() ?></span>
        </p>
    </div>
    <?php
        }
        ?>
    <?php
        $isParent = get_pages(array(
            'child_of' => get_the_ID()
        ));
        if (wp_get_post_parent_id(get_the_ID()) or $isParent) { ?>

    <div class="page-links">
        <h2 class="page-links__title"><a
                href="<?php echo get_permalink(wp_get_post_parent_id(get_the_ID())) ?>"><?php echo get_the_title(wp_get_post_parent_id(get_the_ID())) ?></a>
        </h2>
        <ul class="min-list">
            <?php

                    if (wp_get_post_parent_id(get_the_ID())) {
                        $parent = wp_get_post_parent_id(get_the_ID());
                    } else {
                        $parent = get_the_ID();
                    }

                    wp_list_pages(array(
                        'title_li' => NULL,
                        'child_of' => $parent
                    ))

                    ?>
        </ul>
    </div>

    <?php

        }

        ?>

    <div class="generic-content">
        <?php the_content() ?>
    </div>
</div>

<?php
}

get_footer();
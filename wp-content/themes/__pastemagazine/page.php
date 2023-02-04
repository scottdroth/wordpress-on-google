<?php get_header(); ?>

<main class="expanded site-body" id="site_body" role="main">
    <div class="grid-container">
        <div class="grid-x article-wrapper">
            <div class="large-12 cell">
                <div class="grid-x small-up-1 medium-up-2 large-up-12">
                    <div class="large-auto medium-auto small-auto">

                        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                        <article id="article-detail-container">
                            <div class="header" data-entry-id="<?php the_ID(); ?>">
                                <h1 class="title"><?php the_title(); ?></h1>
                            </div>
                            <div class="copy entry manual-ads">
                                <?php the_content(); ?>
                            </div>
                        </article>

                        <?php endwhile;
                        endif; ?>
                    </div>

                    <?php get_sidebar(); ?>

                </div>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
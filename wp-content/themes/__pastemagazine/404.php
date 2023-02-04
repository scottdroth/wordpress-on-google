<?php get_header(); ?>
<main class="expanded site-body" id="site_body">
    <div class="grid-container">

        <div class="grid-x landing-wrapper">
            <div class="large-12 cell">
                <div class="grid-x small-up-1 medium-up-2 large-up-12">
                    <div class="large-auto medium-auto small-auto">
                        <div id="article-detail-container" class="error-page">
                            <div class="header">
                                <h1 class="title">404</h1>
                            </div>
                            <div class="copy">
                                <p>We’re sorry, but the page you’re looking for doesn’t seem to be working. <br>Why not
                                    check out:</p>
                                <ul class="links">
                                    <li><a class="ovr" href="/">The PasteMagazine.com Homepage</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="large-3 medium-3 small-auto">
                        <?php get_sidebar(); ?>

                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
<?php get_footer(); ?>
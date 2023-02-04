<?php get_header(); ?>

<main class="expanded site-body" id="site_body" role="main">
    <div class="grid-container">
        <div class="grid-x article-wrapper">
            <div class="large-12 cell">
                <div class="grid-x small-up-1 medium-up-2 large-up-12">
                    <div class="large-auto medium-auto small-auto">
                        <br>
                        <div id="article-detail-container" style="border-top: 1px solid #eee;">
                            <div class="copy newsletter-signup">

                            
                                <div class="grid-x large-12 medium-12 small-12">
                                    <div class="small-12">
                                        
                                        
                                        <div>
                                            <!-- <input type="checkbox" id="newsletter-signup-checkbox-main"
                                                name="newsletter-signup-checkbox-main" checked="checked"
                                                style="display:none">
                                            <input type="checkbox" id="newsletter-signup-checkbox-music"
                                                name="newsletter-signup-checkbox-music" checked="checked"
                                                style="display:none"> -->
                                            <label>Get Paste Right in Your Inbox</label>
                                        </div>
                                        <p>The best in music, movies, TV, books, comedy and more.</p>
                                    </div>
                                </div>
                                <!-- <p class="grid-x grid-margin-x">
                                    <input id="newsletter-signup-email" class="medium-8 small-8 cell" type="email"
                                        placeholder="email address" name="newsletter-signup-email">
                                    <button id="newsletter-signup-submit" class="medium-2 small-4 button">Sign
                                        Up</button>
                                    <span class="row" style="display:none"><input type="checkbox"
                                            id="newsletter-signup-optin" name="newsletter-signup-optin"
                                            checked="checked" required=""><label for="nl-opt-in">Yes, I would like to
                                            receive Paste's newsletter</label></span>
                                </p> -->
                                <?php echo do_shortcode('[mc4wp_form id="261672"]'); ?>
                                <div class="clear"></div>

                            </div>
                        </div>
                    </div>

                    <?php get_sidebar(); ?>

                </div>
            </div>
        </div>

    </div>
</main>

<?php get_footer(); ?>
<footer id="master-footer" class="row" role="contentinfo">
    <!-- <div class="grid-container">
        <div class="grid-x landing-wrapper __web-inspector-hide-shortcut__">
            <div class="large-12 cell">
                <div class="grid-x small-up-1 medium-up-2 large-up-12">
                    <div class="large-auto medium-auto small-auto"> -->
    <?php wp_nav_menu([
        'menu' => 'footer-menu',
        'container' => false,
    ]); ?>

    <p>&copy; <?php echo esc_html(
                    date_i18n(__('Y', 'pastemagazine'))
                ); ?> Paste Media Group. All Rights Reserved</p>

    <!-- </div>
                </div>
            </div>
        </div>
    </div> -->
</footer>
<div id="bottom-fixed-unit" class="dfp">
    <div>
        <div id="bottom_leaderboard"></div>
    </div>
</div>
<?php wp_footer(); ?>

<script>
function loadScript(url, callback) {
    var script = document.createElement("script");
    script.type = "text/javascript";
    if (script.readyState) {
        //IE
        script.onreadystatechange = function() {
            if (script.readyState == "loaded" || script.readyState == "complete") {
                script.onreadystatechange = null;
                callback();
            }
        };
    } else {
        //Others
        script.onload = function() {
            callback();
        };
    }
    script.src = url;
    document.getElementsByTagName("head")[0].appendChild(script);
}
setTimeout(
    loadScript,
    2500,
    `${templateDirURI}/js/pm_ad_commands.js`,
    function() {
        //window.pm.ads.initialize(window.pm.adUnitsX, 500, 31000, 31000 + 5000);
    }
);

setTimeout(
    loadScript,
    5000,
    `<?php bloginfo('url'); ?>/wp-content/plugins/mailchimp-for-wp/assets/js/forms.js?ver=4.8.8`,
    function() {}
);

setTimeout(loadScript, 5000, 'https://tag.bounceexchange.com/3869/i.js');
</script>

</body>

</html>
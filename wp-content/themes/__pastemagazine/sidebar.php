<aside id="sidebar" role="complementary" class="large-3 medium-3 <?php echo stristr($_SERVER['REQUEST_URI'], '/articles/') ? 'show-for-large' : 'small-auto';?>">

    <?php if (is_active_sidebar('primary-widget-area')) :
        dynamic_sidebar('primary-widget-area');
    endif; ?>

</aside>
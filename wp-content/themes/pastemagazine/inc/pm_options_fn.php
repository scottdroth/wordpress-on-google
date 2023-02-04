<?php

// Settings Page: PMOptions
// Retrieving values: get_option( 'your_field_id' )
class PMOptions_Settings_Page
{

    private $ppp = 0;

    public function __construct()
    {
        $this->ppp = get_option('posts_per_page');

        add_action('admin_menu', array($this, 'wph_create_settings'));
        add_action('admin_init', array($this, 'wph_setup_sections'));
        add_action('admin_init', array($this, 'wph_setup_fields'));
    }

    public function wph_create_settings()
    {
        $page_title = 'Pastemagazine Options';
        $menu_title = 'PM Options';
        $capability = 'manage_options';
        $slug = 'PMOptions';
        $callback = array($this, 'wph_settings_content');
        $icon = 'dashicons-admin-generic';
        $position = 20;
        add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon, $position);
    }

    public function wph_settings_content()
    {
?>
<div class="wrap">
    <h1>Pastemagazine Options</h1>
    <?php settings_errors(); ?>
    <form method="POST" action="options.php">
        <?php
                settings_fields('PMOptions');
                do_settings_sections('PMOptions');
                submit_button();
                ?>


    </form>
    <?php if (current_user_can('administrator')) { ?>
    <button id="aa_maintenance_submit" name="aa_maintenance-submit"
        class="button-primary aa_button"><?php echo __('Start maintenance', 'aa_store'); ?></button>
    <?php } ?>

    <div id="ajax_response_container"></div>

    <script id="aa-maintenance-ajax-scripts">
    (function($) {

        var maintenanceAbort = false,
            maintenanceDone = false,
            maintenanceOptions = [],
            elapsed_seconds = 0,
            maintenance_elapsed_time = false;


        get_elapsed_time_string = function(total_seconds) {

            pretty_time_string = function(num) {
                return (num < 10 ? "0" : "") + num;
            };

            var hours = Math.floor(total_seconds / 3600);
            total_seconds = total_seconds % 3600;

            var minutes = Math.floor(total_seconds / 60);
            total_seconds = total_seconds % 60;

            var seconds = Math.floor(total_seconds);

            // Pad the minutes and seconds with leading zeros, if required
            hours = pretty_time_string(hours);
            minutes = pretty_time_string(minutes);
            seconds = pretty_time_string(seconds);

            // Compose the string for display
            var currentTimeString = hours + ":" + minutes + ":" + seconds;

            return currentTimeString;
        };



        maintenanceAjax = function(maintenanceData) {

            //console.log("maintenanceAjax called");

            if (!maintenance_elapsed_time) {
                maintenance_elapsed_time = setInterval(function() {
                    elapsed_seconds = elapsed_seconds + 1;
                    jQuery('#time_holder span').text(get_elapsed_time_string(elapsed_seconds));
                }, 1000);
            }

            jQuery.ajax({
                url: '<?php echo admin_url("admin-ajax.php"); ?>',
                method: 'POST',
                data: {
                    action: 'pm_maintenance',
                    maintenance_data: maintenanceData,
                    aa_nonce: '<?php echo wp_create_nonce("aa_store_maintenance"); ?>'
                },
                error: function(jqXHR, textStatus, errorThrown) {

                    alert('aa_store_maintenance ERROR!');

                    //location.reload();
                }

            }).done(function(response) {

                //console.log(response);

                if (true === response.success) {

                    var res = response.data,
                        allDone = false;

                    //console.log(res);

                    $('#ajax_response_container').html(res.html);


                    if (res.import_done == 1) {
                        /* var mAjaxArgs = {

                            'progress': 0
                        }

                        maintenanceAjax(mAjaxArgs); */


                        if (allDone) {
                            $('#aa_maintenance_submit')
                                .remove(); //.removeClass('aa_red').text("<?php echo __('Karbantartás Indítása', 'aa_store'); ?>");   

                            $('#ajax_response_container').append(
                                '<h3 style="color:#52AB37">Maintenance done.</h3>');
                            //location.reload();

                            clearInterval(maintenance_elapsed_time);
                        }
                    } else {
                        maintenanceAjax(res);
                    }

                } else {
                    //alert(response.data);            

                }

            });

        };

        $('#aa_maintenance_submit').on('click', function() {

            if (!$(this).hasClass('aa_red')) {
                if (!confirm('Are you sure you want to start the maintenance?')) return false;
            } else {
                if (confirm('Do you want to cancel the process?')) {
                    location.reload();
                }
            }

            $('#aa_maintenance_submit').addClass('aa_red').text(
                "<?php echo __('Cancel', 'aa_store'); ?>");

            if (!$('#time_holder').length) $('#ajax_response_container').before(
                '<br><br><div id="time_holder">Elapsed time: <span></span></div>');

            var mAjaxArgs = {
                'progress': 0
            }

            maintenanceAjax(mAjaxArgs);

        });

    })(jQuery);
    </script>
</div>
<?php
    }

    public function wph_setup_sections()
    {
        add_settings_section('PMOptions_section', '', array(), 'PMOptions');
        /*add_settings_section(
            'pm_options_2_setting_section', // id
            'Settings', // title
            array($this, 'pm_options_2_section_info'), // callback
            'pm-options-2-admin' // page
        );*/
    }

    public function wph_setup_fields()
    {
        $defaultText = "default is $this->ppp";

        $fields = array(
            array(
                'section' => 'PMOptions_section',
                'label' => 'Homepage Article Limit (Fetaures)',
                'placeholder' => $defaultText,
                'id' => 'homepage_article_limit_features',
                'desc' => 'Sets the number of articles to show in the Features section',
                'type' => 'number',
            ),
            array(
                'section' => 'PMOptions_section',
                'label' => 'Homepage Article Limit (Lists)',
                'placeholder' => $defaultText,
                'id' => 'homepage_article_limit_lists',
                'desc' => 'Sets the number of articles to show in the Lists section',
                'type' => 'number',
            ),
            array(
                'section' => 'PMOptions_section',
                'label' => 'Homepage Article Limit (News)',
                'placeholder' => $defaultText,
                'id' => 'homepage_article_limit_news',
                'desc' => 'Sets the number of articles to show in the News section',
                'type' => 'number',
            ),
            array(
                'section' => 'PMOptions_section',
                'label' => 'Homepage Article Limit (Reviews)',
                'placeholder' => $defaultText,
                'id' => 'homepage_article_limit_reviews',
                'desc' => 'Sets the number of articles to show in the Reviews section',
                'type' => 'number',
            ),

            array(
                'section' => 'PMOptions_section',
                'label' => 'Article Category Listing Limit',
                'placeholder' => $defaultText,
                'id' => 'archive_listing_limit',
                'desc' => 'Sets the number of articles shown per page on Article Category archive pages.',
                'type' => 'number',
            ),

            array(
                'section' => 'PMOptions_section',
                'label' => 'Archive Type Listing Limit',
                'placeholder' => $defaultText,
                'id' => 'archive_type_listing_limit',
                'desc' => 'Sets the number of articles shown per page on Article Type archive pages.',
                'type' => 'number',
            ),

            array(
                'section' => 'PMOptions_section',
                'label' => 'Archive Type Ad Frequency',
                'placeholder' => 0,
                'id' => 'archive_listing_ad_freq',
                'desc' => 'Inserts ad slots after every Nth article on Article Type archive pages.',
                'type' => 'number',
            ),

            array(
                'section' => 'PMOptions_section',
                'label' => 'Video Listing Limit',
                'placeholder' => $defaultText,
                'id' => 'video_listing_limit',
                'desc' => 'Sets the number of articles shown per page on Video Article Type archive pages.',
                'type' => 'number',
            ),

            array(
                'section' => 'PMOptions_section',
                'label' => 'Search Results Article Limit',
                'placeholder' => $defaultText,
                'id' => 'search_section_article_limit',
                'type' => 'number',
            ),

            /* array(
                'section' => 'PMOptions_section',
                'label' => 'Search Section Ad Frequency',
                'placeholder' => 0,
                'id' => 'search_section_ad_freq',
                'type' => 'number',
            ), */

            array(
                'section' => 'PMOptions_section',
                'label' => 'Search Results (Filtered) Article Limit',
                'placeholder' => $defaultText,
                'id' => 'search_article_limit',
                'type' => 'number',
            ),

            /* array(
                'section' => 'PMOptions_section',
                'label' => 'Search (Filtered) Ad Frequency',
                'placeholder' => 0,
                'id' => 'search_ad_freq',
                'type' => 'number',
            ), */

            array(
                'section' => 'PMOptions_section',
                'label' => 'Author Article Limit',
                'placeholder' => $defaultText,
                'id' => 'author_article_limit',
                'type' => 'number',
            ),

            array(
                'section' => 'PMOptions_section',
                'label' => 'Sidebar Article Limit',
                'placeholder' => $defaultText,
                'id' => 'sidebar_article_limit',
                'type' => 'number',
            ),

            array(
                'section' => 'PMOptions_section',
                'label' => 'Single (Related) Articles Limit',
                'placeholder' => $defaultText,
                'id' => 'single_article_limit',
                'desc' => 'Sets the number of related articles (per section) below the article body.',
                'type' => 'number',
            ),

            array(
                'section' => 'PMOptions_section',
                'label' => 'Single Article Ad Frequency Word Count',
                'placeholder' => 'default is 300',
                'id' => 'single_article_ad_word_count',
                'desc' => 'Sets the minimum number of words (in consecutive paragraphs) after and ad slot is inserted.',
                'type' => 'number',
            )
        );
        foreach ($fields as $field) {
            add_settings_field($field['id'], $field['label'], array($this, 'wph_field_callback'), 'PMOptions', $field['section'], $field);
            register_setting('PMOptions', $field['id']);
            /*register_setting(
            'pm_options_2_option_group', // option_group
            'pm_options_2_option_name', // option_name
            array($this, 'pm_options_2_sanitize') // sanitize_callback
            );*/
        }
    }

    /* public function pm_options_2_sanitize($input)
    {
        $sanitary_values = array();
        if (isset($input['homepage_article_limit_0'])) {
            $sanitary_values['homepage_article_limit_0'] = sanitize_text_field($input['homepage_article_limit_0']);
        }

        if (isset($input['kutykurutty_1'])) {
            $sanitary_values['kutykurutty_1'] = sanitize_text_field($input['kutykurutty_1']);
        }

        return $sanitary_values;
    } */

    public function wph_field_callback($field)
    {
        $value = get_option($field['id']);
        $placeholder = '';
        if (isset($field['placeholder'])) {
            $placeholder = $field['placeholder'];
        }
        switch ($field['type']) {

            default:
                printf(
                    '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />',
                    $field['id'],
                    $field['type'],
                    $placeholder,
                    esc_attr($value)
                );
        }
        if (isset($field['desc'])) {
            if ($desc = $field['desc']) {
                printf('<p class="description">%s </p>', $desc);
            }
        }
    }
}
new PMOptions_Settings_Page();



/*****
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * ****/



/**
 * Generated by the WordPress Option Page generator
 * at http://jeremyhixon.com/wp-tools/option-page/
 */

class PMOptions
{
    private $pm_options_2_options;

    public function __construct()
    {
        add_action('admin_menu', array($this, 'pm_options_2_add_plugin_page'));
        add_action('admin_init', array($this, 'pm_options_2_page_init'));
    }

    public function pm_options_2_add_plugin_page()
    {
        add_menu_page(
            'PM Options 2', // page_title
            'PM Options 2', // menu_title
            'manage_options', // capability
            'pm-options-2', // menu_slug
            array($this, 'pm_options_2_create_admin_page'), // function
            'dashicons-admin-generic', // icon_url
            2 // position
        );
    }

    public function pm_options_2_create_admin_page()
    {
        $this->pm_options_2_options = get_option('pm_options_2_option_name'); ?>

<div class="wrap">
    <h2>PM Options 2</h2>
    <p></p>
    <?php settings_errors(); ?>

    <form method="post" action="options.php">
        <?php
                settings_fields('pm_options_2_option_group');
                do_settings_sections('pm-options-2-admin');
                submit_button();
                ?>
    </form>
</div>
<?php }

    public function pm_options_2_page_init()
    {
        register_setting(
            'pm_options_2_option_group', // option_group
            'pm_options_2_option_name', // option_name
            array($this, 'pm_options_2_sanitize') // sanitize_callback
        );

        add_settings_section(
            'pm_options_2_setting_section', // id
            'Settings', // title
            array($this, 'pm_options_2_section_info'), // callback
            'pm-options-2-admin' // page
        );

        add_settings_section(
            'pm_options_2_setting_section_2', // id
            'Settings2', // title
            array($this, 'pm_options_2_section_info'), // callback
            'pm-options-2-admin' // page
        );

        add_settings_field(
            'homepage_article_limit_0', // id
            'Homepage Article Limit', // title
            array($this, 'homepage_article_limit_0_callback'), // callback
            'pm-options-2-admin', // page
            'pm_options_2_setting_section' // section
        );

        add_settings_field(
            'kutykurutty_1', // id
            'Kutykurutty', // title
            array($this, 'kutykurutty_1_callback'), // callback
            'pm-options-2-admin', // page
            'pm_options_2_setting_section' // section
        );
    }

    public function pm_options_2_sanitize($input)
    {
        $sanitary_values = array();
        if (isset($input['homepage_article_limit_0'])) {
            $sanitary_values['homepage_article_limit_0'] = sanitize_text_field($input['homepage_article_limit_0']);
        }

        if (isset($input['kutykurutty_1'])) {
            $sanitary_values['kutykurutty_1'] = sanitize_text_field($input['kutykurutty_1']);
        }

        return $sanitary_values;
    }

    public function pm_options_2_section_info()
    {
    }

    public function homepage_article_limit_0_callback()
    {
        printf(
            '<input class="regular-text" type="text" name="pm_options_2_option_name[homepage_article_limit_0]" id="homepage_article_limit_0" value="%s">',
            isset($this->pm_options_2_options['homepage_article_limit_0']) ? esc_attr($this->pm_options_2_options['homepage_article_limit_0']) : ''
        );
    }

    public function kutykurutty_1_callback()
    {
        printf(
            '<input class="regular-text" type="text" name="pm_options_2_option_name[kutykurutty_1]" id="kutykurutty_1" value="%s">',
            isset($this->pm_options_2_options['kutykurutty_1']) ? esc_attr($this->pm_options_2_options['kutykurutty_1']) : ''
        );
    }
}
/* if (is_admin())
    $pm_options_2 = new PMOptions(); */

/* 
 * Retrieve this value with:
 * $pm_options_2_options = get_option( 'pm_options_2_option_name' ); // Array of All Options
 * $homepage_article_limit_0 = $pm_options_2_options['homepage_article_limit_0']; // Homepage Article Limit
 * $kutykurutty_1 = $pm_options_2_options['kutykurutty_1']; // Kutykurutty
 */
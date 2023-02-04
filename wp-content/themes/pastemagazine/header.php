<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php pm_schema_type(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="viewport-fit=cover, width=device-width, initial-scale=1.0, maximum-scale=2.0">

    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <?php
    global $templateURI, $isFrontPage;
    $templateURI = get_template_directory_uri();
    $isFrontPage = is_front_page();
    ?>

    <link rel="icon" sizes="196x196" href="<?php echo $templateURI; ?>/img/mobile-icon-196.png">
    <link rel="icon" sizes="128x128" href="<?php echo $templateURI; ?>/img/mobile-icon-128.png">
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo $templateURI; ?>/img/mobile-icon-57.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo $templateURI; ?>/img/mobile-icon-72.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo $templateURI; ?>/img/mobile-icon-144.png">

    <link rel="shortcut icon" href="<?php echo $templateURI; ?>/img/favicon.ico" type="image/vnd.microsoft.icon">

    <?php

    if (is_singular('article')) {

        global $post, $article_cat, $ytID;
        $featuredURL = @array_shift(wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'));

        if (empty($ytID) && !empty($featuredURL) && !stristr($featuredURL, 'p-logo-main') && !stristr($featuredURL, 'p_icon_lead')) {
            echo '<link rel="preload" as="image" type="image/jpeg" href="' . $featuredURL . '">';
        }

        if (/* $article_cat == 'studio' && */!empty($ytID)) {
            echo '<link rel="preload" as="image" type="image/jpeg" href="https://i3.ytimg.com/vi/' . $ytID . '/maxresdefault.jpg">';
        }
    }
    ?>


    <script>
    var pm = {
        servers: {
            pastemagazine: "www.pastemagazine.com",
            wolfgangs: "www.wolfgangs.com"
        },
        version: "39.2022.10209.11821.2",
        page: {
            "type": "Landing",
            "mediaType": "Unknown",
            "articleType": "Unknown",
            "articleId": -1,
            "author": null,
            "services": "/pastemagazine.svc/pastemagazine/",
            "isMobile": false,
            "isIos": false,
            "videoEmbedPlayerId": 30540132,
            "testGroup": null,
            "adblocker": true
        }
    };
    </script>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-V3K4873RKC"></script>
    <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-V3K4873RKC');
    </script>

    <script>
    window.pageloadTimer = new Date();
    window.templateDirURI = '<?php echo $templateURI; ?>';
    window.ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';
    window.isFrontPage = <?php echo (int) $isFrontPage; ?>;
    </script>

    <meta property="fb:admins" content="679468950">
    <meta property="fb:admins" content="1185614656">
    <meta property="fb:app_id" content="127550380613969">
    <meta property="fb:pages" content="18512903240">

    <meta name="p:domain_verify" content="8f36922bc91ba221f10d82c407d8b6a9">

    <link rel="dns-prefetch" href="https://hb.adtelligent.com">
    <link rel="dns-prefetch" href="https://ib.adnxs.com">
    <link rel="dns-prefetch" href="https://web.hb.ad.cpe.dotomi.comm">
    <link rel="dns-prefetch" href="https://ap.lijit.com">

      <script>
    var googletag = googletag || {};
    googletag.cmd = googletag.cmd || [];
    </script>



    <?php wp_head(); ?>


    <script>
    let retries = 0,
        alreadySent = false;
    window.gtmEventSend = function() {
        if (dataLayer && !alreadySent) {
            alreadySent = true;
            dataLayer.push({
                'event': 'detectioncomplete'
            });
        } else if (retries < 6 && !alreadySent) {
            retries++;
            setTimeout(gtmEventSend, 250);
        }
    }
    </script>


</head>

<body <?php body_class(); ?>>

    <?php wp_body_open(); ?>

    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P8HM6Q9" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>

    <div id="top_leaderboard"></div>

    <?php get_template_part('partials/header-navigation'); ?>
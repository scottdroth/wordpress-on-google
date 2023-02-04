<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php pm_schema_type(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="viewport-fit=cover, width=device-width, initial-scale=1.0, maximum-scale=2.0">
    
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    
    <?php $templateURI = get_template_directory_uri(); ?>
    
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
            echo '<link rel="preload" as="image" type="image/jpeg" href="'.$featuredURL.'">';
        }
        
        if (/* $article_cat == 'studio' && */!empty($ytID)) {
            echo '<link rel="preload" as="image" type="image/jpeg" href="https://i3.ytimg.com/vi/'.$ytID.'/maxresdefault.jpg">';
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
    
    

    <!-- Google Tag Manager -->
    <script id="gtm-script">
    (function(w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
            'gtm.start': new Date().getTime(),
            event: 'gtm.js'
        });
        var f = d.getElementsByTagName(s)[0],
            j = d.createElement(s),
            dl = l != 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src =
            '//www.googletagmanager.com/gtm.js?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-P8HM6Q9');
    </script>
    <!-- End Google Tag Manager -->


    <script>
    window.pageloadTimer = new Date();
    window.templateDirURI = '<?php echo $templateURI; ?>';
    window.ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';
    window.isFrontPage = <?php echo (int) is_front_page(); ?>;
    </script>

    <!-- <link rel="standout"
        href="https://www.pastemagazine.com/movies/in-theaters/the-10-best-movies-in-theaters-right-now/">
    <link rel="alternate" type="application/rss+xml" title="Recent Paste Articles"
        href="https://www.pastemagazine.com/recent.xml">
    <link rel="canonical" href="https://www.pastemagazine.com/"> -->

    <!-- <meta name="description"
        content="Paste Magazine is your source for the best music, movies, TV, comedy, videogames, books, comics, craft beer, politics and more. Discover your favorite albums and films.">
    <meta name="keywords"
        content="Paste Magazine, discover, music, movies, films, books, videogames, tv, television, music magazine, movie magazine, indie music, indie rock, netflix movies, hbo series, music news, music reviews, tv reviews, movie news, independent music, independent film, amazon prime, hbo max movies, hulu, apple tv, disney, indie rock, best music, best albums, best movies, best films, best tv, best videogames, best beer, best videos, artist interviews, filmmaker interviews, band interviews, album reviews, food, travel, comedy, whiskey, tech news, digital music, online music, movie reviews, entertainment news, videos, music videos, music downloads, comics, anime, book reviews, videogame reviews, videogame news, film festival coverage, live performances, new music discovery, gadgets, music blogs, film blogs, sxsw, politics"> -->

    <meta property="fb:admins" content="679468950">
    <meta property="fb:admins" content="1185614656">
    <meta property="fb:app_id" content="127550380613969">
    <meta property="fb:pages" content="18512903240">


    <!-- <meta property="og:image" content="https://www.pastemagazine.com/pastemagazine.img/logo.jpg">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.pastemagazine.com/">
    <meta property="og:title" content="Paste Magazine: Your Guide to the Best Music, Movies &amp; TV Shows">
    <meta property="og:site_name" content="pastemagazine.com">
    <meta property="og:description"
        content="Paste Magazine is your source for the best music, movies, TV, comedy, videogames, books, comics, craft beer, politics and more. Discover your favorite albums and films."> -->

    <meta name="p:domain_verify" content="8f36922bc91ba221f10d82c407d8b6a9">

    <link rel="dns-prefetch" href="https://hb.adtelligent.com">
    <link rel="dns-prefetch" href="https://ib.adnxs.com">
    <link rel="dns-prefetch" href="https://web.hb.ad.cpe.dotomi.comm">
    <link rel="dns-prefetch" href="https://ap.lijit.com">

    <!-- <link rel="preload" as="script"
        href="https://www.pastemagazine.com/pastemagazine.js/javascript.js?cb=39.2022.10209.11821.2"> -->


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
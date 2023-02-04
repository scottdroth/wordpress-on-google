<?php
global $article_cat, $article_type;
$thumbnailURL = get_post_meta($post->ID, 'pmleadimg', true);
$permalink = get_the_permalink($post->ID);
?>
<li class="grid-x grid-padding-x">
    <a class="large-3 medium-3 cell image youtube" href="<?php echo $permalink; ?>"
        aria-label="<?php echo $post->post_title; ?>">
        <picture class="lazyload">
            <source media="(min-width:40em)" srcset="<?php echo $thumbnailURL; ?>">
            <img src="<?php echo $thumbnailURL; ?>" alt="<?php echo $post->post_title; ?>" />
        </picture>
    </a>

    <a class="auto cell copy-container" href="<?php echo $permalink; ?>">
        <?php echo !$article_cat ? '<b class="type">' . implode(", ", wp_get_object_terms($post->ID, 'article-category', ['fields' => 'names'])) . '</b>' : ''; ?>
        <b class="title"><?php echo $post->post_title; ?></b>
        <?php echo $article_type === 'reviews' && ($rating = get_post_meta($post->ID, 'additional_article_fields_rating', true)) !== '' ? '<b class="rating"><i class="text">Rating</i><i class="number">' . $rating . '</i></b>' : ''; ?>
        <?php echo $article_type !== 'reviews' ? '<b class="byline">By ' . get_the_author() . '</b>' : ''; ?>
        <?php echo $article_type !== 'reviews' ? '<b class="time">' . get_the_date('F j, Y | g:ia') . '</b>' : ''; ?>
    </a>

</li>
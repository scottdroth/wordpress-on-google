<?php global $article_cat, $artist, $isFrontPage; ?>

<div class="expanded site-navigation" id="site_navigation" data-pagetype="Landing">
    <div class="grid-container">
        <header id="master-header" class="grid-y landing top-bar">
            <div class="top-bar-container grid-x">
                <div class="top-bar-right hide-for-large cell">
                    <a href="#" class="menu" aria-label="Section Menu" data-toggle="small-navigation-menu"
                        aria-controls="small-navigation-menu" data-is-focus="false"
                        data-yeti-box="small-navigation-menu" aria-haspopup="true" aria-expanded="false"
                        id="32rdph-dd-anchor">Sections <i class="icon-menu">
                            <!--icon-->
                        </i>
                    </a>
                    <ul class="vertical dropdown-pane menu has-position-left has-alignment-top"
                        id="small-navigation-menu" data-dropdown="vd5cbf-dropdown" data-position="left"
                        data-alignment="top" data-close-on-click="true" data-auto-focus="false"
                        aria-labelledby="32rdph-dd-anchor" aria-hidden="true" data-yeti-box="small-navigation-menu"
                        data-resize="small-navigation-menu" data-events="resize" style="top: 0px; left: -536.78px;">
                        <li>
                            <a href="/music" class="large-1 cell">Music</a>
                        </li>
                        <li>
                            <a href="/movies" class="large-1 cell">Movies</a>
                        </li>
                        <li>
                            <a href="/tv" class="large-1 cell">TV</a>
                        </li>
                        <li>
                            <a href="/comedy" class="large-1 cell">Comedy</a>
                        </li>
                        <li>
                            <a href="/games" class="large-1 cell">Games</a>
                        </li>
                        <li>
                            <a href="/books" class="large-1 cell">Books</a>
                        </li>
                        <li>
                            <a href="/food" class="large-1 cell">Food</a>
                        </li>
                        <li>
                            <a href="/drink" class="large-1 cell">Drink</a>
                        </li>
                        <li>
                            <a href="/travel" class="large-1 cell">Travel</a>
                        </li>
                        <li>
                            <a href="/tech" class="large-1 cell">Tech</a>
                        </li>
                        <li class="empty">
                            <!--to force order-->
                        </li>
                        <li class="hide-not-large">
                            <a href="/studio">Video</a>
                        </li>
                        <li class="hide-not-large">
                            <a href="/newsletter">Newletter</a>
                        </li>
                        <li class="hide-not-large">
                            <a href="/movies/best-movies-on-netflix">Netflix</a>
                        </li>
                        <li class="hide-not-large">
                            <a href="/movies/best-movies-amazon-prime">Amazon</a>
                        </li>
                        <li class="hide-not-large">
                            <a href="/movies/hbo-max/best-movies-on-hbo-max">HBO Max</a>
                        </li>
                        <li class="hide-not-large">
                            <a href="/movies/hulu/best-movies-hulu">Hulu</a>
                        </li>
                        <li class="hide-not-large">
                            <a href="/movies/disney-plus/best-movies-on-disney">Disney+</a>
                        </li>
                    </ul>

                </div>
                <div class="title-bar grid-x cell align-bottom">
                    <<?php echo $isFrontPage ? 'h1' : 'div'; ?> class="large-1 medium-1 small-5 cell logo">
                        <a href="/">
                            <svg id="Layer_1" data-name="Layer 1" viewBox="0 0 650 179.07"
                                <?php echo $isFrontPage ? 'alt="Paste Magazine: Your Guide to the Best Music, Movies & TV Shows"' : ''; ?>>
                                xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <style>
                                    path {
                                        fill: #363636;
                                    }
                                    </style>
                                </defs>
                                <title>Paste Magazine: Your Guide to the Best Music, Movies & TV Shows</title>
                                <path
                                    d="M-23,430c32.81.05,65.63-.15,98.44.3A80.51,80.51,0,0,1,109.31,438c15.81,7.56,23.79,20.31,24.42,37.65.64,17.58-6.38,30.94-22.21,39.32-12.48,6.61-25.85,9.62-39.84,10-10.33.24-20.66.22-31,0-3.54-.06-4.54,1.16-4.52,4.58q.18,30.48,0,61c0,3.52,1.15,4.63,4.62,4.52,7.44-.21,14.89-.06,22.62-.06V604H-23v-9H2.77V440H-23Zm60.22,9.06a13.11,13.11,0,0,0-.87,2.61q-.08,35.46-.13,70.93c0,2.71,1.33,3.34,3.73,3.32,10.66-.1,21.32,0,32-.13,10.26-.12,17.35-5.09,21.22-14.56,6.16-15.11,6.39-30.46,1.29-45.86-2.46-7.44-7.51-13.3-15.4-14.09C65.05,439.88,51,439.73,37.22,439.06Z"
                                    transform="translate(23 -427.93)"></path>
                                <path
                                    d="M173,607c-1.92-.47-3.84-1-5.78-1.41-21.1-4.74-33.91-22-32.63-44,1.3-22.15,11.19-38,33.16-44.78q18.81-5.85,37.67-11.57c27-8.15,26.39-16.89,24.43-39.1-1.23-14-11.62-23.39-27-24.85-7.66-.72-15.63.06-23.29,1.27-10.07,1.59-17.22,7.85-21.11,17.23-2.34,5.64-3.58,11.74-5.62,17.53-.55,1.58-2.2,2.79-3.34,4.17-.64-1.57-1.81-3.13-1.83-4.71-.24-12.65-.19-25.31-.39-38-.06-4.11,1.88-5.37,5.7-5.81,13-1.5,26-3.34,39.07-5.05h12c2.77.44,5.53.9,8.31,1.32,24.22,3.64,37,17.29,38.5,42.25.87,14.77.68,29.62.75,44.43.11,22,0,44,0,66,0,10.76,3,14.22,13.61,15.94,1.8.3,3.73.29,5.38,1s2.76,1.93,4.12,2.94c-1.49.72-2.95,2-4.46,2-6,.24-12,.1-18,.1h-7c-8.12,0-8.12,0-11.14-7.58-2.7-6.76-3.07-6.86-9-2.73a73.15,73.15,0,0,1-32.34,12.32c-1.63.22-3.21.7-4.82,1.06Zm57.46-67.13c0-7.66.12-15.32,0-23-.1-5.29-2.06-6.57-7-4.74q-23.65,8.73-47.23,17.64a27.25,27.25,0,0,0-15,13.27c-6.19,11.83-3.68,32.87,5.89,42.16a29.83,29.83,0,0,0,14.07,7.72c14.31,2.87,27.42-1,38.66-10.35a28.14,28.14,0,0,0,10.66-22.73C230.43,553.2,230.47,546.54,230.46,539.87Z"
                                    transform="translate(23 -427.93)"></path>
                                <path
                                    d="M340,428c5.45,1.42,11.08,2.34,16.28,4.39,5.36,2.1,10.29,5.3,15.59,8.11,4.72-2.23,6-10.39,13.77-9.36v46.52h-7.35c-1-11.74-7.82-20.39-16.15-28.12-15.35-14.24-40.23-14.54-54.46-.65-13.06,12.75-9.67,29.86,7.55,36,11.9,4.25,24.14,7.51,36.22,11.24,2.86.88,5.72,1.79,8.52,2.84,18.88,7.15,30.54,20.4,33.34,40.62,2.61,18.82-.69,36.21-14.23,50.55-15.06,16-41.2,20.94-63.45,11.93-5.35-2.17-10.37-5.15-15.86-7.91-5.51,2.14-8,10.35-16.2,9.86V552.42h6.89c1.48,4.45,2.78,9.06,4.52,13.5,9.84,25.07,36.86,36.49,61.64,26.16,14.51-6.06,23-18,22.48-31.63-.23-6.55-2.39-12.5-8-15.86A184,184,0,0,0,346.35,532c-8.28-3.4-17.07-5.56-25.65-8.22-32.73-10.17-41-41.25-32.19-66.28,5.66-16.11,18.2-24.91,34.68-28.37,1.61-.34,3.21-.75,4.81-1.13Z"
                                    transform="translate(23 -427.93)"></path>
                                <path
                                    d="M616.8,570.12l9.63,8.64c-1.67,1.84-3.11,3.58-4.7,5.17-26.38,26.35-73.09,29.56-102.91,7.1-18.08-13.62-25.64-34-19.75-53.56,4.15-13.81,13.89-22.8,26.18-29.39,4-2.17,8.27-4,12.83-6.13-3.89-2.17-7.46-3.86-10.71-6-16.35-10.85-20-30.8-8.66-46.7,19.31-27.11,69.5-27.3,88.45.07-2.52,2.4-5.11,4.84-7.91,7.5-6.75-7.16-14.49-11.61-24.11-12.4-19.19-1.58-33.29,12.48-30.33,30.33,1.87,11.24,11.67,19.61,24.5,20.4,6.61.41,13.27.07,20.27.07v12.41H579.17a53,53,0,0,0-28.53,7.76c-25.12,15.62-25.59,49.34-.95,65.74,19.49,13,47,10,63.31-6.86C614.16,573.05,615.26,571.8,616.8,570.12Z"
                                    transform="translate(23 -427.93)"></path>
                                <path
                                    d="M420.11,481.56h-20V469.69H420V431.85l33.92-2.18v39.89h30.23v12H454.07v5.93q0,42.72,0,85.46a51.83,51.83,0,0,0,.28,6c.61,5.39,2.2,10.45,8.14,11.92,6.18,1.52,12.19.54,16.27-4.84,2.86-3.76,4.91-8.15,7.46-12.51l5,2.61c-1.74,13.29-14.42,26.2-28.52,27.55-8.66.82-17.59,0-26.28-1s-13.72-7.05-15.25-15.61a68.72,68.72,0,0,1-1-11.9c-.08-29,0-58,0-87Z"
                                    transform="translate(23 -427.93)"></path>
                            </svg>
                        </a>
                    </<?php echo $isFrontPage ? 'h1' : 'div'; ?>>
                    <div class="grid-x article-shares-links four">
                        <a href="https://www.pastemagazine.com/"
                            data-title="Paste Magazine: Your Guide to the Best Music, Movies &amp; TV Shows"
                            class="small-3 icon-facebook">Share</a>
                        <a href="https://www.pastemagazine.com/"
                            data-title="Paste Magazine: Your Guide to the Best Music, Movies &amp; TV Shows"
                            class="small-3 icon-twitter">Tweet</a>
                        <a href="https://www.pastemagazine.com/"
                            data-title="Paste Magazine: Your Guide to the Best Music, Movies &amp; TV Shows"
                            class="small-3 icon-reddit-alien only-view-large">Submit</a>
                        <a href="https://www.pastemagazine.com/"
                            data-title="Paste Magazine: Your Guide to the Best Music, Movies &amp; TV Shows"
                            data-image="https://www.pastemagazine.com/pastemagazine.img/logo.jpg"
                            class="small-3 icon-pinterest">Pin</a>
                    </div>
                </div>
                <nav>
                    <div class="show-for-medium cell horizontal">
                        <a href="/studio"
                            class="icon-video large-auto <?php echo is_tax('article-type', 'studio') ? 'a"' : ''; ?>">Video</a>
                        <b class="div">
                            <!--divider-->
                        </b>
                        <a href="/newsletter"
                            class="icon-newspaper large-auto <?php echo is_page('newsletter') ? 'a"' : ''; ?>">Newsletter</a>
                        <b class="div">
                            <!--divider-->
                        </b>
                        <a href="/movies/best-movies-on-netflix"
                            class="icon-newspaper large-auto <?php echo $artist && $artist === 'netflix' ? 'a"' : ''; ?>">Netflix</a>
                        <b class="div">
                            <!--divider-->
                        </b>
                        <a href="/movies/best-movies-amazon-prime"
                            class="icon-newspaper large-auto <?php echo $artist && $artist === 'amazon-prime' ? 'a"' : ''; ?>">Amazon</a>
                        <b class="div">
                            <!--divider-->
                        </b>
                        <a href="/movies/hbo-max/best-movies-on-hbo-max"
                            class="icon-newspaper large-auto <?php echo $artist && $artist === 'hbo-max' ? 'a"' : ''; ?>">HBO
                            Max</a>
                        <b class="div">
                            <!--divider-->
                        </b>
                        <a href="/movies/hulu/best-movies-hulu"
                            class="icon-newspaper large-auto <?php echo $artist && $artist === 'hulu' ? 'a"' : ''; ?>">Hulu</a>
                        <b class="div">
                            <!--divider-->
                        </b>
                        <a href="/movies/disney-plus/best-movies-on-disney"
                            class="icon-newspaper large-auto <?php echo $artist && $artist === 'disney-plus' ? 'a"' : ''; ?>">Disney+</a>
                    </div>
                    <a href="/music"
                        <?php echo $article_cat && $article_cat === 'music' ? 'class="active"' : ''; ?>>Music</a>
                    <a href="/movies"
                        <?php echo $article_cat && $article_cat === 'movies' ? 'class="active"' : ''; ?>>Movies</a>
                    <a href="/tv" <?php echo $article_cat && $article_cat === 'tv' ? 'class="active"' : ''; ?>>TV</a>
                    <a href="/comedy"
                        <?php echo $article_cat && $article_cat === 'comedy' ? 'class="active"' : ''; ?>>Comedy</a>
                    <a href="/games"
                        <?php echo $article_cat && $article_cat === 'games' ? 'class="active"' : ''; ?>>Games</a>
                    <a href="/books"
                        <?php echo $article_cat && $article_cat === 'books' ? 'class="active"' : ''; ?>>Books</a>
                    <a href="/food"
                        <?php echo $article_cat && $article_cat === 'food' ? 'class="active"' : ''; ?>>Food</a>
                    <a href="/drink"
                        <?php echo $article_cat && $article_cat === 'drink' ? 'class="active"' : ''; ?>>Drink</a>
                    <a href="/travel"
                        <?php echo $article_cat && $article_cat === 'travel' ? 'class="active"' : ''; ?>>Travel</a>
                    <a href="/tech"
                        <?php echo $article_cat && $article_cat === 'tech' ? 'class="active"' : ''; ?>>Tech</a>
                    <a class="large-auto medium-auto cell search closed icon-search" href="/search"
                        aria-label="Search">Search
                        <!--search-->
                    </a>
                    <div class="top-bar-right show-for-large cell">
                        <a href="#" class="menu" aria-label="Section Menu" data-toggle="large-navigation-menu"
                            aria-controls="large-navigation-menu" data-is-focus="false"
                            data-yeti-box="large-navigation-menu" aria-haspopup="true" aria-expanded="false"
                            id="ojytlk-dd-anchor">
                            <i class="icon-menu">
                                <!--icon-->
                            </i>
                        </a>
                        <ul class="vertical dropdown-pane menu has-position-left has-alignment-top"
                            id="large-navigation-menu" data-dropdown="0iyk7c-dropdown" data-position="left"
                            data-alignment="top" data-close-on-click="true" data-auto-focus="false"
                            aria-labelledby="ojytlk-dd-anchor" aria-hidden="true" data-yeti-box="large-navigation-menu"
                            data-resize="large-navigation-menu" data-events="resize">
                            <li>
                                <a href="/studio">Video</a>
                            </li>
                            <li>
                                <a href="/newsletter">Newsletter</a>
                            </li>
                            <li>
                                <a href="/movies/best-movies-on-netflix">Netflix</a>
                            </li>
                            <li>
                                <a href="/movies/best-movies-amazon-prime">Amazon</a>
                            </li>
                            <li>
                                <a href="/movies/hbo-max/best-movies-on-hbo-max">HBO Max</a>
                            </li>
                            <li>
                                <a href="/movies/hulu/best-movies-hulu">Hulu</a>
                            </li>
                            <li>
                                <a href="/movies/disney-plus/best-movies-on-disney">Disney+</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
        <div class="header-top-ad cell center dfp">
        </div>
    </div>
</div>
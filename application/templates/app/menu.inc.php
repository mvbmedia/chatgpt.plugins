<!-- navigation -->
<nav id="navigation" class="<?= ($data['status'] ?? ''); ?>" role="navigation">
    <div class="wrapper">
        <!-- logo -->
        <div class="logo">
            <a href="/" aria-label="logo"><img src="/logo.svg" alt="logo <?= $this->website['name']; ?>" width="195" height="48" loading="eager"></a>
        </div>
        <!-- menu -->
        <div class="menu">
            <ul>
                <li><a href="/reviews/" class="title"><img src="/images/icons/menu/reviews.svg" width="16" height="16" alt="reviews"> <span>Plugins</span></a></li>
                <li><a href="/<?= $this->configuration['article_slug']; ?>/" class="title"><img src="/images/icons/menu/guide.svg" width="16" height="16" alt="artikelen"> <span>Guide</span></a></li>
                <li>
                    <a href="#" class="title dropdown"><img src="/images/icons/menu/categories.svg" width="16" height="16" alt="categorieën"> <span>Categories</span></a>
                    <?php
                    # display categories
                    if (!empty($this->categories)) {
                        echo '<ul>';

                        foreach ($this->categories as $row) {
                            echo '<li><a href="/' . $row['slug'] . '/" class="title"><img src="/images/icons/category/' . $row['icon'] . '" alt="' . $row['name'] . '" width="24" height="24" loading="lazy"> <span class="title">' . $row['name'] . '</span> </a></li>';
                        }

                        echo '</ul>';
                    }
                    ?>
                </li>
            </ul>
        </div>
        <!-- search bar -->
        <div class="search">
            <input type="text" name="search" class="search-query" placeholder="What are you looking for?" autocomplete="off">
            <div id="searchR" class="search-results"></div>
        </div>
        <!-- search faq -->
        <div class="menu second-menu">
            <ul>
                <li><a href="/faq/" class="title"><img src="/images/icons/menu/faq-2.svg" width="24" height="24" alt="Frequently Asked Questions" loading="eager"></a></li>
            </ul>
        </div>
        <!-- menu hide button -->
        <div class="menu-hide">
            <button data-menu="hide" aria-label="menu">
                <img src="/images/icons/menu/close.svg" width="24" height="24" alt="view menu">
            </button>
        </div>
        <!-- menu show button -->
        <div class="menu-show">
            <button data-menu="show" aria-label="menu">
                <img src="/images/icons/menu/menu.svg" width="24" height="24" alt="view menu">
            </button>
        </div>
        <!-- menu search button -->
        <div class="menu-search">
            <button data-search="show" class="menu-search-show" aria-label="search">
                <img src="/images/icons/menu/magnifier.svg" width="24" height="24" alt="display search">
            </button>
            <button data-search="hide" class="menu-search-hide" aria-label="search">
                <img src="/images/icons/menu/close.svg" width="24" height="24" alt="close search">
            </button>
        </div>
    </div>
</nav>

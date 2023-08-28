<?php
# initiate modal
$app = new Reviews;

# display head
$this->template->display('app/head', [
    # meta tags
    'meta_title' => 'We Reviewed All ChatGPT Plugins | Plugin.Support',
    'meta_description' => 'The Ultimate guide for up-to-date information about any ChatGPT Plugin for OpenAI\'s GPT-4, Test all plugins, compare costs and all pros and cons.',
    'meta_keywords' => 'chatgpt plugins, chat gpt plugins, OpenAI plugins, gpt-4 plugins, test plugins',

    # open graph tags
    'og_image' => '/images/content/reviews.png',

    # canonical
    'canonical' => '/reviews/'
]);

# display menu
$this->template->display('app/menu', [
    'status' => 'active'
]);
$nonce = isset($_SESSION['nonce']) ? $_SESSION['nonce'] : '';
?>
<script nonce="<?= $nonce ?>">
    document.addEventListener('DOMContentLoaded', function() {
        /* category selector */
        document.querySelectorAll(".selector").forEach(selector => {
            selector.addEventListener('click', function() {
                document.querySelectorAll(".category").forEach(category => {
                    category.checked = this.checked;
                });
            });
        });

        /* filter selector */
        document.querySelectorAll(".category").forEach(category => {
            category.addEventListener('click', function() {
                if (!this.checked) {
                    document.querySelector(".selector").checked = false;
                }
            });
        });

        document.querySelectorAll('input[type=checkbox], select[name="sort"]').forEach(element => {
            element.addEventListener('change', function() {
                /* set sort */
                var sort = document.querySelector('select[name="sort"]').value;

                /* set categories */
                var category = Array.from(document.querySelectorAll(".category:checked")).map(element => ({
                    name: element.name,
                    value: element.value
                }));
                var categories = JSON.stringify(category);

                /* set features */
                var feature = Array.from(document.querySelectorAll(".feature:checked")).map(element => ({
                    name: element.name,
                    value: element.value
                }));
                var features = JSON.stringify(feature);

                /* set form */
                var data = new FormData();
                data.append('features', features);
                data.append('categories', categories);
                data.append('sort', sort);

                /* upload request */
                fetch('/api/offers/', {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.text())
                    .then(response => {
                        /* display content */
                        var subjects = document.querySelector('.subjects');
                        subjects.style.display = 'none';
                        subjects.innerHTML = response;
                        subjects.style.display = 'block';
                    })
                    .catch(error => console.error(error));
            });
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    this.parentElement.classList.add("active");
                } else {
                    this.parentElement.classList.remove("active");
                }
            });
        });

        document.querySelector('#category-all').addEventListener('change', function() {
            var checkboxes = document.querySelectorAll('#categories input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
                if (this.checked) {
                    checkbox.parentElement.classList.add('active');
                } else {
                    checkbox.parentElement.classList.remove('active');
                }
            });
        });
    });

    window.addEventListener('load', function() {
        document.getElementById('showMoreButton').addEventListener('click', showMore);
    });

    function showMore() {
        var element = document.getElementById("categories");
        element.classList.toggle("showList");
    }
</script>
<!-- banner -->
<header id="banner" class="mt-70">
    <div class="wrapper">
        <div class="details">
            <h1>Legit reviews about the <?= count($this->data['offers']); ?> best <strong>ChatGPT Plugins</strong> for OpenAI's GPT-4</h1>
        </div>
    </div>
</header>
<!-- offers -->
<main id="offers">
    <div class="wrapper">
        <div class="row">
            <aside class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                <section class="details banner">
                    <h2><img src="/images/icons/search-filter.svg" width="23" height="23" alt="zoekfilter" />Search results for <strong>chatgpt</strong>?</h2>
                    <div class="col-lg-0 col-md-12 col-sm-12 col-xs-12">
                        <button class="btn btn-filter btn-large btn-round" data-toggle="filter">Filter</button>
                        <select name="sort" class="btn btn-sort btn-large btn-round">
                            <option value="popular">Popularity</option>
                            <option value="recommendations">Recommends</option>
                            <option value="score">Ratings</option>
                            <option value="new">New Chat GPT's</option>
                        </select>
                    </div>
                </section>
                <section id="cont">
                    <div id="sidebar" class="filter box">
                        <h3>Plugins <span>(<?= count($this->data['offers']); ?>)</span></h3>
                        <div class="content">
                            <span class="title">Short</span>
                            <select name="sort">
                                <option value="popular">Popularity</option>
                                <option value="recommendations">Recommends</option>
                                <option value="score">Ratings</option>
                                <option value="new">New offers</option>
                            </select>
                            <span class="title">Categories</span>
                            <ul id="categories">
                                <li><input type="checkbox" name="selector" id="category-all" class="selector" value="all" /> <span class="description"><label for="category-all">All categories</label></span></li>
                                <?php
                                foreach ($this->data['categories'] as $row) {
                                    $altText = isset($row['img_alt']) ? $row['img_alt'] : $row['name'];
                                    echo '<li><input type="checkbox" name="' . $row['ID'] . '" id="category-' . $row['ID'] . '" class="category" value="' . $row['name'] . '" /> <span class="description"><label for="category-' . $row['ID'] . '">' . $altText . '</label></span></li>';
                                }
                                ?>
                            </ul>
                            <?php
                            # display filter
                            foreach ($this->data['filter'] as $result) {
                                # empty filter
                                if (empty($result['filter'])) {
                                    continue;
                                }

                                # display title
                                echo '<span class="title">' . $result['name'] . '</span>';

                                # set filter list
                                echo '<ul class="' . strtolower(str_replace(' ', '-', $result['name'])) . '">';

                                # display filter values
                                foreach ($result['filter'] as $row) {
                                    echo '<li><input type="checkbox" name="' . $row['name'] . '" id="feature-' . $row['ID'] . '" class="feature" value="' . $row['ID'] . '" /> <span class="description"><label for="feature-' . $row['ID'] . '">' . $row['name'] . '</label></span></li>';
                                }

                                echo '</ul>';
                            }
                            ?>
                        </div>
                    </div>
                </section>
            </aside>
            <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12" id="conti">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="details">
                            <!-- <h2><img src="/images/icons/top-rated.svg" alt="recommendation" width="23" height="23"/>Top <?= count($this->data['offers']); ?> best <span>Chat GPT apps</span></h2> -->
                            <button type="button" class="btn-advertising-policy" data-toggle="modal-advertising-policy"><span>Advertisements</span></button>
                            <div class="modal-advertising-policy">
                                <p>This website is a free, online resource that strives to provide useful content and comparison options to its visitors. We do not operate any chatgpt sites ourselves, making us more independent than most chat GPT comparison sites. Please note, however, that the operator of this site receives advertising compensation from companies appearing on the site, and that such compensation will affect the location in which the companies (and/or their services) are presented, but generally of a similar magnitude, so it may have little reasonable impact on the rating/score assigned to them. To the extent that there are ratings on this site, they are determined by our subjective opinion, consumer experience, and based on a methodology that combines our analysis of each brand's market share and reputation, each brand's conversion rates, the fees paid to us, and the overall consumer interest. Company listings on this page do not imply endorsement. Except as expressly set forth in our Terms of Use, all representations and warranties with respect to the information on this page are disclaimed. The information on this site, including price, is subject to change at any time.</p>
                                <button type="button" data-toggle="modal-advertising-policy">Close</button>
                            </div>
                        </div>
                        <section class="subjects" itemscope itemtype="https://schema.org/Product">
                        <?php foreach ($this->data['offers'] as $key => $row) {
                            ?>
                                <article class="card" data-id="<?= $row['ID']; ?>" itemprop="itemOffered" itemscope itemtype="https://schema.org/Product">
                                    <!-- details -->
                                    <div class="container">
                                        <span class="position"><?= $row['position']; ?></span>
                                        <div class="symbol">
                                            <img src="/images/plugins/symbols/<?= $row['symbol']; ?>" width="110" height="110" alt="logo <?= $row['name']; ?>" loading="lazy" data-subject="<?= $row['ID']; ?>" itemprop="image" />
                                        </div>
                                        <div class="options">
                                            <button type="button" class="btn-compare <?= ($this->functions->compare($row['ID']) ? 'active' : ''); ?>" data-compare="<?= $row['ID']; ?>" data-title="add">
                                                <img src="/images/icons/compare.svg" alt="Compare" loading="lazy" />
                                            </button>
                                            <h2 class="title" data-position="<?= $row['position']; ?>" data-title="Meest gekozen &#9733;" data-subject="<?= $row['ID']; ?>" itemprop="name"><?= $row['name']; ?></h2>
                                            <div class="grade">
                                                <span class="title"><?= $this->functions->score($row['score']); ?></span>
                                                <div class="score">
                                                    <span class="amount"><img src="/images/icons/star.svg" alt="Stars rating" loading="lazy" /> <?= $row['rating']; ?></span>
                                                    <!-- tooltip -->
                                                    <div class="tooltip">
                                                        <!-- consumer engagement -->
                                                        <div class="item">
                                                            <div class="information">
                                                                <p class="description" itemprop="description">
                                                                    <?= $this->functions->shorter($row['summary'], 240); ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="item">
                                                            <div class="icon"><img src="/images/icons/consumer-engagement.svg" alt="Consumer engagement" loading="lazy" /></div>
                                                            <div class="information">
                                                                <span class="name">Consumer engagement</span>
                                                                <p class="description"><strong>Choiced by <?= $this->data['tooltip'][$row['ID']]['clicks']; ?></strong> people in the last 30 days</p>
                                                            </div>
                                                            <div class="score">
                                                                <span itemprop="reviewCount"><?= $this->data['tooltip'][$row['ID']]['consumer']; ?></span>
                                                            </div>
                                                        </div>
                                                        <!-- customer feedback -->
                                                        <div class="item">
                                                            <div class="icon"><img src="/images/icons/customer-feedback.svg" alt="Consumer feedback" /></div>
                                                            <div class="information">
                                                                <span class="name">Customers feedback</span>
                                                                <p class="description">Very good with <strong>ease of use</strong>
                                                                </p>
                                                            </div>
                                                            <div class="score">
                                                                <span itemprop="reviewCount"><?= $this->data['tooltip'][$row['ID']]['feedback']; ?></span>
                                                            </div>
                                                        </div>
                                                        <!-- brand reputation -->
                                                        <div class="item">
                                                            <div class="icon"><img src="/images/icons/brand-reputation.svg" alt="brand reputation" loading="lazy" /></div>
                                                            <div class="information">
                                                                <span class="name">Brand reputation</span>
                                                                <p class="description">Based on <strong>web analyses</strong>
                                                                </p>
                                                            </div>
                                                            <div class="score">
                                                                <span itemprop="reviewCount"><?= $this->data['tooltip'][$row['ID']]['brand']; ?></span>
                                                            </div>
                                                        </div>
                                                        <!-- benefits -->
                                                        <div class="item">
                                                            <div class="icon"><img src="/images/icons/benefits.svg" alt="Features &amp; Benefits" loading="lazy" /></div>
                                                            <div class="information">
                                                                <span class="name">Features &amp; Benefits</span>
                                                                <p class="description">Product review</p>
                                                            </div>
                                                            <div class="score">
                                                                <span itemprop="reviewCount"><?= $this->data['tooltip'][$row['ID']]['benefits']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="item">
                                                            <a href="/how-we-score/">Learn how we score</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="details">
                                            <div class="review" itemprop="review" itemscope itemtype="https://schema.org/Review">
                                                <div class="rating" data-evaluate="<?= $row['slug']; ?>" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                                                    <span class="stars"></span>
                                                    <span class="stars-filled" data-stars="<?= round($row['score']); ?>"></span>
                                                    <meta itemprop="worstRating" content="1">
                                                    <meta itemprop="ratingValue" content="<?= round($row['score'] / 2 / 10); ?>">
                                                    <meta itemprop="bestRating" content="5">
                                                </div>                                                
                                                    <a href="/reviews/<?= $row['slug']; ?>/" class="prompts ratings" data-title="Prompts for <?= $row['name']; ?>" itemprop="url">
                                                        <span><?= $row['prompts']; ?></span> prompts
                                                    </a>
                                                <a href="/evaluate/<?= $row['slug']; ?>/" class="reviews" data-title="Read Honest consumer experiences from other <?= $row['name']; ?> users" itemprop="url"><span itemprop="ratingCount"><?= $row['ratings']; ?></span> ratings</a>
                                            </div>
                                        </div>
                                        <div class="options scnd">
                                            <div class="rating" data-evaluate="<?= $row['slug']; ?>">
                                                <span class="stars"></span>
                                                <span class="stars-filled" data-width="<?= round($row['score']); ?>" data-stars="<?= round($row['score'] / 2 / 10); ?>"></span>
                                            </div>
                                            <div class="links" data-subject="<?= $row['ID']; ?>">
                                                <a href="/reviews/<?= $row['slug']; ?>/" target="_blank" class="btn btn-subject btn-large btn-round" rel="nofollow" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                                    <!--<span itemprop="price"><?= isset($row['price']) ? $row['price'] : '0.00'; ?></span> -->
                                                    <!--<meta itemprop="priceCurrency" content="<?= isset($row['currency']) ? $row['currency'] : 'USD'; ?>" />-->
                                                    <!--<meta itemprop="availability" content="https://schema.org/InStock" />-->
                                                    <span>Get Plugin</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- outlinks -->
                                    <div class="outlinks">
                                        <span class="slogan"><?= $row['name']; ?> - <?= $row['slogan']; ?></span>
                                        <a href="/offer/<?= $row['slug']; ?>/" target="_blank" class="btn btn-subject btn-large btn-wide" rel="nofollow" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                            <!-- <span itemprop="price"><?= isset($row['price']) ? $row['price'] : '0.00'; ?></span> -->
                                            <!-- <meta itemprop="priceCurrency" content="<?= isset($row['currency']) ? $row['currency'] : 'USD'; ?>" /> -->
                                            <!-- <meta itemprop="availability" content="https://schema.org/InStock" /> -->
                                            <span itemprop="url">Get plugin</span>
                                        </a>
                                        <button type="button" class="btn btn-features" data-features="<?= $row['ID']; ?>" rel="nofollow">Show details</button>
                                    </div>
                                    <!-- features -->
                                    <div class="features" itemprop="description">
                                        <?php foreach ($row['features'] as $feature) { ?>
                                            <div class="item">
                                                <img src="/images/icons/features/<?= $feature['icon']; ?>" alt="<?= $feature['title']; ?>" class="icon" loading="lazy" />
                                                <div class="details">
                                                    <span class="name"><?= $feature['title']; ?></span>
                                                    <span class="description"><?= (!empty($feature['content']) ? $feature['content'] : '-'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <a href="/reviews/<?= $row['slug']; ?>/" class="reviews" itemprop="url"><?= $row['name']; ?> reviews</a>
                                    </div>
                                </article>
                            <?php } ?>
                        </section>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
$this->template->display('app/footer');

<?php
# initiate modal
$app = new Category;

# display head
$this->template->display('app/head', [
    # meta tags
    'meta_title' => $this->data['category']['meta_title'],
    'meta_description' => substr($this->data['category']['meta_description'], 0, 158),
    'meta_keywords' => $this->data['category']['meta_keywords'],

    # open graph data
    'og_image' => '/images/themes/' . $this->data['category']['thumbnail'],

    # canonical
    'canonical' => '/' . $this->data['category']['slug'] . '/',
    
    # css style
    'stylesheet' => 'categories.css',

    # rich snippet
    'category' => [
        'name' => $this->data['category']['name'],
        'title' => $this->data['category']['title'],
        'description' => $this->data['category']['description'],
        'url' => '/' . $this->data['category']['slug'] . '/',
        'icon' => $this->data['category']['icon'],
        'thumbnail' => $this->data['category']['thumbnail'],
        'review' => $this->data['category']['description'],
        'published' => $this->functions->timestamp($this->data['category']['created'])
    ]
]);

# display menu
$this->template->display('app/menu');

# display header
$this->template->display('app/header', [
    'name' => $this->data['category']['title'],
    'description' => $this->data['category']['description'],
    'thumbnail' => $this->data['category']['thumbnail'],
    'theme' => $this->data['category']['theme'],
    'buttons' => true
]);
?>
<!-- top offers -->
<div id="top-offers">
    <div class="wrapper">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="last-updated">
                    <span class="title">Updated <strong><?= $this->functions->timestamp(time(), 'M Y'); ?></strong></span>
                </div>
                <div class="container flex-box flex-justify-center">
                    <?php
                    foreach ($this->data['top-offers'] as $key => $row) {
                        ?>
                        <div class="card" data-id="<?= $row['ID']; ?>">
                            <?php
                            if (!$key) {
                                echo '<span class="ribbon"><img src="/images/content/favicon.svg" width="22" height="22" alt="Popular" />Popular choice</span>';
                            }
                            ?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-6 col-xs-6 col-xxs-5">
                                    <div class="grade"><?= $row['rating']; ?></div>
                                    <div class="thumbnail flex-box flex-align-center flex-justify-center" data-subject="<?= $row['ID']; ?>">
                                        <img src="/images/plugins/symbols/<?= $row['symbol']; ?>" width="85" height="85" alt="logo <?=$row['name'];?>" loading="lazy"/>
                                    </div>
                                    <div class="rating" data-evaluate="<?= $row['slug'] ;?>">
                                        <span class="stars"></span>
                                        <span class="stars-filled" style="width: <?= $row['score'] ;?>%"></span>
                                    </div>
                                    <a href="/reviews/<?= $row['slug']; ?>/" class="ratings"><?= ($row['ratings']); ?>x rated</a>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-6 col-xs-6 col-xxs-7">
                                    <div class="details">
                                        <p class="description" data-subject="<?= $row['ID']; ?>"><?= $row['slogan']; ?></p>
                                        <a href="/offer/<?= $row['slug']; ?>/" target="_blank" class="btn btn-subject btn-medium btn-round" rel="nofollow">Visit website</a>
                                        <a href="/reviews/<?= $row['slug']; ?>/" class="reviews"><?= $row['name']; ?> review</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- breadcrumbs -->
<nav id="breadcrumbs" aria-label="Breadcrumb">
    <div class="wrapper">
         <ol itemscope itemtype="https://schema.org/BreadcrumbList">
            <?php
            $breadcrumbs = [
                [
                    'name' => 'Home',
                    'href' => '/'
                ],
                [
                    'name' => $this->data['category']['name'],
                    'href' => $this->data['category']['slug'] . '/'
                ]
            ];

            foreach ($breadcrumbs as $index => $breadcrumb) :
            ?>
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="<?= $breadcrumb['href'] ?>">
                        <span itemprop="name"><?= $breadcrumb['name'] ?></span>
                    </a>
                    <meta itemprop="position" content="<?= $index + 1 ?>">
                </li>
             <?php endforeach; ?>
        </ol>
    </div>
</nav>
<!-- offers -->
<div id="offers">
    <div class="wrapper">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="details">
                    <h2>Top <?= count($this->data['offers']); ?> best <span><?= $this->data['category']['name']; ?></span></h2>
                    <!--<button type="button" class="btn-advertising-policy" data-toggle="modal-advertising-policy"><span>Advertising Policy</span></button>
                    <div class="modal-advertising-policy">
                        <p>This website is a free online resource that aims to provide useful content and comparison options to its visitors. We do not directly manage any ChatGPT solutions, making us more independent than most ChatGPT comparison sites. Please note that the operator of this site receives compensation from companies that appear on the site, and that such compensation may impact the location in which the companies (and/or their services) are presented, although it typically has little effect on their overall rating or score. Ratings on this site are based on our subjective opinion, consumer experiences, and a methodology that combines our analysis of each brand's market share, reputation, conversion rates, fees, and overall consumer interest. Business listings on this page do not imply endorsement. We disclaim all representations and warranties regarding the information on this page, except as expressly set forth in our Terms of Use. Information on this site, including pricing, is subject to change at any time.</p>
                        <button type="button" data-toggle="modal-advertising-policy">Close</button>
                    </div>-->
                </div>
                <?php
                # display offers
                foreach ($this->data['offers'] as $key => $row) {
                    ?>
                    <div class="card" data-id="<?= $row['ID']; ?>">
                        <div class="container category">
                            <span class="position"><?= $row['position']; ?></span>
                            <div class="thumbnail">
                                <img src="/images/plugins/symbols/<?= $row['symbol']; ?>" alt="<?=$row['slogan'];?>" width="110" height="110" data-subject="<?= $row['ID']; ?>" loading="lazy"/>
                                <span class="brand"><img src="/images/plugins/symbols/<?= $row['symbol']; ?>" alt="logo <?= $row['name']; ?>" width="114" height="55" loading="lazy"/></span>
                                <button type="button" class="btn-compare <?=($this->functions->compare($row['ID']) ? 'active' : '');?>" data-compare="<?=$row['ID'];?>" data-title="Added"><img src="/images/icons/plus.svg" width="14" height="14"  alt="plus icon" loading="lazy"/>Compare</button>
                            </div>
                            <div class="details">
                                <span class="title" data-position="<?= $row['position']; ?>" data-title="Meest gekozen &#9733;" data-subject="<?= $row['ID']; ?>"><?= $row['name']; ?></span>
                                <div class="review">
                                    <div class="rating" data-evaluate="<?= $row['slug'] ;?>">
                                        <span class="stars"></span>
                                        <span class="stars-filled" style="width: <?= $row['score'] ;?>%"></span>
                                    </div>
                                    <a href="/evaluate/<?= $row['slug']; ?>/" class="ratings" data-title="Give your opinion about this site!"><?= ($row['ratings']); ?> ratings</a>
                                    <a href="/reviews/<?= $row['slug']; ?>/" class="reviews" data-title="Read honest consumer experiences from real users"><?= $row['reviews']; ?> reviews</a>
                                </div>
                                <p class="description" data-subject="<?= $row['ID']; ?>">
                                    <?= $this->functions->shorter($row['summary'], 400); ?>
                                    <a href="/reviews/<?= $row['slug']; ?>/" class="reviews"><?= $row['name']; ?> reviews</a>
                                </p>
                            </div>
                            <div class="options">
                                <div class="grade">
                                    <span class="title" data-subject="<?= $row['ID']; ?>"><?= $this->functions->score($row['score']); ?></span>
                                    <div class="score">
                                        <span class="amount"><?= $row['rating']; ?></span>
                                        <!-- tooltip -->
                                        <div class="tooltip">
                                            <!-- consumer engagement -->
                                            <div class="item">
                                                <div class="icon"><img src="/images/icons/consumer-engagement.svg" width="18" height="18" alt="Consumer involvement" loading="lazy"/></div>
                                                <div class="information">
                                                    <span class="name">Consumer involvement</span>
                                                    <p class="description"><strong>Chosen by <?= $this->data['tooltip'][$row['ID']]['clicks']; ?></strong> people in the past 30 days</p>
                                                </div>
                                                <div class="score">
                                                    <span><?= $this->data['tooltip'][$row['ID']]['consumer']; ?></span>
                                                </div>
                                            </div>
                                            <!-- customer feedback -->
                                            <div class="item">
                                                <div class="icon"><img src="/images/icons/customer-feedback.svg" width="18" height="18" alt="Customer feedback" loading="lazy"/></div>
                                                <div class="information">
                                                    <span class="name">Customer feedback</span>
                                                    <p class="description">Great at <strong>Ease of Use</strong></p>
                                                </div>
                                                <div class="score">
                                                    <span><?= $this->data['tooltip'][$row['ID']]['feedback']; ?></span>
                                                </div>
                                            </div>
                                            <!-- brand reputation -->
                                            <div class="item">
                                                <div class="icon"><img src="/images/icons/brand-reputation.svg" width="18" height="18" alt="Brand reputation" loading="lazy"/></div>
                                                <div class="information">
                                                    <span class="name">Brand reputation</span>
                                                    <p class="description">Based on <strong>webanalyses</strong></p>
                                                </div>
                                                <div class="score">
                                                    <span><?= $this->data['tooltip'][$row['ID']]['brand']; ?></span>
                                                </div>
                                            </div>
                                            <!-- benefits -->
                                            <div class="item">
                                                <div class="icon"><img src="/images/icons/benefits.svg" width="18" height="18" alt="kenmerken en voordelen" loading="lazy"/>
                                                </div>
                                                <div class="information">
                                                    <span class="name">Features and benefits</span>
                                                    <p class="description">Product review</p>
                                                </div>
                                                <div class="score">
                                                    <span><?= $this->data['tooltip'][$row['ID']]['benefits']; ?></span>
                                                </div>
                                            </div>
                                            <div class="item">
                                                <a href="/hoe-we-rangschikken/">Learn how we score</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="rating" data-evaluate="<?= $row['slug'] ;?>">
                                    <span class="stars"></span>
                                    <span class="stars-filled" style="width: <?= $row['score'] ;?>%"></span>
                                </div>
                                <div class="links">
                                    <a href="/offer/<?= $row['slug']; ?>/" target="_blank" class="btn btn-subject btn-large btn-round" rel="nofollow">Visit site</a>
                                </div>
                            </div>
                        </div>
                        <!-- outlinks -->
                        <div class="outlinks">
                            <span class="slogan"><?= $row['name']; ?> - <?= $row['slogan']; ?></span>
                            <a href="/offer/<?= $row['slug']; ?>/" target="_blank" class="btn btn-subject btn-large btn-wide" rel="nofollow">Visit site</a>
                            <button type="button" class="btn-compare <?=($this->functions->compare($row['ID']) ? 'active' : '');?>" data-compare="<?=$row['ID'];?>" data-title="Toegevoegd"><img src="/images/icons/plus.svg" alt="plus icon" width="18" height="18" loading="lazy"/>Compare</button>
                            <button type="button" class="btn btn-features" data-features="<?= $row['ID']; ?>" rel="nofollow">Show details</button>
                        </div>
                        <div class="features">
                            <?php
                            # display features
                            foreach ($row['features'] as $feature) {
                                ?>
                                <div class="item">
                                    <img src="/images/icons/features/<?= $feature['icon']; ?>" width="36" height="36" alt="<?=$feature['title'];?>" class="icon" loading="lazy"/>
                                    <div class="details">
                                        <span class="name"><?= $feature['title']; ?></span>
                                        <span class="description"><?= (!empty($feature['content']) ? $feature['content'] : '-'); ?></span>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            <a href="/reviews/<?= $row['slug']; ?>/" class="reviews"><?= $row['name']; ?> reviews</a>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <button class="btn btn-secondary btn-large btn-round btn-center" data-href="/reviews/">Show all plugins</button>
            </div>
        </div>
    </div>
</div>
<div id="longtext" class="content">
    <div class="row">
        <div class="wrapper">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <Guide>
                    <?= $this->data['category']['content']; ?>
                </Guide>
            </div>
        </div>
    </div>
</div>
<?php
$this->template->display('app/footer');

<?php
# initiate modal
$app = new Articles;
$datatype = 'guide';

# display head
$this->template->display('app/head', [
    # meta tags
    'meta_title' => $this->data['article']['meta_title'],
    'meta_description' => substr($this->data['article']['meta_description'], 0, 158),
    'meta_keywords' => $this->data['article']['meta_keywords'],

    # open graph tags
    'og_image' => '/images/guide/' . $this->data['article']['thumbnail'],

    # canonical
    'canonical' => '/' . $this->template->configuration['article_slug'] . '/' . $this->data['article']['slug'] . '/',

    # CSS
    'stylesheet' => 'articles.css'
]);

# display menu
$this->template->display('app/menu', [
    'status' => 'active'
]);
?>
<?php $nonce = isset($_SESSION['nonce']) ? $_SESSION['nonce'] : ''; ?>
<!-- banner -->
<header id="banner" class="article-banner">
    <div class="wrapper">
        <div class="details">
            <h1><?= $this->data['article']['title']; ?></h1>
        </div>
    </div>
</header>
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
                    'name' => 'guide',
                    'href' => '/' . $this->template->configuration['article_slug'] . '/'
                ],
                [
                    'name' => $this->data['article']['title'],
                    'href' => '/' . $this->template->configuration['article_slug'] . '/' . $this->data['article']['slug'] . '/'
                ]
            ];

            foreach ($breadcrumbs as $index => $breadcrumb) :
            ?>
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="<?= $breadcrumb['href'] ?>">
                        <span itemprop="name"><?= $breadcrumb['name'] ?></span>
                    </a>
                    <meta itemprop="position" content="<?= $index + 1 ?>" />
                </li>
             <?php endforeach; ?>
        </ol>
    </div>
</nav>
<!-- article -->
<div id="article">
    <div class="wrapper">
        <div class="row">
            <main class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <article class="content" itemscope itemtype="https://schema.org/Article">
                    <!-- details -->
                    <section class="details">
                        <h2 itemprop="headline"><?= $this->data['article']['title']; ?></h2>
                        <span class="head-subLine"></span>
                        <div data-type="application/hydration-marker">
                        <div class="by-author-modular" itemprop="author" itemscope itemtype="https://schema.org/Person">
                            <img src="/images/articles/author/mauritswalters.jpg" width="40" height="40">
                            <a data-testid="link" itemprop="url" href="/authors/maurits/" data-role-position="0" target="_self"> Door<!-- --> <!-- --><span itemprop="name">Maurits<!-- --> <!-- -->Walters</span></a>
                            <time class="date" itemprop="dateModified" datetime="<?= date('Y-m-d\TH:i:s', strtotime($this->functions->timestamp($this->data['article']['updated']))); ?>"><?= date('d-m-Y', strtotime($this->functions->timestamp($this->data['article']['updated']))); ?></time></span>
                        </div>
                        <div class="thumbnail" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                            <img src="/images/articles/<?= $this->data['article']['thumbnail']; ?>" alt="<?= $this->data['article']['title']; ?>" itemprop="contentUrl"/>
                            <meta itemprop="url" content="/images/articles/<?= $this->data['article']['thumbnail']; ?>"/>
                            <meta itemprop="width" content="800"/>
                            <meta itemprop="height" content="600"/>
                        </div>
                        <span class="quote quote-blue"><?= $this->data['article']['meta_description']; ?></span>
                        <div class="description" itemprop="articleBody">
                            <?= $this->data['article']['content']; ?>
                        </div>
                    </section>
                    <section class="options">
                        <div id="reaction-container" data-page-id="<?= $this->data['article']['ID']; ?>" data-page-type="<?= $datatype ?>" class="intercom-reaction-picker -mb-4 -ml-4 -mr-4 mt-6 rounded-card sm:-mb-2 sm:-ml-1 sm:-mr-1 sm:mt-8" dir="ltr">
                            <div class="intercom-reaction-prompt">Was this article helpful?</div>
                            <button class="intercom-reaction" aria-label="Disappointed Reaction" tabindex="0" data-reaction-text="disappointed" aria-pressed="false">
                                <span title="Disappointed">😞</span>
                            </button>
                            <button class="intercom-reaction" aria-label="Neutral Reaction" tabindex="0" data-reaction-text="neutral" aria-pressed="false">
                                <span title="Neutral">😐</span>
                            </button>
                            <button class="intercom-reaction" aria-label="Smiley Reaction" tabindex="0" data-reaction-text="smiley" aria-pressed="false">
                                <span title="Smiley">😃</span>
                            </button>
                        </div>
                    </section>
                </article>
                <div class="content">
                    <div class="details">
                        <div class="description">
                            <h3>Sources &amp; references</h3>
                            <h4>Resources related to: "<?= $this->data['article']['title']; ?>"</h4>
                            <p><?= $this->data['article']['meta_keywords']; ?></p>
                        </div>
                    </div>
                </div>
            </main>
            <aside class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <section class="index">
                    <h3>Table of contents</h3>
                    <ul>
                        <?php
                        foreach ($this->data['article']['index'] as $key => $row) {
                            echo '<li><span class="title">' . $row[2] . '</span></li>';
                        }
                        ?>
                    </ul>
                </section>
                <section class="top-offers">
                    <h3>Top Plugins on <?= $this->functions->timestamp(time(), 'M Y'); ?></h3>
                    <?php
                    # display top offers
                    foreach ($this->data['top-offers'] as $row) {
                        ?>
                        <div class="item">
                            <picture class="thumbnail" loading="lazy"  data-subject="<?= $row['ID']; ?>" >
                                <source type="image/jpg" srcset="/images/plugins/symbols/30x30/<?= str_replace('.png', '.jpg', $row['symbol']); ?>" media="(max-width: 480px)" loading="lazy">
                                <source type="image/jpg" srcset="/images/plugins/symbols/<?= $row['symbol']; ?>" media="(min-width: 480px)">
                                <img srcset="/images/plugins/symbols/<?= $row['symbol']; ?>" src="/images/plugins/symbols/<?= $row['symbol']; ?>" alt="logo <?= $row['name']; ?>" alt="<?= $row['slug']; ?> app" width="90" height="90" loading="lazy"/>
                            </picture>
                            <div class="details">
                                <span class="title" data-subject="<?= $row['ID']; ?>"><?= $row['name']; ?></span>
                                <p><?= $row['slogan']; ?></p>
                                <a href="/offer/<?= $row['slug']; ?>/" target="_blank" class="btn btn-primary btn-medium btn-round" rel="noopener">Show more</a>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </section>
                <section class="top-offers top-articles">
                    <h3>Popular guide on <?= $this->functions->timestamp(time(), 'M Y'); ?></h3>
                    <?php
                    foreach ($this->data['articles'] as $row) {
                        ?>
                        <div class="item" data-subject="<?= $row['ID']; ?>" data-href="/<?= $this->template->configuration['article_slug']; ?>/<?= $row['slug']; ?>/">
                            <div class="details">
                                <span class="title"><?=$row['title'];?></span>
                                <span class="date"><?= $this->functions->timestamp($row['created']); ?></span>
                            </div>
                            <div class="thumbnail">
                                <img src="/images/guide/366x198/<?= $row['thumbnail']; ?>" alt=" <?=$row['title'];?>" width="140" height="92" />
                            </div>
                        </div>
                    <?php } ?>
                </section>
            </aside>
        </div>
    </div>
</div>
<?php
$this->template->display('app/footer');

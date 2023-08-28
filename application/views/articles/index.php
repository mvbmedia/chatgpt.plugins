<?php
# initiate modal
$app = new Articles;

# display head
$this->template->display('app/head', [
    # meta tags
    'meta_title' => $this->template->configuration['article_title'],
    'meta_description' => substr($this->template->configuration['article_description'], 0, 158),
    'meta_keywords' => $this->template->configuration['article_keywords'],

    # open graph tags
    'og_image' => '/images/content/guide.png',

    # canonical
    'canonical' => '/' . $this->template->configuration['article_slug'] . '/',
    
    # CSS
    'stylesheet' => 'articles.css'
]);

# display menu
$this->template->display('app/menu', [
    'status' => 'active'
]);

# display header
$this->template->display('app/header', [
    'name' => $this->template->configuration['article_name'],
    'description' => 'Discover how ChatGPT\'s plugins can help you expand and grow your business', 
    'theme' => 'advisor',
    'thumbnail' => 'guide.svg',
    'statistics' => [
        'experts' => $this->data['experts'],
        'words' => round($this->data['words'] / 1000, 1) . 'K'
    ]
]);
?>
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
<!-- guide -->
<div id="article">
    <div class="wrapper">
        <div class="row">
            <h2><?= $this->template->configuration['article_title']; ?></h2>
            <span class="head-subLine"></span>
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <div class="row">
                    <?php
                    # display guide
                    foreach ($this->data['articles'] as $row) {
                        ?>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <div class="card" data-href="/<?= $this->template->configuration['article_slug']; ?>/<?= $row['slug']; ?>/">
                                <span class="date"><?= $this->functions->timestamp($row['created']); ?></span>
                                <div class="thumbnail">
                                    <img src="/images/guide/366x198/<?= $row['thumbnail']; ?>" alt="<?= $row['title']; ?>"/>
                                </div>
                                <div class="details">
                                    <h2><a href="/<?= $this->template->configuration['article_slug']; ?>/<?= $row['slug']; ?>/"><?= $row['title']; ?></a></h2>
                                    <a href="/<?= $this->template->configuration['article_slug']; ?>/<?= $row['slug']; ?>/">Read more</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }

                    # display pages
                    echo $app->pages();
                    ?>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <section class="top-offers">
                    <h3>Top Plugins on <?= $this->functions->timestamp(strtotime('last Sunday -7 days')); ?></h3>
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
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="container">
                    <div class="card">
                        <p class="description">
                            <?= $this->template->configuration['article_description'] ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->template->display('app/footer');

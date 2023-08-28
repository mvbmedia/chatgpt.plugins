<?php
# initiate modal
$app = new Compare;

# display head
$this->template->display('app/head', [
    # meta tags
    'meta_title' => '',
    'meta_description' => '',
    'meta_keywords' => '',

    # open graph tags
    'og_image' => '',

    # canonical
    'canonical' => '/compare/'
]);

# display menu
$this->template->display('app/menu', [
    'status' => 'active'
]);
?>
<!-- banner -->
<header id="banner">
    <div class="wrapper">
        <div class="details">
            <h1>Compare all OpenAI ChatGPT Plugins Right Now</h1>
        </div>
    </div>
</header>
<!-- breadcrumbs -->
<nav id="breadcrumbs" class="breadcrumbs" aria-label="Breadcrumb">
    <div class="wrapper">
         <ol itemscope itemtype="https://schema.org/BreadcrumbList">
            <?php
            $breadcrumbs = [
                [
                    'name' => 'Home',
                    'href' => '/'
                ],
                [
                    'name' => 'Compare',
                    'href' => '/compare/'
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
<!-- compare -->
<main id="compare">
    <div class="wrapper">
        <div class="subjects">
            <?php
            foreach($this->data['offers'] as $key => $row)
            {
            ?>
                <div class="card">
                    <span class="position" data-compare="<?= $row['ID'] ;?>">&#10006;</span>
                    <div class="thumbnail flex-box flex-align-center flex-justify-center" data-subject="<?= $row['ID']; ?>">
                        <img src="/images/plugins/symbols/<?= $row['symbol']; ?>" alt="logo <?= $row['name']; ?>" loading="eager"/>
                        <span class="brand"><?= $row['name']; ?></span>
                    </div>
                    <a href="/offer/<?=$row['slug'];?>/" class="btn btn-primary btn-medium btn-wide btn-round btn-shadow" target="_blank">Get plugin</a>
                    <div class="item">
                        <h2>Overview</h2>
                        <ul class="general">
                            <li>
                                <span class="slogan"><?= $row['slogan'] ;?></span>
                            </li>
                            <li>
                                <span class="title">Our score</span>
                                <span class="description"><?= $row['rating']; ?></span>
                            </li>
                            <li>
                                <span class="title">Installs today</span>
                                <span class="description"><?= $row['registrations']; ?></span>
                            </li>
                        </ul>
                    </div>
                    <?php
                    # display ratings
                    if (!empty($row['ratings'])){
                    ?>
                    <div class="item">
                        <h3>Recommendations</h3>
                        <ul class="ratings">
                        <?php
                        foreach($row['ratings'] as $key => $rating){
                            ?>
                            <li>
                                <span class="title"><?=$rating['title'];?></span>
                                <div class="rating" data-evaluate="<?= $row['slug'] ;?>">
                                    <span class="stars"></span>
                                    <span class="stars-filled" style="width: <?= $rating['score'] ;?>%"></span>
                                </div>
                            </li>
                            <?php
                        }
                        ?>
                        </ul>
                    </div>
                    <?php
                    }

                    # display features
                    if (!empty($row['features'])){
                    ?>
                    <div class="item">
                        <h4>Characteristics</h4>
                        <ul class="features">
                            <?php
                            # display features
                            foreach ($row['features'] as $feature) {
                                ?>
                                <li>
                                    <span class="title">
                                        <img src="/images/icons/features/<?= $feature['icon']; ?>" alt="<?=$feature['title'];?>" class="icon"/>
                                        <?= $feature['title']; ?>
                                    </span>
                                    <span class="description"><?= (!empty($feature['content']) ? $feature['content'] : '-'); ?></span>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                    <?php
                    }

                    # display summaries
                    if (!empty($row['summaries'])){
                    ?>
                    <div class="item">
                        <h5>In the short</h5>
                        <ul class="summaries">
                            <?php
                            # display features
                            foreach ($row['summaries'] as $summary) {
                                ?>
                                <li>
                                    <span class="title"><?= $summary['title']; ?></span>
                                    <span class="description"><?= (!empty($summary['description']) ? $summary['description'] : '-'); ?></span>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                    <?php
                    }
                    ?>
                    <a href="/offer/<?=$row['slug'];?>/" class="btn btn-primary btn-medium btn-wide btn-round btn-shadow" target="_blank">start for free</a>
                    <a href="/reviews/<?= $row['slug']; ?>/" class="reviews"><?= $row['name']; ?> reviews</a>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</main>
<?php
$this->template->display('app/footer');

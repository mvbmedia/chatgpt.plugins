<?php
# initiate modal
$app = new Index;

# display head
$this->template->display('app/head', [
    # meta tags
    'meta_title' => 'Error 404',
    'meta_description' => 'Error 404',

    # open graph tags
    'og_image' => '/images/themes/index.png',

    # css style
    'stylesheet' => '404.css',

    # canonical
    'canonical' => '/404/'
]);

# display menu
$this->template->display('app/menu');

# display header
$this->template->display('app/header', [
    'name' => 'Error 404', 
    'description' => 'This page is not longer avalible!', 
    'buttons' => true
]);

?>
<!-- top offers -->
<div id="top-offers">
    <div class="wrapper">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="last-updated">
                    <span class="title">Updated <strong><?= $this->functions->timestamp(time(), '%B %Y'); ?></strong></span>
                </div>
                <div class="container flex-box flex-justify-center">
                    <?php
                    if ($result = $app->top_offers()){
                        foreach ($result as $key => $row) {
                            ?>
                            <div class="card" data-id="<?= $row['ID']; ?>">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
                                        <div class="grade"><?= $row['rating']; ?></div>
                                        <div class="thumbnail flex-box flex-align-center flex-justify-center" data-subject="<?= $row['ID']; ?>">
                                            <img src="/images/plugins/symbols/<?= $row['logo']; ?>" alt="logo <?=$row['name'];?>" width="65" height="65" loading="eager"/>
                                        </div>
                                        <div class="rating" data-evaluate="<?= $row['slug'] ;?>">
                                            <span class="stars"></span>
                                            <span class="stars-filled" style="width: <?= $row['score'] ;?>%"></span>
                                        </div>
                                        <a href="/reviews/<?= $row['slug']; ?>/" class="ratings"><?= ($row['ratings']); ?> x rated</a>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
                                        <div class="details">
                                            <p class="description"><?= $row['slogan']; ?></p>
                                            <a href="/reviews/<?= $row['slug']; ?>/" target="_blank"  class="btn btn-subject btn-medium btn-round" rel="nofollow">Get plugin</a>
                                            <a href="/reviews/<?= $row['slug']; ?>/" class="reviews"><?= $row['name']; ?> review</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                <button class="btn btn-secondary btn-large btn-round btn-center" data-href="/reviews/">Show all plugins</button>
            </div>
        </div>
    </div>
</div>

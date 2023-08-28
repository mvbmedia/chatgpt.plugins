<?php
# display offers
if (!empty($result)) {
    # set tooltip
    $tooltip = $this->functions->tooltip();

    # display offers
    foreach ($result as $key => $row) {
        ?>
        <div class="card" data-id="<?= $row['ID']; ?>">
            <!-- details -->
            <div class="container">
                <span class="position"><?= $row['position']; ?></span>
                <div class="symbol">
                    <img src="/images/symbols/110x110/<?= $row['symbol']; ?>" width="110" height="110" alt="logo <?=$row['name'];?>" loading="lazy" data-subject="<?= $row['ID']; ?>" />
                    <button type="button" class="btn-compare <?=($this->functions->compare($row['ID']) ? 'active' : '');?>" data-compare="<?=$row['ID'];?>" data-title="Toegevoegd"><img src="/images/icons/plus.svg" alt="" />Vergelijken</button>
                </div>
                <div class="details">
                    <span class="title" data-position="<?= $row['position']; ?>" data-title="Meest gekozen &#9733;" data-subject="<?= $row['ID']; ?>"><?= $row['name']; ?></span>
                    <div class="review">
                        <div class="rating" data-evaluate="<?= $row['slug'] ;?>">
                            <span class="stars"></span>
                            <span class="stars-filled" style="width: <?= $row['score'] ;?>%"></span>
                        </div>
                        <a href="/reviews/<?= $row['slug']; ?>/" class="ratings" data-title="Geef uw mening over deze site!"><?=$row['ratings'];?> beoordelingen</a>
                        <a href="/reviews/<?= $row['slug']; ?>/" class="reviews" data-title="Lees eerlijke consumenten ervaringen van echte gebruikers"><?=$row['reviews'];?> reviews</a>
                    </div>
                    <p class="description" >
                        <?= $this->functions->shorter($row['summary'], 400); ?>
                        <a href="/reviews/<?= $row['slug']; ?>/" class="reviews"><?= $row['name']; ?> reviews</a>
                    </p>
                </div>
                <div class="options" data-subject="<?= $row['ID']; ?>">
                    <div class="grade">
                        <span class="title"><?= $this->functions->score($row['score']); ?></span>
                        <div class="score">
                            <span class="amount"><?= $row['rating']; ?></span>
                            <!-- tooltip -->
                            <div class="tooltip">
                                <!-- consumer engagement -->
                                <div class="item">
                                    <div class="icon"><img src="/images/icons/consumer-engagement.svg" alt="consumenten betrokkenheid"/></div>
                                    <div class="information">
                                        <span class="name">Consumenten betrokkenheid</span>
                                        <p class="description"><strong>Gekozen door <?= $tooltip[$row['ID']]['clicks']; ?></strong> mensen in de afgelopen 30 dagen</p>
                                    </div>
                                    <div class="score">
                                        <span><?= $tooltip[$row['ID']]['consumer']; ?></span>
                                    </div>
                                </div>
                                <!-- customer feedback -->
                                <div class="item">
                                    <div class="icon"><img src="/images/icons/customer-feedback.svg" alt="klanten feedback"/></div>
                                    <div class="information">
                                        <span class="name">Klanten feedback</span>
                                        <p class="description">Geweldig in <strong>gebruiksgemak</strong></p>
                                    </div>
                                    <div class="score">
                                        <span><?= $tooltip[$row['ID']]['feedback']; ?></span>
                                    </div>
                                </div>
                                <!-- brand reputation -->
                                <div class="item">
                                    <div class="icon"><img src="/images/icons/brand-reputation.svg" alt="merk reputatie"/></div>
                                    <div class="information">
                                        <span class="name">Merk reputatie</span>
                                        <p class="description">Gebaseerd op <strong>webanalyses</strong></p>
                                    </div>
                                    <div class="score">
                                        <span><?= $tooltip[$row['ID']]['brand']; ?></span>
                                    </div>
                                </div>
                                <!-- benefits -->
                                <div class="item">
                                    <div class="icon"><img src="/images/icons/benefits.svg" alt="kenmerken en voordelen"/></div>
                                    <div class="information">
                                        <span class="name">Kenmerken &amp; voordelen</span>
                                        <p class="description">Product review</p>
                                    </div>
                                    <div class="score">
                                        <span><?= $tooltip[$row['ID']]['benefits']; ?></span>
                                    </div>
                                </div>
                                <div class="item">
                                    <a href="/hoe-we-rangschikken/">Lees hoe wij scoren</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="rating" data-evaluate="<?= $row['slug'] ;?>">
                        <span class="stars"></span>
                        <span class="stars-filled" style="width: <?= $row['score'] ;?>%"></span>
                    </div>
                    <div class="links">
                        <a href="/offer/<?= $row['slug']; ?>/" target="_blank" class="btn btn-subject btn-large btn-round" rel="nofollow">Naar de site</a>
                    </div>
                </div>
            </div>
            <!-- outlinks -->
            <div class="outlinks">
                <span class="slogan"><?= $row['name']; ?> <?= $row['slogan']; ?></span>
                <a href="/offer/<?= $row['slug']; ?>/" target="_blank" class="btn btn-subject btn-large btn-wide" rel="nofollow">Naar de website</a>
                <button type="button" class="btn btn-features" data-features="<?= $row['ID']; ?>" rel="nofollow">Bekijk details</button>
            </div>
            <!-- features -->
            <div class="features">
                <?php
                # display features
                foreach ($row['features'] as $feature) {
                    ?>
                    <div class="item">
                        <img src="/images/icons/features/<?= $feature['icon']; ?>" alt="<?=$feature['title'];?>" class="icon"/>
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
} else {
    ?>
    <div class="card">
        <div class="container">
            <div class="details" style="padding: 0;">
                <span class="title">Geen resultaten</span>
                <p class="description">Er zijn geen resultaten gevonden bij jouw zoekopdracht</p>
            </div>
        </div>
    </div>
    <?php
}
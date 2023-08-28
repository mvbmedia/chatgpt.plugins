<?php
# initiate modal
$app = new Reviews;

# display head
$this->template->display('app/head', [
    # meta tags
    'meta_title' => substr($this->data['review']['title']. ', ' .$this->data['review']['name']. ' over ' .$this->data['subject']['name'], 0, 56),
    'meta_description' => substr($this->data['review']['description'] . '.. Lees ' . $this->data['review']['name']. '\'s review over ' .$this->data['subject']['name'] . ' op ChatGPT Plugins', 0, 156),

    # open graph tags
    'og-image' => '/images/icons/users/' . $this->data['review']['thumbnail'],

    # canonical
    'canonical' => '/reviews/' . $this->data['subject']['slug'] . '/' . $this->data['review']['slug'] .'/',

    # rich snippet
    'review' => [
        'name' => $this->data['review']['name'],
        'slogan' => $this->data['review']['title'],
        'description' => $this->data['review']['description'],
        'url' => '/reviews/' . $this->data['subject']['slug'] . '/' . $this->data['review']['slug'] .'/',
        'symbol' => $this->data['subject']['symbol'],
        'thumbnail' => '/images/icons/users/' . $this->data['review']['thumbnail'],
        'review' => $this->data['review']['description'],
        'rating' => [
            'average' => ($this->data['review']['score'] / 20) . '.0',
            'best' => $this->data['subject']['score']['best'],
            'worst' => $this->data['subject']['score']['worst']
        ],
        'published' => $this->functions->timestamp($this->data['review']['created'])
    ]
]);

# display menu
$this->template->display('app/menu', [
    'status' => 'active'
]);
?>
<!-- banner -->
<div id="banner" class="review-banner">
    <div class="wrapper">
        <div class="thumbnail" data-subject="<?= $this->data['subject']['ID']; ?>">
            <img src="/images/symbols/65x65/<?= $this->data['subject']['symbol']; ?>" width="65" height="65" alt="logo <?= $this->data['subject']['name']; ?>" loading="lazy"/>
        </div>
        <div class="details">
            <h1 data-subject="<?= $this->data['subject']['ID']; ?>"><?= $this->data['review']['name']; ?>, <?= $this->data['review']['title']; ?></h1>
            <h2 data-subject="<?= $this->data['subject']['ID']; ?>"><?= $this->data['review']['name']; ?>'s mening over <?= $this->data['subject']['name']; ?></h2>
            <h3 data-subject="<?= $this->data['subject']['ID']; ?>"><?= $this->data['subject']['domain']; ?></h3>
            <div class="rating" data-evaluate="<?= $this->data['subject']['slug'] ;?>">
                <span class="stars"></span>
                <span class="stars-filled" style="width: <?= ($this->data['subject']['score']['average'] * 20); ?>%"></span>
            </div>
        </div>
    </div>
</div>
<!-- breadcrumbs -->
<div id="breadcrumbs">
    <div class="wrapper">
        <?php
        $this->functions->breadcrumbs([
            [
                'name' => 'Reviews',
                'href' => '/reviews/'
            ],
            [
                'name' => $this->data['subject']['name'],
                'href' => '/reviews/' . $this->data['subject']['slug'] . '/'
            ],
            [
                'name' => $this->data['review']['title'],
                'href' => '/reviews/' . $this->data['subject']['slug'] . '/' . $this->data['review']['slug'] . '/'
            ]
        ]);
        ?>
    </div>
</div>
<!-- review -->
<div id="review" class="experience">
    <!-- subject -->
    <div class="wrapper">
        <div class="row">
            <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
                <!-- review -->
                <div class="comments">
                    <!-- average rating -->
                    <h2><?=$this->data['review']['name'];?>'s beoordeling over <?= $this->data['subject']['domain']; ?></h2>
                    <div class="ratings">
                        <table>
                            <?php
                            # progress bar themes
                            $theme = ['green', 'light-green', 'yellow', 'orange', 'red'];

                            # questions
                            foreach ($this->data['review']['ratings'] as $key => $row) {
                                if (!isset($theme[$key])) {
                                    $theme[$key] = '';
                                }

                                echo '<tr>';
                                echo '<td class="title">' . $row['title'] . '</td>';
                                echo '<td class="progress"><div class="progress-bar"><span class="' . $theme[$key] . '" style="width: ' . (round($row['rating']) * 10) . '%"></span></div></td>';
                                echo '<td class="total">' . round($row['rating']) . ' / 10</td>';
                                echo '</tr>';
                            }
                            ?>
                        </table>
                    </div>
                    <!-- review -->
                    <?php
                    # set variables
                    $vote = ($this->data['review']['recommend'] == 'yes' ? 'upvote' : 'downvote');
                    $recommend = ($this->data['review']['recommend'] == 'yes' ? 'aan' : 'niet aan');
                    ?>
                    <div class="review">
                        <div class="details">
                            <div class="thumbnail" data-mood="<?= round($this->data['subject']['rating'] / 20); ?>">
                                <img src="/images/icons/users/<?= $this->data['review']['thumbnail']; ?>" alt="auteur van de beoordeling"/>
                            </div>
                            <div class="person">
                                <span class="name"><?= $this->data['review']['name']; ?> <small>(<?= $this->data['review']['age']; ?> jaar)</small></span>
                                <span class="date"><?= $this->functions->timestamp($this->data['review']['created']); ?></span>
                                <div class="rating" data-evaluate="<?= $this->data['subject']['slug'] ;?>">
                                    <span class="stars"></span>
                                    <span class="stars-filled" style="width: <?= $this->data['review']['score'] ;?>%"></span>
                                </div>
                            </div>
                        </div>
                        <span class="<?= $vote; ?>">Ik raad <?= $this->data['subject']['name']; ?> <?= $recommend; ?></span>
                        <div class="comment">
                            <span class="title"><?= $this->data['review']['title']; ?></span>
                            <p class="description"><?= $this->data['review']['description']; ?></p>
                        </div>
                        <?php
                        # display strengths
                        if (!empty($this->data['review']['strengths'])) {
                            echo '<div class="strengths">';

                            # display pros
                            if (!empty($this->data['review']['strengths']['pros'])) {
                                echo '<ul>';
                                foreach ($this->data['review']['strengths']['pros'] as $strength) {
                                    echo '<li class="pro"><span>' . $strength['title'] . '</span></li>';
                                }
                                echo '</ul>';
                            }

                            # display cons
                            if (!empty($this->data['review']['strengths']['cons'])) {
                                echo '<ul>';
                                foreach ($this->data['review']['strengths']['cons'] as $strength) {
                                    echo '<li class="con"><span>' . $strength['title'] . '</span></li>';
                                }
                                echo '</ul>';
                            }

                            echo '</div>';
                        }
                        ?>
                         <div class="share-icons">
                            <h4><?= $this->data['review']['name'];?>'s Review delen? </h4>
                            <div class="item">
                                <ul>
                                    <li>
                                        <div class="messenger-share-button" data-href="https://www.plugin.support/reviews/<?= $this->data['subject']['slug']; ?>/<?= $this->data['review']['slug']; ?>/" data-layout="button" data-size="large">
                                             <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?kid_directed_site=0&sdk=joey&u=https%3A%2F%2Fwww.plugin.support%2Freviews%2F<?= $this->data['subject']['slug']; ?>%2F<?= $this->data['review']['slug']; ?>%2F&display=popup&ref=plugin&src=share_button" target="_blank" class="fb-xfbml-parse-ignore" data-service="messenger">
                                                 <img src="/images/icons/social/messenger.svg" class="share-buttons-item-icon" alt="deel <?= $this->data['subject']['name']; ?> op messenger" width="36" height="36"/>
                                             </a>
                                         </div>
                                     </li>
                                    <li>
                                        <div class="fb-share-button" data-href="https://www.plugin.support/reviews/<?= $this->data['subject']['slug']; ?>/<?= $this->data['review']['slug']; ?>/" data-layout="button" data-size="large">
                                             <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fwww.plugin.support%2Freviews%2F<?= $this->data['subject']['slug']; ?>%2F<?= $this->data['review']['slug']; ?>%2F&amp;src=sdkpreparse" target="_blank" class="fb-xfbml-parse-ignore" data-service="facebook">
                                                 <img src="/images/icons/social/facebook.svg" class="share-buttons-item-icon" alt="deel <?= $this->data['subject']['name']; ?> op facebook" width="36" height="36"/>
                                             </a>
                                         </div>
                                     </li>
                                    <li>    
                                        <div class="twitter-share-button" data-href="https://www.plugin.support/reviews/<?= $this->data['subject']['slug']; ?>/<?= $this->data['review']['slug']; ?>/" data-layout="button" data-size="large">
                                            <a href="https://twitter.com/intent/tweet?hashtags=betrouwbaredatingsites&original_referer=https%3A%2F%2Fwww.plugin.support%2F&ref_src=twsrc%5Etfw%7Ctwcamp%5Ebuttonembed%7Ctwterm%5Eshare%7Ctwgr%5E&url=https%3A%2F%2Fwww.plugin.support%2Freviews%2F<?= $this->data['subject']['slug']; ?>%2F<?= $this->data['review']['slug']; ?>%2F&via=datingsites_bd/&text=<?= $this->data['subject']['slug']; ?>" target="_blank" data-via="datingsites_bd" data-hashtags="betrouwbaredatingsites" data-lang="nl" data-dnt="false" data-show-count="false"  data-service="twitter">
                                                <img src="/images/icons/social/twitter.svg" class="share-buttons-item-icon" alt="deel <?= $this->data['subject']['name']; ?> op twitter" width="36" height="36"/>
                                            </a>
                                        </div><!-- <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>-->
                                    </li>
                                    <li>    
                                        <a href="https://web.whatsapp.com/send?text=https%3A%2F%2Fwww.plugin.support%2Freviews%2F<?= $this->data['subject']['slug']; ?>%2F<?= $this->data['review']['slug']; ?>%2F" class="share-buttons-item Share-buttons-item--social" target="_blank" data-service="whatsapp">
                                            <img src="/images/icons/social/whatsapp.svg" class="share-buttons-item-icon" alt="deel <?= $this->data['subject']['name']; ?> via whatsapp" width="36" height="36"/>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="social pinterest" data-media="https://www.plugin.support/reviews/<?= $this->data['subject']['slug']; ?>/<?= $this->data['review']['slug']; ?>/" data-description="<?= $this->data['subject']['name']; ?>" target="_blank" data-service="pinterest">
                                            <img src="/images/icons/social/pinterest.svg" class="share-buttons-item-icon" alt="deel <?= $this->data['subject']['name']; ?> via pinterest" width="36" height="36"/>
                                        </a>
                                        <script> var pinOneButton = document.querySelector('.pinterest');  pinOneButton.addEventListener('click', function () { PinUtils.pinOne({ media: e.target.getAttribute('data-media'), description: e.target.getAttribute('data-description'), }); }); </script>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- comments -->
                <div id="comments" class="comments">
                    <h5>Reacties</h5>
                    <?php
                    # display comments
                    if ($result = $app->comments($this->data['review']['ID'])){
                        foreach($result as $key => $row)
                        {
                            echo '<div class="comment">';
                            echo '<span class="name">' . $row['name'] . ' <span class="date">' . $this->functions->timestamp($row['created']) . '</span></span>';
                            echo '<p class="description">' . $row['comment'] . '</p>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="comment no-replies">';
                        echo '<p class="description">Er zijn nog geen reacties op deze beoordeling geplaatst.</p>';
                        echo '</div>';
                    }
                    ?>
                    <!-- new comment -->
                    <div class="reply">
                        <h6>Reageer op <?= $this->data['review']['name'];?>'s review</h6>
                        <table>
                            <tr>
                                <td>
                                    <span class="title">Naam</span>
                                    <input type="text" name="name" placeholder="Schrijf hier jouw naam" value="" required />
                                </td>
                                <td>
                                    <span class="title">E-mailadres</span>
                                    <input type="text" name="email" placeholder="Schrijf hier jouw e-mailadres" value="" required />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <span class="title">Reactie</span>
                                    <textarea name="comment" placeholder="Schrijf hier jouw reactie op deze review" required></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <span class="agreements">Door deze reactie te plaatsen gaat u akkoord met onze <a href="/algemene-voorwaarden/" target="_blank">gebruikersvoorwaarden</a> en <a href="/privacy/" target="_blank">privacybeleid</a></span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><button type="button" class="btn btn-primary btn-medium btn-round btn-shadow" data-comment="<?=$this->data['review']['ID'];?>">Plaats reactie</button></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="thumbnail" data-subject="<?= $this->data['subject']['ID']; ?>">
                        <img src="/images/logos/135x65/<?= $this->data['subject']['logo']; ?>" alt="logo <?= $this->data['subject']['name']; ?>"/>
                    </div>
                    <div class="outlink">
                        <a href="/offer/<?= $this->data['subject']['slug']; ?>/" target="_blank" class="btn btn-primary btn-large btn-wide btn-round" rel="noopener">Ga naar de site</a>
                    </div>
                    <a href="/reviews/<?= $this->data['subject']['slug']; ?>/" target="_blank" class="btn-large" rel="noopener"><?= $this->data['subject']['name']; ?> review</a>
                </div>
                <div class="top-offers">
                    <h3>Aanbevolen alternatieven</h3>
                    <?php
                    # display top offers
                    foreach ($this->data['top-offers'] as $row) {
                        $hash = uniqid();
                        ?>
                        <div class="item">
                            <div class="details">
                                <span class="title"><?= $row['name']; ?></span>
                                <div class="rating" data-evaluate="<?= $row['slug'] ;?>">
                                    <span class="stars"></span>
                                    <span class="stars-filled" style="width: <?= $row['score'] ;?>%"></span>
                                </div>
                                <a href="/offer/<?= $row['slug']; ?>/" target="_blank" class="btn btn-primary btn-medium btn-round" rel="noopener">Show more</a>
                            </div>
                            <div class="thumbnail">
                                <img src="/images/symbols/90x90/<?= $row['symbol']; ?>" width="90" height="90" data-subject="<?= $row['ID']; ?>" alt="logo <?= $row['name']; ?>"/>
                            </div>
                        </div>
                        <?php
                        # end of top offers
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- mobile direct outlink -->
<div id="outlink">
    <div class="content">
        <div class="item">
            <img src="/images/logos/<?= $this->data['subject']['logo']; ?>" alt="logo <?= $this->data['subject']['name']; ?>" class="logo"/>
        </div>
        <div class="item">
            <a href="/offer/<?= $this->data['subject']['slug']; ?>/" target="_blank" class="btn btn-primary btn-large btn-round">Bezoek site</a>
        </div>
    </div>
</div>
<?php
$this->template->display('app/footer');

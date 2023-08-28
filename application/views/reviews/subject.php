<?php
# initiate modal
$app = new Reviews;
$datatype = 'reviews';

# display head
$this->template->display('app/head', [
    # meta tags
    'meta_title' => substr($this->data['subject']['meta_title'], 0, 56),
    'meta_description' => substr($this->data['subject']['meta_description'], 0, 144),
    'meta_keywords' => $this->data['subject']['meta_keywords'],

    # open graph tags
    'og_image' => '/images/og-images/thumbnails/' . $this->data['subject']['slug'] . '-review-plugin-support.jpg',

    # canonical
    'canonical' => '/reviews/' . $this->data['subject']['slug'] . '/',

    # rich snippet
    'review' => [
        'name' => $this->data['subject']['name'],
        'slogan' => $this->data['subject']['slogan'],
        'description' => $this->data['subject']['slogan'],
        'url' => '/reviews/' . $this->data['subject']['slug'] . '/',
        'symbol' => $this->data['subject']['symbol'],
        'thumbnail' => $this->data['subject']['thumbnail'],
        'review' => $this->data['subject']['description'],
        'rating' => [
            'average' => $this->data['subject']['score']['average'],
            'best' => $this->data['subject']['score']['best'],
            'worst' => $this->data['subject']['score']['worst']
        ],
        'published' => $this->functions->timestamp($this->data['subject']['created'])
    ]
]);

# display menu
$this->template->display('app/menu', [
    'status' => 'active'
]);
?>
<!-- popup -->
<div class="modal popup">
    <div class="modal-container">
        <div class="modal-header">
            <div class="thumbnail">
                <img src="/images/plugins/symbols/<?= $this->data['subject']['symbol']; ?>" alt="logo <?= $this->data['subject']['name']; ?>" width="85" height="85" loading="lazy"/>
            </div>
            <h3 data-subject="<?= $this->data['subject']['ID']; ?>"><?=$this->data['subject']['name'];?> according to users</h3>
                        <span class="title badge <?= ($this->data['subject']['score']['average'] > 2.5 ? 'badge-success' : 'badge-error'); ?>"><span class="error"><?= (100 - ($this->data['subject']['score']['average'] * 20)); ?>%</span><span class="success"><?= ($this->data['subject']['score']['average'] * 20); ?>%</span> of the people who use <?=$this->data['subject']['name'];?> are <span class="s1">unhappy</span><span class="s2">happy</span></span>
            <button type="button" class="btn-close" data-exit="true">&#10006;</button>
        </div>
        <div class="modal-content" data-subject="<?= $this->data['top-offers'][0]['ID']; ?>">
            <span class="description">Listen to the users who preceded you</span>
        </div>
        <div class="modal-footer">
                    <?php
                    # display top offers
                    foreach ($this->data['top-offers'] as $row) {
                        ?>
                        <div class="item" data-subject="<?= $row['ID']; ?>">
                            <span class="position"><?= $row['position']; ?></span>
                            <div class="thumbnail">
                                <img src="/images/plugins/symbols/<?= $row['symbol']; ?>" alt="logo <?= $row['name']; ?>" width="85" height="85" loading="lazy"/>
                            </div>
                            <div class="details">
                                <span class="title" data-subject="<?= $row['ID']; ?>"><?= $row['name']; ?></span>
                                <span class="slogan"><?= $row['slogan']; ?></span>
                                <div class="rating" data-evaluate="<?= $this->data['subject']['slug'] ;?>">
                                    <span class="success"><?= round($row['score']); ?>%</span> satisfied users
                                </div>
                                <div class="grade" data-subject="<?= $row['ID']; ?>">
                                    <span class="title">Our score</span>
                                    <span class="description"><?= $row['rating']; ?></span>
                                </div>
                                <img src="/images/icons/angle-right.svg" alt="angle-right" class="angle-right" width="75" height="75" loading="lazy"/>
                            </div>
                            <div class="down-row"><?= $row['registrations']; ?> people chose today <?= $row['name']; ?></div>                            
                        </div>
                        <?php
                    }
                    ?>
        </div>
    </div>
</div>
<!-- banner -->
<header id="banner" class="subject-banner">
    <div class="wrapper">
        <div class="details col-lg-8 col-md-8 col-sm-12 col-xs-12">
        <div class="thumbnail" data-subject="<?= $this->data['subject']['ID']; ?>">
            <img src="/images/plugins/symbols/<?= $this->data['subject']['symbol']; ?>" alt="logo <?= $this->data['subject']['name']; ?>" loading="lazy"/>
        </div>
            <h1 data-subject="<?= $this->data['subject']['ID']; ?>" title><?= $this->data['subject']['name']; ?> Review <?php $current_year = date('Y'); echo $current_year; ?></h1>
            <div class="page-subtitle" title><?= $this->data['subject']['slogan']; ?></div>
            <hr>
            <div data-type="application/hydration-marker">
                <div class="by-author-modular">
                    <img src="/images/guide/author/mauritswalters.jpg" width="40" height="40">
                    <a data-testid="link" href="/authors/maurits/" data-role-position="0" target="_self"> By<!-- --> <!-- -->Maurits<!-- --> <!-- -->Walters</a>
                    <span class="date">Last update : <?= $this->functions->timestamp($this->data['subject']['updated']); ?></span></div>
                </div>
            <h2 data-subject="<?= $this->data['subject']['ID']; ?>"><?= $this->data['subject']['name']; ?></h2>
            <h3 data-subject="<?= $this->data['subject']['ID']; ?>"><?= $this->data['subject']['domain']; ?></h3>
            <div class="rating" data-evaluate="<?= $this->data['subject']['slug'] ;?>">
                <span class="stars"></span>
                <span class="stars-filled" style="width: <?= ($this->data['subject']['score']['average'] * 20) ;?>%"></span>
            </div>
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
                    'name' => 'Reviews',
                    'href' => '/reviews/'
                ],
                [
                    'name' => $this->data['subject']['name'],
                    'href' => '/reviews/' . $this->data['subject']['slug'] . '/'
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
<!-- review -->
<div id="review">
    <!-- subject -->
    <div class="wrapper">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <div class="content">
                    <!-- details -->
                    <div class="details" data-subject="<?= $this->data['subject']['ID']; ?>">
                        <h2><?= $this->data['subject']['name'] . ': ' . $this->data['subject']['slogan']; ?></h2>
                        <span class="head-subLine"></span>
                        <p class="description"><?= nl2br($this->data['subject']['description']); ?></p>
                    </div>
                    <!-- summary -->
                    <div class="summary" data-subject="<?= $this->data['subject']['ID']; ?>">
                        <h3><?= $this->data['subject']['name']; ?> in a nutshell</h3>
                        <span class="head-subLine"></span>
                        <table>
                            <?php
                            # summaries
                            foreach ($this->data['subject']['summaries'] as $row) {
                                echo '<tr>';
                                echo '<td><span class="title">' . $row['title'] . '</span></td>';
                                echo '<td><span class="description">' . $row['description'] . '</span></td>';
                                echo '</tr>';
                            }
                            ?>
                        </table>
                    </div>
                    <!-- compare -->
                    <div class="compare">
                        <img src="/images/plugins/symbols/<?= $this->data['subject']['symbol']; ?>" alt="logo <?= $this->data['subject']['name']; ?>" width="70" height="70" loading="lazy"/>
                        <span class="title"><?= $this->data['subject']['name']; ?></span>
                        <button class="btn btn-danger btn-large btn-round" data-compare="<?= $this->data['subject']['ID']; ?>">Compare all</button>
                    </div>
                    <!-- thumbnail --> 
                    <?php
                        $thumbnailPath = $_SERVER['DOCUMENT_ROOT'] . "/images/thumbnails/" . $this->data['subject']['thumbnail'];
                        $logoPath = $_SERVER['DOCUMENT_ROOT'] . "/images/logos/135x65/" . $this->data['subject']['logo'];

                        if (!empty($this->data['subject']['thumbnail']) && file_exists($thumbnailPath) && filesize($thumbnailPath) > 0) { ?>
                            <div class="thumbnail" data-subject="<?= $this->data['subject']['ID']; ?>">
                                <img src="/images/thumbnails/<?= $this->data['subject']['thumbnail']; ?>" alt="<?= $this->data['subject']['name']; ?>: <?= $this->data['subject']['slogan']; ?>" width="730" height="480" loading="lazy"/>
                                <?php if (file_exists($logoPath) && filesize($logoPath) > 0) { ?>
                                    <span class="brand"><img src="/images/logos/135x65/<?= $this->data['subject']['logo']; ?>" alt="logo <?= $this->data['subject']['name']; ?>" loading="lazy"></span>
                                <?php } ?>
                            </div>
                        <?php } ?>


                    <?php
                    # impressions
                    if (!empty($this->data['subject']['impressions'])) {
                        echo '<div class="impressions" data-subject="' . $this->data['subject']['ID'] . '">';

                        # pros
                        if (!empty($this->data['subject']['impressions']['pros'])) {
                            echo '<div class="item">';
                            echo '<span class="title">Pros</span>';
                            echo '<ul>';

                            foreach ($this->data['subject']['impressions']['pros'] as $row) {
                                echo '<li class="pro">' . $row['title'] . '</li>';
                            }

                            echo '</ul>';
                            echo '</div>';
                        }

                        # cons
                        if (!empty($this->data['subject']['impressions']['cons'])) {
                            echo '<div class="item">';
                            echo '<span class="title">Cons</span>';
                            echo '<ul>';

                            foreach ($this->data['subject']['impressions']['cons'] as $row) {
                                echo '<li class="con">' . $row['title'] . '</li>';
                            }

                            echo '</ul>';
                            echo '</div>';
                        }

                        echo '</div>';
                    }
                    ?>                    
                
                        <div class="share-icons">
                        <h4><?= $this->data['subject']['name'];?> Share review?</h4>
                            <div class="item">
                                <ul>
                                    <li>
                                        <div class="messenger-share-button" data-href="https://www.plugin.support/reviews/<?= $this->data['subject']['slug']; ?>/" data-layout="button" data-size="large">
                                             <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?kid_directed_site=0&sdk=joey&u=https%3A%2F%2Fwww.plugin.support%2Freviews%2F<?= $this->data['subject']['slug']; ?>%2F&display=popup&ref=plugin&src=share_button" target="_blank" class="fb-xfbml-parse-ignore" data-service="messenger">
                                                 <img src="/images/icons/social/messenger.svg" class="share-buttons-item-icon" alt="deel <?= $this->data['subject']['name']; ?> op messenger" width="36" height="36"/>
                                             </a>
                                         </div>
                                     </li>
                                    <li>
                                        <div class="fb-share-button" data-href="https://www.plugin.support/reviews/<?= $this->data['subject']['slug']; ?>/" data-layout="button" data-size="large">
                                             <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fwww.plugin.support%2Freviews%2F<?= $this->data['subject']['slug']; ?>%2F&amp;src=sdkpreparse" target="_blank" class="fb-xfbml-parse-ignore" data-service="facebook">
                                                 <img src="/images/icons/social/facebook.svg" class="share-buttons-item-icon" alt="deel <?= $this->data['subject']['name']; ?> op facebook" width="36" height="36"/>
                                             </a>
                                         </div>
                                     </li>
                                    <li>    
                                        <div class="twitter-share-button" data-href="https://www.plugin.support/reviews/<?= $this->data['subject']['slug']; ?>/" data-layout="button" data-size="large">
                                            <a href="https://twitter.com/intent/tweet?hashtags=chatgpt-plugins&original_referer=https%3A%2F%2Fwww.plugin.support%2F&ref_src=twsrc%5Etfw%7Ctwcamp%5Ebuttonembed%7Ctwterm%5Eshare%7Ctwgr%5E&url=https%3A%2F%2Fwww.plugin.support%2Freviews%2F<?= $this->data['subject']['slug']; ?>%2F&via=chatgpt_plugins/&text=<?= $this->data['subject']['slug']; ?>" target="_blank" data-via="chatgpt_plugins" data-hashtags="chatgpt_plugins" data-lang="nl" data-dnt="false" data-show-count="false"  data-service="twitter">
                                                <img src="/images/icons/social/twitter.svg" class="share-buttons-item-icon" alt="deel <?= $this->data['subject']['name']; ?> op twitter" width="36" height="36"/>
                                            </a>
                                        <!--</div><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script> -->
                                    </li>
                                    <li>    
                                        <a href="https://web.whatsapp.com/send?text=https%3A%2F%2Fwww.plugin.support%2Freviews%2F<?= $this->data['subject']['slug']; ?>%2F" class="share-buttons-item Share-buttons-item--social" target="_blank" data-service="whatsapp">
                                            <img src="/images/icons/social/whatsapp.svg" class="share-buttons-item-icon" alt="deel <?= $this->data['subject']['name']; ?> via whatsapp" width="36" height="36"/>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="social pinterest" data-media="https://www.plugin.support/reviews/<?= $this->data['subject']['slug']; ?>/" data-description="<?= $this->data['subject']['name']; ?>" target="_blank" data-service="pinterest">
                                            <img src="/images/icons/social/pinterest.svg" class="share-buttons-item-icon" alt="deel <?= $this->data['subject']['name']; ?> via pinterest" width="36" height="36"/>
                                        </a>
                                        <?php global $nonce; ?>
                                            <script nonce="<?php echo $nonce; ?>"> var pinOneButton = document.querySelector('.pinterest');  pinOneButton.addEventListener('click', function () { PinUtils.pinOne({ media: e.target.getAttribute('data-media'), description: e.target.getAttribute('data-description'), }); }); </script>
                                    </li>
                                </ul>
                                <ul>
                                    <li>
                                        <a href="/evaluate/<?= $this->data['subject']['slug'];?>/" data-evaluate="<?= $this->data['subject']['slug'];?>" class="btn btn-primary btn-large btn-round">
                                            <img src="/images/icons/plus.svg"  width="16" height="16" alt="<?= $this->data['subject']['name'];?> add review" loading="lazy"/> <?= $this->data['subject']['name'];?> add review</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <section class="prompts">
                        <h2><?= $this->data['subject']['name'];?> Prompts : <?= $this->data['subject']['prompts_count']; ?> Prompt examples</h2>
                        <span class="head-subLine"></span>
                        <p class="description">Learn how to use <strong><?= $this->data['subject']['name'];?></strong> effectively! Here are <?= $this->data['subject']['prompts_count']; ?> example prompts, tips, and the documentation of available commands.</p>
                        <?php 
                            # prompts 
                            if (!empty($this->data['subject']['prompts']) || !empty($this->data['subject']['prompts'])) { 
                                foreach ($this->data['subject']['prompts'] as $key => $row) 
                                { 
                                    echo '<div class="prompt">';
                                    echo '<h3 for="question-' . ($key + 1) . '" class="target"><strong>Prompt ' . ($key + 1) . ' :</strong> ' . $row['target'] . '</h3>';
                                    echo '<span class="head-subLine"></span>';
                                    echo '<pre>';
                                    echo '<div class="bg-black rounded-md mb-4">';
                                    echo '<div class="flex items-center relative text-gray-200 bg-gray-800 px-4 py-2 text-xs font-sans justify-between rounded-t-md transition-opacity">';
                                    echo '<span>plaintext</span>';
                                    echo '<button class="flex ml-auto gap-2" onclick="myFunction(this)" data-copy-target="myInput' . ($key + 1) . '"><svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg> Copy text</button>';
                                    echo '</div>';
                                    echo '<div class="p-4 overflow-y-auto">';
                                    echo '<code>';
                                    echo '<ol>';
                                    echo '<li itemprop="mainEntity" itemscope itemtype="https://schema.org/Question">';
                                    echo '<p for="question-' . ($key + 1) . '" id="myInput' . ($key + 1) . '" name="answer" class="title">' . $row['prompt'] . '</p>';
                                    echo '</li>';
                                    echo '</ol>';
                                    echo '</code>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</pre>';
                                    echo '<p for="question-' . ($key + 1) . '" class="title"><strong>Required: </strong> ' . $row['required'] . '</p>';
                                    echo '<p class="description" itemprop="acceptedAnswer" itemscope itemtype="https://schema.org/Answer"><strong>Expected result: </strong> ' . nl2br($row['expected']) . '</p>';
                                    echo '</div>';
                                }
                         } ?>
                    </section>
                <?php 
                # display prices summary
                if (!empty($this->data['subject']['costs']) || !empty($this->data['subject']['prices'])) {
                    ?>
                    <section class="prices" data-subject="<?= $this->data['subject']['ID']; ?>">
                        <h2>Wat kost <?= $this->data['subject']['name']; ?>?</h2>
                        <?php
                        # display costs description
                        if (!empty($this->data['subject']['costs'])) {
                            echo '<p class="description">' . nl2br($this->data['subject']['costs']) . '</p>';
                        }

                        # display prices
                        if (!empty($this->data['subject']['prices'])) {
                            ?>
                            <table>
                                <tr>
                                    <th>Period</th>
                                    <th>Price</th>
                                    <th>Per month</th>
                                    <th>Benefit</th>
                                </tr>
                                <?php
                                foreach ($this->data['subject']['prices'] as $row) {
                                    echo '<tr>';
                                    echo '<td>' . $row['period'] . '</td>';
                                    echo '<td>' . $row['price'] . '</td>';
                                    echo '<td>' . $row['monthly'] . '</td>';
                                    echo '<td>' . $row['benefit'] . '%</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </table>
                            <?php
                        }
                        ?>
                    </section>
                    <?php
                }

                # frequently asked questions
                if (!empty($this->data['subject']['unsubscribe'])) {
                    ?>
                    <section class="unsubscribe">
                        <div class="content">
                            <h2>Not satisfied with <?= $this->data['subject']['name']; ?>?</h2>
                            <?php
                                # display unsubscribe description
                                if (!empty($this->data['subject']['unsubscribe'])) {
                                    echo '<div class="description                                                                                                                                                                                                                                                                                                                                                                                         ">' . nl2br($this->data['subject']['unsubscribe']) . '</div>';
                                }
                            ?>
                        </div>
                    </section>
                    <?php
                }
                # frequently asked questions
                if (!empty($this->data['subject']['faq'])) {
                    ?>
                    <section class="faq">
                        <img src="/images/icons/faq.svg" alt="<?= $this->data['subject']['name']; ?> faq" width="36" height="36" loading="lazy">
                        <h2>FAQ's about <?= $this->data['subject']['name']; ?></h2>
                        <span class="head-subLine"></span>
                        <h4>We are happy to help you</h4>
                        <div class="questions" itemscope itemtype="https://schema.org/FAQPage">
                            <?php
                            echo '<ul>';

                            foreach ($this->data['subject']['faq'] as $key => $row) {
                                echo '<li itemprop="mainEntity" itemscope itemtype="https://schema.org/Question">';
                                echo '<input type="checkbox" name="answer" id="question-' . ($key + 1) . '" />';
                                echo '<label for="question-' . ($key + 1) . '" class="title" itemprop="name">' . $row['question'] . '</label>';
                                echo '<p class="description" itemprop="acceptedAnswer" itemscope itemtype="https://schema.org/Answer"><span itemprop="text">' . nl2br($row['answer']) . '</span></p>';
                                echo '</li>';
                            }

                            echo '</ul>';
                            ?>
                        </div>
                        <span class="contact">Is your question about <?= $this->data['subject']['name'] ;?> not there? Please feel free to contact us.</span>
                    </section>
                    <?php
                }
                # reviews
                if (!empty($this->data['subject']['reviews'])) {
                    ?>                
                    <div id="ratingform" class="content">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 avatar-evaluate">
                            <img src="/images/content/73x73.png" width="73" height="73" alt="user avatar" loading="lazy" />
                            <h3 class="title-evaluate"><a href="/evaluate/<?= $this->data['subject']['slug']; ?>/">write a review <span class="min-970">over <?= $this->data['subject']['name'] ;?></span></a></h3>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="rating" data-evaluate="<?= $this->data['subject']['slug'] ;?>">
                                <span class="stars"></span>
                                <span class="stars-filled" style="width: <?= round($this->data['subject']['score']['average'] * 20); ?>%" data-stars="<?= round($this->data['subject']['score']['average']); ?>"></span>
                            </div>
                        </div>
                    </div>
                    <section class="comments">
                        <?php
                        # average rating
                        if (!empty($this->data['subject']['ratings'])) {
                            ?>
                            <!-- average rating -->
                            <h3>Experiences and Reviews</h3>
                            <div class="score-box">
                                <div class="col-lg-6 col-md-5 col-sm-12 col-xs-12 overall">
                                    <div class="container">
                                        <div class="grade" data-subject="<?= $this->data['subject']['ID']; ?>">
                                            <span class="description"><?= $this->data['subject']['rating']; ?></span>
                                        </div>
                                        <div class="review">
                                            <div class="rating" data-evaluate="<?= $this->data['subject']['slug'] ;?>">
                                                <span class="stars"></span>
                                                <span class="stars-filled" data-width="<?= ($this->data['subject']['score']['average'] * 20) ;?>%"  data-stars="<?= round($this->data['subject']['score']['average']) ;?>"></span>
                                            </div>
                                            <span class="description"><?= $this->data['subject']['count']['ratings']; ?> reviews</span>
                                        </div>
                                        <span class="guidelines">
                                            <p>Reviews can only be added by registered <?= $this->data['subject']['slug'] ;?> users are added. All reviews are checked by us. Please also see our <a href="/review-guidelines/">review guidelines</a> before posting</p>
                                        </span>
                                    </div>                                
                                </div>
                                <div class="col-lg-6 col-md-7 col-sm-12 col-xs-12"> 
                                    <div class="ratings">
                                        <table>
                                            <?php
                                            # progress bar themes
                                            $theme = ['green', 'light-green', 'yellow', 'orange', 'red'];

                                            # questions
                                            foreach ($this->data['subject']['ratings'] as $key => $row) {
                                                $theme[$key] = ($theme[$key] ?? '');

                                                echo '<tr>';
                                                echo '<td class="title">' . $row['title'] . '</td>';
                                                echo '<td class="progress"><div class="progress-bar"><span class="' . $theme[$key] . '" style="width: ' . (round($row['rating']) * 10) . '%"></span></div></td>';
                                                echo '<td class="total">' . round($row['rating']) . ' / 10</td>';
                                                echo '</tr>';
                                            }
                                            ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>

                        <!-- reviews -->
                        <h3 id="comments">Popular Plugins</h3>
                        <?php
                        # reviews
                        foreach ($this->data['subject']['reviews'] as $row) {
                            # set variables
                            $vote = ($row['recommend'] == 'yes' ? 'upvote' : 'downvote');
                            $recommend = ($row['recommend'] == 'yes' ? 'do' : 'do not');
                            ?>
                            <div class="review">
                                <div class="details">
                                    <div class="thumbnail" data-mood="<?= round($row['score'] / 20); ?>">
                                        <img src="/images/icons/users/<?= $row['thumbnail']; ?>" alt="<?= $row['name']; ?> beoordeelde <?= $this->data['subject']['name']; ?>" width="50" height="50" loading="lazy"/>
                                    </div>
                                    <div class="person">
                                        <span class="name"><?= $row['name']; ?> <small>(<?= $row['age']; ?> year)</small></span>
                                        <span class="date"><?= $this->functions->timestamp($row['created']); ?></span>
                                    </div>
                                    <div class="rating" data-evaluate="<?= $this->data['subject']['slug'] ;?>">
                                        <span class="stars"></span>
                                        <span class="stars-filled" style="width: <?= $row['score'] ;?>%"></span>
                                    </div>
                                </div>
                                <div class="comment">
                                    <span class="<?= $vote; ?>">I do <?= $recommend; ?> <?= $this->data['subject']['name']; ?></span>
                                    <span class="title"><?= $row['title']; ?></span>
                                    <p class="description"><?= $row['description']; ?></p>
                                </div>
                                <?php
                                # display strengths
                                if (!empty($row['strengths'])) {
                                    echo '<div class="strengths">';

                                    # display pros
                                    if (!empty($row['strengths']['pros'])) {
                                        echo '<ul>';
                                        foreach ($row['strengths']['pros'] as $strength) {
                                            echo '<li class="pro"><span>' . $strength['title'] . '</span></li>';
                                        }
                                        echo '</ul>';
                                    }

                                    # display cons
                                    if (!empty($row['strengths']['cons'])) {
                                        echo '<ul>';
                                        foreach ($row['strengths']['cons'] as $strength) {
                                            echo '<li class="con"><span>' . $strength['title'] . '</span></li>';
                                        }
                                        echo '</ul>';
                                    }

                                    echo '</div>';
                                }
                                ?>
                                <ul class="share">
                                    <li><button type="button" data-like="<?= $row['ID']; ?>" class="<?= ($row['liked'] ? 'active' : ''); ?>"><img src="/images/icons/like.svg" alt="Useful review" width="14" height="14" loading="lazy"/> Useful (<span class="likes"><?= $row['likes']; ?></span>)</button></li>
                                    <li><button type="button" data-href="/reviews/<?= $this->data['subject']['slug']; ?>/<?= $row['slug']; ?>/"><img src="/images/icons/comment.svg" alt="naar de reacties" width="14" height="14" loading="lazy"/> Comments (<?= $row['comments']; ?>)</button></li>
                                    <li><a href="/reviews/<?= $this->data['subject']['slug']; ?>/<?= $row['slug']; ?>/">Read more</a></li>
                                    <li><a href="/offer/<?= $this->data['subject']['slug']; ?>/" target="_blank" rel="noopener"><img src="/images/icons/share.svg" alt="naar de aanbieder" width="16" height="16" loading="lazy"/><?= $this->data['subject']['name']; ?></a></li>
                                </ul>
                            </div>
                            <?php
                            # end of reviews
                        }
                        ?>
                    </section>
                    <?php
                    # load more button
                    if ($this->data['subject']['count']['reviews'] > 5) {
                        echo '<button class="btn btn-secondary btn-round btn-medium btn-center" data-reviews="' . $this->data['subject']['ID'] . '">Bekijk meer</button>';
                    }
                }
                ?>
            </div>
            <aside class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <section class="card">
                    <picture class="thumbnail">
                           <source type="image/jpg" srcset="/images/plugins/symbols/30x30/<?= str_replace('.png', '.jpg', $this->data['subject']['symbol']); ?>" media="(max-width: 480px)" loading="lazy">
                           <source type="image/jpg" srcset="/images/plugins/symbols/<?= $this->data['subject']['symbol']; ?>" media="(min-width: 480px)">
                        <img srcset="/images/plugins/symbols/<?= $this->data['subject']['symbol']; ?>" src="/images/plugins/symbols/<?= $this->data['subject']['symbol']; ?>" alt="logo <?= $this->data['subject']['name']; ?>"  width="150" height="150" />
                    </picture>
                    <div class="container">
                        <div class="grade" data-subject="<?= $this->data['subject']['ID']; ?>">
                            <span class="title">Our score</span>
                            <span class="description"><?= $this->data['subject']['rating']; ?></span>
                        </div>
                        <div class="review">
                            <span class="title">Average rating</span>
                            <div class="rating" data-evaluate="<?= $this->data['subject']['slug'] ;?>">
                                <span class="stars"></span>
                                <span class="stars-filled" data-width="<?= (!empty($this->data['subject']['score']['average']) ? $this->data['subject']['score']['average'] : '60'); ?>" data-stars="<?= round($this->data['subject']['score']['average'] / 2 / 10) ? round($this->data['subject']['score']['average'] / 2 / 10) : 6; ?>"></span>
                            </div>
                            <span class="description"><?= $this->data['subject']['count']['ratings']; ?> ratings</span>
                        </div>
                    </div>
                    <div class="outlink">
                        <a href="/offer/<?= $this->data['subject']['slug']; ?>/" target="_blank" class="btn btn-primary btn-large btn-wide btn-round" rel="noopener">Go to website</a>
                    </div>
                    <div class="widgets" data-subject="<?= $this->data['subject']['ID']; ?>">
                        <div class="item">
                            <img src="/images/icons/features/secure-data.svg" alt="100% veilig en discreet"/>
                            <span class="title">100% data secure</span>
                        </div>
                        <div class="item">
                            <img src="/images/icons/features/users-count.svg" alt="gekozen vandaag"/>
                            <span class="title"><strong><?= $this->data['subject']['registrations']; ?></strong> x chosen today</span>
                        </div>
                        <div class="item">
                            <img src="/images/icons/features/comments.svg" alt="eerlijke ervaringen"/>
                            <span class="title"><?= $this->data['subject']['count']['reviews']; ?> consumer experiences</span>
                        </div>
                    </div>
                </section>
                <section class="features" data-subject="<?= $this->data['subject']['ID']; ?>">
                    <h3><?= $this->data['subject']['name']; ?> Specifications</h3>
                    <ul>
                        <?php
                        # display features
                        foreach ($this->data['subject']['features'] as $row) {
                            echo '<li>';
                            echo '<span class="icon"><img src="/images/icons/features/' . $row['icon'] . '" alt="' . $row['title'] . '" /></span>';
                            echo '<span class="name">' . $row['title'] . '</span>';
                            echo '<span class="description">' . (!empty($row['content']) ? $row['content'] : '-') . '</span>';
                            echo '</li>';
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
            </aside>
        </div>
    </div>
</div>
<!-- mobile direct outlink -->
<div id="outlink">
    <div class="content">
        <div class="item">
            <img src="/images/plugins/symbols/<?= $this->data['subject']['symbol']; ?>" alt="logo <?= $this->data['subject']['name']; ?>" width="65" height="65" class="logo"/>
        </div>
        <div class="item">
            <a href="/offer/<?= $this->data['subject']['slug']; ?>/" target="_blank" class="btn btn-primary btn-large btn-round">Visit site</a>
        </div>
    </div>
</div>
<?php
$this->template->display('app/footer');

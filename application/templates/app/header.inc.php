<!-- header -->
<header id="header" class="theme-<?= ($data['theme'] ?? 'default'); ?>">
    <div class="wrapper">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="content">
                <h1 class="title">
                    <?php 
                        if (!isset($data['title']) && !isset($this->website['title'])) {
                            echo $data['name'];
                        } else {
                            echo ($data['title'] ?? $this->website['title']);
                        }
                    ?>
                </h1>
                    <p id="textmore" class="description larger" style="transition: all 1.2s ease 0s;"><?= ($data['description'] ?? $this->website['description']); ?></p>
                    <button class="btn__text" onclick="toggleChanges();">show more</button>
                    <?php
                    # display buttons
                    if (isset($data['buttons'])) {
                        echo '<button class="recommend" data-href="/reviews/">ChatGPT Plugins</button>';
                        echo '<small>Free forever, Build with love.</small>';
                      # echo '<button class="compare" data-href="/compare/">Compare Plugins</button>';
                    }

                    # display statistics
                    if (isset($data['statistics']) && !empty($data['statistics'])) {
                        echo '<div class="statistics">';
                        echo '<ul>';
                        echo '<li><div class="icon"><img src="/images/icons/experts.svg" alt="' . $data['statistics']['experts'] . ' experts" width="20" height="20" /></div><span class="amount">' . $data['statistics']['experts'] . '</span><span class="title">Experts</span></li>';
                        echo '<li><div class="icon"><img src="/images/icons/words.svg" alt="' . $data['statistics']['words'] . ' woorden" width="20" height="20" /></div><span class="amount">' . $data['statistics']['words'] . '</span><span class="title">Words</span></li>';
                        echo '<li><div class="icon"><img src="/images/icons/advertisements.svg" alt="0 advertenties" width="20" height="20"/></div><span class="amount">0</span><span class="title">Ads</span></li>';
                        echo '</ul>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
            <?php if (!empty($data['thumbnail'])): ?>
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="thumbnail">
                        <img src="/images/themes/<?= ($data['thumbnail'] ?? $this->website['thumbnail']); ?>" alt="thema for <?= $this->website['name']; ?>">
                    </div>                
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>


<?php
# initiate modal
$app = new Index;

# display head
$this->template->display('app/head', [
    # meta tags
    'meta_title' => $this->data['websites']['meta_title'],
    'meta_description' => $this->data['websites']['meta_description'],
    'meta_keywords' => $this->data['websites']['meta_keywords'],

    # open graph tags
    'og_image' => '/' . $this->data['websites']['og_image'],

    # canonical
    'canonical' => '/',

    # google_analytics
    'google_analytics' => $this->data['websites']['google_analytics']
]);

# display menu
$this->template->display('app/menu');

# display header
$this->template->display('app/header', [
    'title' => 'Explore the Ultimate <strong>ChatGPT Plugin</strong> Guide',
    'name' => $this->data['websites']['slogan'],
    'buttons' => true
]);
?>
<script type="application/ld+json">
{
 "@context" : "https://schema.org",
    "@type" : "Organization",
     "name" : "ChatGPT Plugins",
      "url" : "https://www.plugin.support",
 "sameAs" : [
   "https://twitter.com/chatgptplugins",
   "https://www.facebook.com/chatgptplugins",
   "https://www.instagram.com/chatgptplugins/?hl=nl",
   "https://nl.pinterest.com/chatgptplugins/"
   ],
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Joop Geesinkweg 901",
    "addressRegion": "Noord-Holland",
    "postalCode": "1114AB",
    "addressCountry": "NL"
  }
}
</script>
<?php
    global $nonce;
?>
<script nonce="<?php echo $nonce; ?>">
    document.addEventListener("DOMContentLoaded",function(){var e;if("IntersectionObserver"in window){e=document.querySelectorAll(".lazy");var n=new IntersectionObserver(function(e,t){e.forEach(function(e){if(e.isIntersecting){var t=e.target;t.classList.remove("lazy"),n.unobserve(t)}})});e.forEach(function(e){n.observe(e)})}else{var t;function o(){t&&clearTimeout(t),t=setTimeout(function(){var n=window.pageYOffset;e.forEach(function(e){e.offsetTop<window.innerHeight+n&&(e.src=e.dataset.src,e.classList.remove("lazy"))}),0==e.length&&(document.removeEventListener("scroll",o),window.removeEventListener("resize",o),window.removeEventListener("orientationChange",o))},20)}e=document.querySelectorAll(".lazy"),document.addEventListener("scroll",o),window.addEventListener("resize",o),window.addEventListener("orientationChange",o)}});
</script>
<!-- top offers -->
<section id="top-offers">
    <div class="wrapper">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="last-updated">
                <span class="title">Updated <strong><?= $this->functions->timestamp(time(), 'j F Y'); ?></strong></span>
            </div>
            <div class="container flex-box flex-justify-center">
                <?php
                foreach ($this->data['top-offers'] as $key => $row) {
                ?>
                <div class="card" data-id="<?= $row['ID']; ?>">
                <?php if (!$key) { echo '<span class="ribbon">Best Choice</span>';} ?>
                    <div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
                        <div class="grade"><?= $row['rating']; ?></div>
                            <picture class="thumbnail flex-box flex-align-center flex-justify-center" data-subject="<?= $row['ID']; ?>">
                                <source type="image/webp" srcset="/images/plugins/symbols/webp/<?= $row['slug']; ?>-apple-touch-icon.webp" media="(max-width: 480px)">
                                <source type="image/webp" srcset="/images/plugins/symbols/<?= $row['symbol']; ?>" media="(min-width: 480px)">
                                <img srcset="/images/plugins/symbols/jpg/<?= $row['slug']; ?>-apple-touch-icon.jpg, /images/plugins/symbols/png/<?= $row['slug']; ?>-apple-touch-icon.png 2x" src="/images/plugins/symbols/<?= $row['symbol']; ?>" width="90" height="90" alt="logo <?=$row['name'];?>">
                            </picture>
                            <div class="details">
                                <p class="description"><a href="/reviews/<?= $row['slug']; ?>/"><?= $row['name']; ?></a></p>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
                            <div class="rating" data-evaluate="<?= $row['slug'] ;?>">
                                <span class="stars"></span>
                                <span class="stars-filled" data-value="<?= (!empty($row['score']) ? round($row['score'],0,PHP_ROUND_HALF_UP) : '5.0'); ?>"></span>
                            </div>
                            <div class="details">
                                <span data-subject="<?= $row['ID']; ?>" class="ratings"><?= ($row['ratings']); ?></span>
                                <span class="btn btn-subject btn-medium btn-round" rel="noopener" data-subject="<?= $row['ID']; ?>">Show more</span>
                                <a href="/reviews/<?= $row['slug']; ?>/" class="reviews"><?= $row['name']; ?> review</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                </div>
            <button class="btn btn-secondary btn-large btn-round btn-center" data-href="/reviews/">Show all plugins</button>
        </div>
    </div>
</section>
<!-- homepage -->
<div id="home">
    <section id="intro">
        <div class="wrapper">
            <div class="container">
                <h2 class="title">Are you ready to transform your ChatGPT experience?</h2>
                <span class="head-subLine"></span>
                <p class="description">Welcome to the #1 <strong>ChatGPT Plugin Library</strong>, your one-stop destination for everything you need to know about ChatGPT plugins. With over 800 plugins across 75 categories, this guide is your treasure trove to the best-ranked GPT-4 plugins and their alternatives. Whether you're a seasoned user or a newcomer, this guide will help you navigate the world of ChatGPT plugins with ease and confidence. So, why wait? Let's dive in and unlock the full potential of ChatGPT plugins!</p>
                <h2>Understanding ChatGPT Plugins</h2>
                <h3>What are ChatGPT Plugins?</h3>
                <p class="description">ChatGPT plugins are powerful tools that enhance the capabilities of the ChatGPT AI developed by OpenAI. These plugins, ranging from the simple <strong>chatgpt plugin</strong> to the more advanced <strong>chatgpt wordpress plugin</strong> or <strong>chatgpt chrome plugin</strong>, allow the AI to perform a variety of tasks, from simple text generation to complex computations and data analysis.</p>
                <h3>Why Use ChatGPT Plugins?</h3>
                <p class="description">The use of <strong>chatgpt plugins</strong> can significantly enhance your interaction with the AI. Whether you're using the <strong>chatgpt plugin for chrome</strong> or the <strong>gmail chatgpt plugin</strong>, these tools can provide additional functionality, making your AI experience more interactive and productive.</p>
                <h3>How to Use ChatGPT Plugins?</h3>
                <p class="description">The process of learning <strong>how to use chatgpt plugins</strong> is straightforward. Once installed, these plugins can be activated within the ChatGPT interface, allowing the AI to utilize their functionality. For instance, the <strong>chatgpt google plugin</strong> can enable the AI to perform Google searches, providing real-time, up-to-date information.</p>
            </div>
        </div>
    </section>
    <!-- categories -->
    <section class="reviews categories">
        <div class="wrapper">
            <h2 class="title">Discovering Plugins Across 75 Categories</h2>
            <span class="head-subLine"></span>
            <p class="description">Learn about the best Chat GPT-4 plugins that can help you connect with right OpenAI's tools that fits all your needs. Our guide offers a category-wise breakdown of over 800 plugins, helping you discover the perfect "chatgpt plugin" for your specific needs.</p>
            <div id="textmore2" class="hidden__text">
                <ul>
                    <?php
                    foreach ($this->data['categories'] as $row) {
                        echo '<li>';
                        echo '<a href="/' . $row['slug'] . '/">';

                        echo '<div class="icon bg-image lazy" id="' . $row['theme'] . '"><img src="/images/icons/category/' . $row['icon'] . '" alt="' . $row['name'] . '" width="50" height="50" loading="lazy"/></div>';

                        echo '<span class="description">' . $row['name'] . '</span>';

                        echo '</a>';
                        echo '</li>';
                    }
                    ?>
                </ul>
                <div class="show-more">
                    <button class="btn__text2" onclick="toggleChanges2();">show more</button>
                </div>
            </div>
        </div>
    </section>
    <!-- reviews -->
    <section class="reviews">
        <div class="container">
            <h2 class="title">Recent Complaints and Success Stories</h2>
            <span class="head-subLine"></span>
            <span class="description">Our guide also offers tips for choosing the right "chatgpt plugin", helping you make the most of your AI experience.</span>
            <div class="container flex-box flex-wrap flex-direction-row flex-justify-space-between flex-align-start">
                <?php
                if (!empty($this->data['reviews'])){
                    foreach ($this->data['reviews'] as $row) {
                        # review card
                        echo '<div class="card">';

                        # thumbnail
                        echo '<div class="thumbnail" data-mood="' . round($row['score'] / 20) . '">';
                        echo '<img src="/images/icons/users/' . $row['thumbnail'] . '" alt="' . $row['name'] . ', auteur van de beoordeling" width="60" height="60" loading="lazy"/>';
                        echo '</div>';

                        # details
                        echo '<div class="card-header">';
                        echo '<span class="name">' . $row['name'] . ' : </span>';

                        # rating
                        echo '<div class="rating" data-evaluate="' . $row['slug'] . '">';
                        echo '<span class="stars"></span>';
                        echo '<span class="stars-filled" data-value="' . round($row['score'],0,PHP_ROUND_HALF_UP) . '"></span>';
                        echo '</div>';
                        echo '</div>';

                        # website
                        echo '<span class="website"> <img src="/images/symbols/18x18/' . $row['symbol'] . '" data-subject="' . $row['ID'] . '" alt="' . $row['slug'] . ' merk logo" width="20" height="20" loading="lazy"/> ' . $row['website'] . '</span>';

                        # title
                        echo '<span class="title"><a href="/reviews/' . $row['slug'] . '/' . $row['r_slug'] . '/">' . $row['title'] . '</a></span>';

                        # description
                        echo '<div class="description">' . $this->functions->shorter($row['description'], 140) . '</div>';

                        # end of review card
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
    </section>
    <!-- guide -->
    <section class="information">
        <div class="wrapper">
            <div class="row grid-box">
                <h2 class="title">Exploring the Best ChatGPT Plugins</h2>
                <span class="head-subLine"></span>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 images-box">
                        <img src="/images/content/home/verschillende-datingsite-categorieen.svg" alt="Top-Ranked GPT-4 Plugins" width="450" height="315" loading="lazy"/>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-box">
                        <small>OpenAI Plugins</small>
                        <h3>Top-Ranked GPT-4 Plugins</h3>
                        <p>When it comes to <strong>best chatgpt plugins</strong>, there are several top-ranked GPT-4 plugins that stand out. These include the <strong>openai chatgpt plugins</strong> and the <strong>chatgpt plugins openai</strong>, both of which offer advanced features and capabilities.</p>
                        <a href="#home">Show all categories</a>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 grid-box">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-box">
                        <small>The Best Practices</small>
                        <h3>In-depth Reviews of Top Plugins</h3>
                        <p>Our guide provides in-depth reviews of these top plugins, including the <strong>chatgpt plugin</strong>, <strong>chatgpt wordpress plugin</strong>, and <strong>chatgpt chrome plugin</strong>. These reviews provide detailed information about each plugin's features, benefits, and how they can enhance your ChatGPT experience.</p>
                        <a href="/how-we-score/">Learn more</a>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 images-box">
                        <img src="/images/content/home/dating-site-reputatie-check.svg" alt="In-depth Reviews of Top Plugins" width="450" height="340" loading="lazy"/>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 images-box">
                        <img src="/images/content/home/beschermen-tegen-datingfraude.svg" alt="Comparison of Top Plugins" width="450" height="340" loading="lazy"/>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-box">
                        <small>Compare Hundreds Of Plugins</small>
                        <h3>Comparison of Top Plugins</h3>
                        <p>We also provide a comparison of the top plugins, helping you decide which <strong>chatgpt plugin</strong> or <strong>plugin chatgpt</strong> is the best fit for your needs. Whether you're looking for a simple text generation tool or a more advanced data analysis plugin, our guide can help you make an informed decision.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="guide">
        <div class="wrapper">
            <div class="row">
                <div class="container">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h2 class="title">Tips for Choosing the Right Plugin</h2>
                    <span class="head-subLine"></span>
                    <span class="description">Our guide also offers tips for choosing the right "chatgpt plugin", helping you make the most of your AI experience.</span>
                </div>
                <div class="col-lg-l2 col-md-12 col-sm-12 col-xs-12">
                        <?php
                        foreach ($this->data['articles'] as $row)
                        {
                            ?>
                            <div class="card" data-href="/<?=$this->template->configuration['article_slug'];?>/<?= $row['slug']; ?>/">
                                <div class="thumbnail">
                                    <img src="/images/guide/366x198/<?= $row['thumbnail']; ?>" alt="<?=$row['title'];?>" width="366" height="198" loading="lazy"/>
                                </div>
                                <div class="details">
                                    <span class="title"><?= $row['title']; ?></span>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <button class="btn btn-secondary btn-large btn-round btn-center" data-href="/<?=$this->template->configuration['article_slug'];?>/">Show all guides</button>
                </div>
            </div>
        </div>
    </section>
</div>
<?php
$this->template->display('app/footer');

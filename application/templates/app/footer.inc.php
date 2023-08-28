<!-- sitemap -->
<footer id="sitemap">
    <section class="wrapper sticky-stopper">
            <section class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <img src="/logo.svg" alt="logo <?= $this->website['name']; ?>" class="logo" width="235" height="54" loading="lazy"/>
                <span class="copyright">Copyright &copy; <?= date('Y'); ?> <a href="https://globalrankgroup.com/" target="_blank" rel="noopener">Global Rank Group Ltd.</a></span>
                <span class="rights">All rights reserved.</span>
                <span class="title">Secure</span>
                <ul class="brands">
                    <li><img src="/images/content/ssl-secured.png" alt="beveiligd met ssl" width="68" height="19" loading="lazy"/></li>
                    <li><img src="/images/content/mcafee-secured.png" alt="beveiligd met mcafee" width="67" height="21" loading="lazy"/></li>
                    <li><img src="/images/content/norton-secured.png" alt="beveiligd met norton" width="67" height="26" loading="lazy"/></li>
                </ul>
                <span class="title">Social</span>
                <ul class="menu">
                    <li><a href="https://www.facebook.com/chatgptplugins" target="_blank" rel="noopener" class="facebook"><img src="/images/icons/social/facebook.svg" width="24" height="24" class="subject" alt="app icon facebook" loading="lazy"> @chatgptplugins</a></li>
                    <!---<li><a href="https://twitter.com/datingsites_bd" target="_blank" rel="noopener" class="twitter"><img src="/images/icons/social/twitter.svg" width="24" height="24" class="subject" alt="app icon twitter" loading="lazy"> @datingsites_bd</a></li>-->
                    <!---<li><a href="https://www.instagram.com/chatgptplugins" target="_blank" rel="noopener" class="instagram"><img src="/images/icons/social/instagram.svg" width="24" height="24" class="subject" alt="app icon instagram" loading="lazy"> @chatgptplugins</a></li>-->
                    <!---<li><a href="https://nl.pinterest.com/chatgptplugins/" target="_blank" rel="noopener" class="pinterest"><img src="/images/icons/social/pinterest.svg" width="24" height="24" class="subject" alt="app icon pinterest" loading="lazy"> @chatgptplugins</a></li>-->
                </ul>
            </section>
            <section class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p class="description"><?= $this->website['footer_description']; ?></p>
                    </div>
                    <nav>
                        <ul class="menu col-lg-4 col-md-12 col-sm-0 col-xs-0">
                            <li class="title"><?= ucfirst($this->website['domain']); ?></li>
                            <?php
                            # display page urls
                            foreach ($this->pages() as $row) {
                                echo '<li><a href="/' . $row['slug'] . '/">' . $row['name'] . '</a></li>';
                            }
                            ?>
                            <li><a href="/compare/">GPT-4 Plugins Compare</a></li>
                        </ul>
                        <ul class="menu col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <li class="title">Reviews</li>
                                <?php
                                # display top offers
                                foreach ($this->reviews() as $row) {
                                    echo '<li><a href="/reviews/' . $row['slug'] . '/" class="title"><img src="/images/plugins/symbols/' . $row['symbol'] . '" width="18" height="18" class="subject" alt="logo ' . $row['name'] . '" loading="lazy"> ' . $row['name'] . ' Reviews</a> <span class="description">' . $row['slogan'] . '</span></li>';
                                }
                                ?>
                        </ul>
                        <ul class="menu col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <li class="title">Categories</li>
                            <?php
                            # display categories
                            foreach (array_slice($this->categories, 0, 12) as $key => $row) {
                                echo '<li><a href="/' . $row['slug'] . '/" class="title"><img src="/images/icons/category/' . $row['icon'] . '" width="24" height="24" class="category" alt="' . $row['name'] . '" loading="lazy"> ' . $row['name'] . '</a> <span href="/' . $row['slug'] . '/"
                                 class="description">' . $row['title'] . '</a></li>';
                            }
                            ?>
                        </ul>
                    </nav>
                </div>
            </section>
    </section>
</footer>
</body>
</html>
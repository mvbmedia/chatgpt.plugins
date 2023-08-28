<?php
# initiate modal
$app = new Index;

# display head
$this->template->display('app/head', [
    # meta tags
    'meta_title' => $this->data['page']['meta_title'],
    'meta_description' => $this->data['page']['meta_description'],
    'meta_keywords' => $this->data['page']['meta_keywords'],

    # canonical
    'canonical' => '/' . $this->data['page']['slug'] . '/',

    # css style
    'stylesheet' => 'pages.css',
    # print style
    'print' => $this->data['page']['print'],

    # open graph tags
    'og_image' => '/images/pages/' . $this->data['page']['og_image'],

    'schema' => (file_exists(APP . 'templates/schema/' . $this->data['page']['slug'] . '.html')) ? file_get_contents(APP . 'templates/schema/' . $this->data['page']['slug'] . '.html') : null

]);

# display menu
$this->template->display('app/menu', [
    'status' => 'active'
]);
?>
<!-- banner -->
<div id="banner" class="page-banner">
    <div class="wrapper">
        <div class="details">
            <h1><?=$this->data['page']['name'];?></h1>
            <h2><?=$this->data['page']['name'];?></h2>
        </div>
    </div>
</div>
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
                    'name' => $this->data['page']['name'],
                    'href' => '/' . $this->data['page']['slug'] . '/'
                ]
            ];

            foreach ($breadcrumbs as $index => $breadcrumb) :
            ?>
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="<?= $breadcrumb['href'] ?>">
                        <span itemprop="name"><?= $breadcrumb['name'] ?></span>
                    </a>
                    <meta itemprop="position" content="<?= $index + 1 ?>">
                </li>
             <?php endforeach; ?>
        </ol>
    </div>
</nav>
<!-- content -->
<div id="content">
    <div class="wrapper">
        <div class="row">
            <div class="container">
                <a href="javascript:print();" class="print-page" title="Deze pagina afdrukken" arial-role="link" arial-label="Print this Article">
                    <img src="/images/icons/print.svg" alt="Print" width="22" height="22" class="icon-print" />
                    <span class="text-print">Printen</span>
                </a>
                <?php if (!empty($this->data['page']['og_image'])): ?>
                    <header class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <img src="https://chatgpt.plugin.support/images/pages/<?= $this->data['page']['og_image'] ?>" alt="how we rank" width="586" height="337"/>
                    </header>
                <?php endif; ?>
                <?php

                # set content
                $content = $this->data['page']['content'];

                echo $app->generateTableOfContents($content);
                # set date
                $content = str_replace('{{date}}', $this->functions->timestamp(time()), $content);

                # set year
                $content = str_replace('{{year}}', $this->functions->timestamp(time(), 'Y'), $content);

                # set month
                $content = str_replace('{{month}}', $this->functions->timestamp(time(), 'B'), $content);

                # set day
                $content = str_replace('{{day}}', $this->functions->timestamp(time(), 'e'), $content);

                echo $content;
                ?>
            </div>
        </div>
    </div>
</div>
<?php
$this->template->display('app/footer');
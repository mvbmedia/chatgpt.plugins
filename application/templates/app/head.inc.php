<?php
# website data
$website = $this->website;

# Helper function to trim and replace spaces
function trim_and_replace_spaces(string $str): string
{
    return trim(preg_replace('/\s+/', ' ', $str));
}

# meta title
$data['meta_title'] = $data['meta_title'] ?? $website['meta_title'];
$data['meta_title'] = trim_and_replace_spaces($data['meta_title']);

# meta description
$data['meta_description'] = $data['meta_description'] ?? $website['meta_description'];
$data['meta_description'] = trim_and_replace_spaces($data['meta_description']);

# meta keywords
$data['meta_keywords'] = $data['meta_keywords'] ?? $website['meta_keywords'];
$data['meta_keywords'] = trim_and_replace_spaces($data['meta_keywords']);

# open graph image
$data['og_image'] = $data['og_image'] ? 'https://www.' . $website['domain'] . $data['og_image'] : '';
?>
<!DOCTYPE html>
<html lang="en-<?= strtolower($website['country']); ?>" dir="ltr" prefix="og: http://ogp.me/ns#">
<head>
    <title><?= htmlspecialchars($data['meta_title'], ENT_QUOTES, 'UTF-8'); ?></title>
    <meta charset="utf-8"> 
    <meta name="referrer" content="origin">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Global Rank Group Limited" >
    <link rel="preconnect" href="https://www.googletagmanager.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="<?= htmlspecialchars($data['meta_description'], ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="keywords" content="<?= htmlspecialchars($data['meta_keywords'], ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#0970E3">
    <meta name="msapplication-config" content="/browserconfig.xml">
    <meta property="og:title" content="<?= htmlspecialchars($data['meta_title'], ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:description" content="<?= htmlspecialchars($data['meta_description'], ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <?php if (!empty($data['og_image'])): ?>
    <meta property="og:image" content="<?= $data['og_image']; ?>">
    <?php endif; ?>
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:alt" content="<?= htmlspecialchars($data['meta_title'], ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="plugin.support">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@chatgpt_plugins">
    <meta name="twitter:title" content="<?= htmlspecialchars($data['meta_title'], ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($data['meta_description'], ENT_QUOTES, 'UTF-8'); ?>">
    <?php if (!empty($data['og_image'])): ?>
    <meta name="twitter:image" content="<?= $data['og_image']; ?>">
    <?php endif; ?>
    <meta name="twitter:image:alt" content="<?= htmlspecialchars($data['meta_title'], ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="/sitemap.xml">
    <link rel="alternate" hreflang="be" href="<?= 'https://' . $this->website['type'] . '.plugin.support' . $data['canonical']; ?>">
    <link rel="alternate" hreflang="nl" href="<?= 'https://' . $this->website['type'] . '.plugin.support/nl/' . $data['canonical']; ?>">
    <link rel="alternate" hreflang="en" href="<?= 'https://' . $this->website['type'] . '.plugin.support/en/' . $data['canonical']; ?>">
    <link rel="alternate" hreflang="x-default" href="<?= 'https://' . $this->website['type'] . '.plugin.support' . $data['canonical']; ?>">
    <link rel="canonical" href="<?= 'https://' . $this->website['type'] . '.' . $website['domain'] . $data['canonical']; ?>">
    <link rel="stylesheet preload prefetch" as="style" href="/css/fonts.css?v=1.0.2" type="text/css" media="screen">
    <link rel="stylesheet" href="/css/style.css?v=1.0.2" type="text/css" media="screen">
    <link rel="stylesheet" href="/css/template.css?v=1.0.2" type="text/css" media="screen">
    <link rel="stylesheet" href="/css/responsive.css?v=1.0.2" type="text/css" media="screen">
    <link rel="stylesheet" href="/css/custom.css?v=1.0.2" type="text/css" media="screen">
    <?php if (!empty($data['stylesheet'])): ?>
        <link rel="stylesheet" href="/css/<?= $data['stylesheet'] ?>?v=1.0.2" type="text/css" media="screen">
    <?php endif; ?>
    <link rel="shortcut icon" href="/favicon.svg?v=1.0.2">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=1.0.2">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=1.0.2">
    <link rel="icon" type="image/png" sizes="192x192" href="/android-chrome-192x192.png?v=1.0.2">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v=1.0.2">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#7c60eb">
    <meta name="apple-mobile-web-app-title" content="ChatGPT Plugin Store">
    <meta name="application-name" content="ChatGPT Plugin Store">
    <meta name="msapplication-TileColor" content="#7c60eb">
    <meta name="msapplication-TileImage" content="/mstile-144x144.png?v=1.0.2">
    <meta name="theme-color" content="#7c60eb">
    <link rel="manifest" href="/manifest.json?v=1.0.2" crossorigin="use-credentials">
    <script src="/js/jquery-3.5.1.min.js?v=1.0.2"></script>
    <script src="/js/functions.js?v=1.0.2" async defer></script>
    <?php
        # Generate a unique nonce value
        $nonce = bin2hex(random_bytes(16));

        # Update the script tag with nonce attribute
        function add_nonce_to_script_tag(string $html, string $nonce): string
        {
            return preg_replace('/<script(.*?)>/i', '<script$1 nonce="' . $nonce . '">', $html);
        }
        ob_start('add_nonce_to_script_tag');
    ?>
    <!-- Updated script tags -->
    <script nonce="<?php echo $nonce; ?>" src="/js/jquery-3.5.1.min.js?v=1.0.2"></script>
    <script nonce="<?php echo $nonce; ?>" src="/js/functions.js?v=1.0.2" async defer></script>
    <script nonce="<?php echo $nonce; ?>">
        document.addEventListener('scroll', initGTMOnEvent);
        document.addEventListener('mousemove', initGTMOnEvent);
        document.addEventListener('touchstart', initGTMOnEvent);
        document.addEventListener('DOMContentLoaded', () => { setTimeout(initGTM, 2000); });
        
        function initGTMOnEvent (event) {
            initGTM();
            event.currentTarget.removeEventListener(event.type, initGTMOnEvent);
        }

        // Initializes Google Tag Manager
        function initGTM () {
            if (window.gtmDidInit) {
              // Don't load again
              return false;
            }

            window.gtmDidInit = true;
            
            // Create the script
            const script = document.createElement('script');
            script.type = 'text/javascript';
            script.onload = () => { 
              window.dataLayer = window.dataLayer || [];
              function gtag(){ dataLayer.push(arguments); }

              gtag('consent', 'update', {
                'ad_storage': 'granted',
                'analytics_storage': 'granted',
                'wait_for_update': 2000
              });

              gtag('js', new Date());
              gtag('config', '<?= htmlspecialchars($this->website['google_analytics'], ENT_QUOTES, 'UTF-8'); ?>');
            }
            script.src = 'https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($this->website['google_analytics'], ENT_QUOTES, 'UTF-8'); ?>';

            // We are still deferring the script
            script.defer = true;
            
            // Append the script to the body of the document
            document.getElementsByTagName('body')[0].appendChild(script);
        }
    </script>
<?php
    # rich snippet review
    if (isset($data['review']) && !empty($data['review'])) {
        # rich snippet
        $object = array();

        # context
        $object['@context'] = 'https://schema.org';

        # type
        $object['@type'] = 'Review';

        # name
        $object['name'] = $data['review']['name'] . ' review';

        # description
        $object['description'] = $data['review']['description'];

        # item reviewed
        $object['itemReviewed'] = [
            '@type' => 'Organization',
            'name' => $data['review']['name']
        ];

        # headline
        $object['headline'] = $data['review']['name'] . ': ' . $data['review']['slogan'];

        # review
        $object['reviewBody'] = $data['review']['review'];

        # date published
        $object['datePublished'] = $data['review']['published'];

        # review author
        $object['author'] = [
            '@type' => 'Organization',
            'name' => $this->website['name']
        ];

        # url
        $object['url'] = $this->website['host'] . '://' . $this->website['type'] . '.' . $this->website['domain'] . $data['review']['url'];

        # image
        $object['image'] = [
            '@type' => 'ImageObject',
            'url' => $this->website['host'] . '://' . $this->website['type'] . '.' . $this->website['domain'] . '/images/symbols/' . $data['review']['symbol'],
            'caption' => $data['review']['name']
        ];

        # entity
        $object['mainEntityOfPage'] = [
            '@type' => 'WebPage',
            '@id' => 'https://' . $this->website['type'] . '.' . $this->website['domain'] . $data['review']['url']
        ];

        #  thumbnail
        $object['thumbnailUrl'] = $this->website['host'] . '://' . $this->website['type'] . '.' . $this->website['domain'] . '/images/thumbnails/' . $data['review']['thumbnail'];

        # publisher
        $object['publisher'] = [
            '@type' => 'Organization',
            'name' => $this->website['name'],
            'url' => 'https://www.' . $this->website['domain'],
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $this->website['host'] . '://' . $this->website['type'] . '.' . $this->website['domain'] . '/images/content/logo.png',
                'caption' => $this->website['name'] . ': ' . $this->website['slogan']
            ]
        ];

        # rating
        $object['reviewRating'] = [
            '@type' => 'Rating',
            'ratingValue' => $data['review']['rating']['average'],
            'bestRating' => $data['review']['rating']['best'],
            'worstRating' => $data['review']['rating']['worst'],
            'author'=> [
              '@type' => 'Person',
              'name' => $data['review']['name']
            ]
        ];

    # display encoded json
    echo '<script type="application/ld+json">' . json_encode($object) . '</script>';

    }# rich snippet category
     if (isset($data['category']) && !empty($data['category'])) {
        # rich snippet
        $object = array();

        # context
        $object['@context'] = 'https://schema.org';

        # type
        $object['@type'] = 'article';

        # name
        $object['name'] = $data['category']['name'];

        # description
        $object['description'] = $data['category']['description'];

        # headline
        $object['headline'] = $data['category']['title'];

        # category
        $object['articleBody'] = $data['category']['description'];

        # date published
        $object['datePublished'] = $data['category']['published'];

        # category author
        $object['author'] = [
            '@type' => 'Organization',
            'name' => $this->website['name']
        ];

        # url
        $object['url'] = $this->website['host'] . '://' . $this->website['type'] . '.' . $this->website['domain'] . $data['category']['url'];

        # image
        $object['image'] = [
            '@type' => 'ImageObject',
            'url' => $this->website['host'] . '://' . $this->website['type'] . '.' . $this->website['domain'] . '/images/icons/category/' . $data['category']['icon'],
            'caption' => $data['category']['name']
        ];

        # entity
        $object['mainEntityOfPage'] = [
            '@type' => 'WebPage',
            '@id' => 'https://' . $this->website['type'] . '.' . $this->website['domain'] . $data['category']['url']
        ];

        #  thumbnail
        $object['thumbnailUrl'] = $this->website['host'] . '://' . $this->website['type'] . '.' . $this->website['domain'] . '/images/themes/' . $data['category']['thumbnail'];

        # publisher
        $object['publisher'] = [
            '@type' => 'Organization',
            'name' => $this->website['name'],
            'url' => 'https://' . $this->website['type'] . '.' . $this->website['domain'],
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $this->website['host'] . '://' . $this->website['type'] . '.' . $this->website['domain'] . '/images/content/logo.png',
                'caption' => $this->website['name'] . ': ' . $this->website['slogan']
            ]
        ];

        # display encoded json
        echo '<script type="application/ld+json">' . json_encode($object) . '</script>';
    }

    # css style
    if (isset($data['style']) && !empty($data['style'])) {
        echo '<style>' . preg_replace('/\s+/', ' ', $data['style']) . '</style>';
    }
    # print css
    if (isset($data['print']) && !empty($data['print'])) {
    echo '<style type="text/css" media="print">' . preg_replace('/\s+/', ' ', $data['print']) . '</style>';
    }
    ?>
    <?=$data['schema'] ?? null?>
    <script src="/js/cookies.js"></script>
    <?php
    # Close the output buffer
    ob_end_flush();
    ?>

</head>
<body>

importScripts('/cache.js');

const version = "1.0.0";
const cacheName = 'betrouwbare-datingsites-BE' + version;

/* install service worker */
self.addEventListener('install', function (e) {
    e.waitUntil(
        caches.open(cacheName).then(function (cache) {
            return cache.addAll([
                '/js/jquery-3.5.1.min.js',
                '/css/style.css',
                '/css/template.css',
                '/css/responsive.css',
                '/css/fonts.css',
                '/css/custom.css',
                '/fonts/Roboto-Light.ttf',
                '/fonts/Roboto-Medium.ttf',
                '/fonts/Roboto-Regular.ttf',
                '/fonts/Roboto-Bold.ttf',
                '/images/themes/webp/index.png.webp',
                '/logo.svg',
                '/favicon.ico'
            ]);
        })
    );
});

/* activate service worker */
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cache => {
                    if (cache !== cacheName) {
                        return caches.delete(cache);
                    }
                })
            );
        })
    );
});

/* fetch cached files */
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request).then(response => {
            if (event.request.cache === 'only-if-cached' && event.request.mode !== 'same-origin') {
                return;
            } else {
                return response || fetch(event.request);
            }
        }).catch(() =>
            console.log("Couldn't load this file")
        )
    );
});
/* cookies */
var cookies = (function(){
    return {
        'duration': 90,
        'name': 'consent',
        'value': 'accepted',
        'createCookie': function(name, value, days){
            if (days) {
                let date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                var expires = "; expires=" + date.toGMTString();
            } else {
                var expires = "";
            }
            document.cookie = name + "=" + value + expires + "; path=/";
        },
        'checkCookie': function(name) {
            var name = name + "=";
            var ca = document.cookie.split(';');

            for(var i=0;i < ca.length;i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);

                if (c.indexOf(name) == 0){
                    return c.substring(name.length,c.length);
                }
            }

            return null;
        },
        'eraseCookie': function(name) {
            cookies.createCookie(name, '', -1);
        },
        'createAcceptCookie': function() {
            cookies.createCookie(cookies.name, cookies.value, cookies.duration);
        },
        'closeBanner': function() {
            $('#cookie').hide();
        },
        'accept': function() {
            /* consent gtag */
            if (typeof gtag !== 'undefined') {
                gtag('consent', 'update', {
                    'ad_storage': 'granted',
                    'analytics_storage': 'granted'
                });
            }

            cookies.createAcceptCookie();
            cookies.closeBanner();
        },
        'display': function(html) {
            if(cookies.checkCookie(cookies.name) != cookies.value){
                $('body').prepend(html);
            } else {
                cookies.accept();
            }
        },
        'init': function() {
            /* accept cookies */
            $(document).on('click', '[data-cookie]', function() {
                cookies.accept();
            });

            /* display cookie notification */
            $(window).on('load', function() {
                cookies.display('<div id="cookie"><p class="description">Wij gebruiken cookies en andere technieken om uw gebruikers ervaring op onze site te verbeteren en personaliseren. Wij zijn een onafhankelijk partij en beheren zelf geen datingsites <a href="/privacy/">Meer informatie</a></p><button class="btn btn-primary btn-medium btn-round" data-cookie="accept">Accepteren</button></div>');
            });
        }
    }
})();

cookies.init();
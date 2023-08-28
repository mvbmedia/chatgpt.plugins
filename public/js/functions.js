var items=[],offset=0,version="1.0.2";
$(document).on("click","[data-href]",function(e){window.location.href=$(this).data("href")}),
$(document).on("click","[data-tab]",function(){window.open($(this).data("tab"),"_blank")}),
$(document).on("click","[data-toggle]",function(){$("."+$(this).data("toggle")).fadeToggle(250)}),
$(document).on("click",'[data-menu="show"]',function(){$("#navigation .menu").show(),$("#navigation .menu").animate({left:"0"}),$(".menu-hide").delay(400).show(0),$(".menu-search-show").hide(),$("body").animate({left:"300px"}),$("#navigation .logo").hide(),$("body").css("overflow","hidden"),$("body").addClass("active")}),
$(document).on("click",'[data-menu="hide"]',function(){$("#navigation .menu").animate({left:"-300px"}),$("#navigation .menu").delay(400).hide(0),$(".menu-search-show").show(),$("body").animate({left:"0"}),$("#navigation .logo").show(),$(".menu-hide").hide(),$("body").removeAttr("style"),$("body").removeClass("active")}),
$(document).on("click",'[data-search="show"]',function(){$("#navigation").addClass("active"),$("#contentTable").addClass("active"),$("#navigation .search").show(),$("#navigation .search").animate({"margin-left":"0%"}),$("#navigation .logo").hide(),$(".menu-show").hide(),$(".menu-search-show").hide(),$(".menu-search-hide").show(),$(".search-query").focus()}),
$(document).on("click",'[data-search="hide"]',function(){$(".search-results").hide(),$(".search-results").html(""),$(".search-query").val(""),$("#navigation .search").animate({"margin-left":"100%"},function(){$("#navigation .search").hide(),$("#navigation .logo").show(),$(".menu-show").show(),$(".menu-search-hide").hide(),$(".menu-search-show").show()})}),
$(document).on("click","[data-features]",function(){$(this).closest(".card");$(this).closest(".card").find(".features").toggle()}),
$(document).on("click",function(e){($(e.target).is(".modal")||$(e.target).is(".modal-loader"))&&$(".modal").fadeOut(250,function(){$(this).remove()}),$(e.target).is(".filter")&&$(".filter").fadeOut(250,function(){$(this).css("display","none")})}),
$(document).on("click","[data-exit]",function(){$(".modal").fadeOut(250,function(){$(this).remove()})}),
$(document).on("keyup",function(e){"Escape"===e.key&&$(".modal").fadeOut(250,function(){$(this).remove()})}),
$(document).on("click","[data-modal]",function(){var e=$(this).data("modal");$.ajax({url:"/modal/"+e,type:"POST",dataType:"text",beforeSend:function(){$(".modal").html('<div class="modal-loader">&nbsp;</div>').fadeIn(250)},success:function(e){$(".modal").html(e)}})}),
$(document).on("click","[data-content]",function(){for(var e=$(".item-block"),a=$(this).data("content"),t=0;t<e.length;t++)$(e[t]).hide();var n=$(".item-link");for(t=0;t<n.length;t++)$(n[t]).removeClass("active");$(this).addClass("active"),$(".item-"+a).show()}),
$(document).on("click",".star-rating",function(){var e=$(".star-rating").length/5,a=new Array;$(".star-rating:checked").each(function(){a.push(parseInt($(this).val(),10))});var t=a.reduce(function(e,a){return e+a},0),n=Math.round(t/e);$(".star-average").prop("checked",!1),$('.star-average[value="'+n+'"]').prop("checked",!0)}),
$(document).on("keyup",".write-review",function(){$(".current-review-length").text($(this).val().length)}),
$(document).on("click","[data-subject]",function(e){e.preventDefault();var a=new FormData;a.append("id",$(this).data("subject")),$.ajax({url:"/api/click/",type:"POST",data:a,processData:!1,contentType:!1,dataType:"json",beforeSend:function(){$(this).prop("disabled",!0)},success:function(e){if(e.url){var a=window.open(e.url,"_blank");a&&!a.closed&&void 0!==a.closed||window.location.assign(e.url)}else e.message&&alert(e.message);$(this).prop("enabled",!0)}})}),
$(document).on("click","[data-reviews]",function(){offset+=5,$.ajax({url:"/api/reviews/",data:{id:$(this).data("reviews"),offset:offset},type:"POST",dataType:"text",success:function(e){$(".comments .review:last").after(e)}})}),
$(document).on("click","[data-comment]",function(){let e=$(this).data("comment"),a=$('.reply [name="name"]').val(),t=$('.reply [name="email"]').val(),n=$('.reply [name="comment"]').val();var s="";$.ajax({type:"POST",url:"/api/comment/",data:{id:e,name:a,email:t,comment:n},cache:!1,async:!0,dataType:"json",success:function(e){"success"==e.status?(s+='<div class="comment">',s+='<span class="name">'+e.result.name+' <span class="date">'+e.result.date+"</span></span>",s+='<p class="description">'+e.result.comment+"</p>",s+="</div>",$("#comments h5").after(s),$("#comments .no-replies").hide(),$("html, body").animate({scrollTop:$("#comments").offset().top-100},1e3),setTimeout(function(){alert(e.message)},1e3),$('.reply [name="name"]').val(""),$('.reply [name="email"]').val(""),$('.reply [name="comment"]').val("")):alert(e.message)}})}),
$(document).on("click","[data-like]",function(){var e=$(this);$.ajax({type:"POST",url:"/api/like/",data:{id:$(this).data("like")},cache:!1,async:!0,headers:{"cache-control":"no-cache"},dataType:"text",success:function(a){let t=parseInt($(e).find(".likes").html());$(e).hasClass("active")?t--:t++;$(e).find("span.likes").html(t),$(e).toggleClass("active")}})}),
$(document).on("click","[data-evaluate]",function(){var e=$(this).data("evaluate");window.location.href="/evaluate/"+e+"/"}),
$(document).on("click","[data-compare]",function(){var e=$(this),a=$(this).closest(".card"),t=new FormData;t.append("subject",$(this).data("compare")),$.ajax({url:"/api/compare/",type:"POST",data:t,processData:!1,contentType:!1,dataType:"JSON",success:function(t){$(e).hasClass("position")?$(a).fadeOut(500):("success"==t.status&&($(e).toggleClass("active"),$("#direct").remove(),$("body").append(t.html)),"error"==t.status&&alert(t.message))}})}),$(".search-query").on("keyup touchend",function(){if($(this).val().length<1)return $(".search-results").css("display","none"),void $(".search-results").html("");$.ajax({type:"POST",url:"/api/autocomplete/",data:{term:$(this).val()},cache:!1,async:!0,headers:{"cache-control":"no-cache"},dataType:"json",success:function(e){$(".search-results").css("display","block");var a=e.length,t="";if(a>0)for(var n=0;n<a;n++)t+='<div class="item" data-href="/reviews/'+e[n].slug+'/"><img src="/images/plugins/symbols/'+e[n].symbol+'"  width="16" height="16"/> <span class="title">'+e[n].name+"</span></div>";else t+='<div class="item"><span class="title">Geen resultaten gevonden</span></li>';$(".search-results").html(t)}})}),$(window).on("load resize scroll",function(){$(window).scrollTop()>=50?$("#navigation").addClass("scrolled"):$("#navigation").removeClass("scrolled"),$(".popup")&&$(window).scrollTop()>600&&$(".popup").fadeIn(),$("[data-id]").each(function(e,a){var t=$(this).data("id");if($(this).isVisible()){if(items.indexOf(t)<0){var n=new FormData;n.append("id",t),$.ajax({url:"/api/views/",type:"POST",data:n,processData:!1,contentType:!1,cache:!1,dataType:"text"})}items.push(t)}})}),"serviceWorker"in navigator&&navigator.serviceWorker.register("/server.js?v="+version).then(e=>{e.installing,e.waiting,e.active,e.addEventListener("updatefound",()=>{const a=e.installing;a.state,a.addEventListener("statechange",()=>{console.log(a.state)})})}).catch(function(e){console.log("Service Worker registration failed with error:",e)}); 
function toggleChanges() {document.getElementById("textmore").classList.toggle("larger")}
function toggleChanges2() {document.getElementById("textmore2").classList.toggle("larger")}
window.onload = function() {setTimeout(function() {$("p").removeClass("larger")}, 4E3)};
document.addEventListener('DOMContentLoaded', function toggleChanges2(){ var btn = document.querySelector('.btn__text2'); btn.addEventListener('click', function() {var textmore2 = document.getElementById('textmore2');textmore2.classList.toggle('larger2');});});
window.addEventListener("scroll", function() {
    var contentTable = document.getElementById("contentTable");
    var sitemap = document.getElementById("sitemap");

    // Controleer of beide elementen bestaan
    if (contentTable && sitemap) {
        // Distance from top to the #sitemap element
        var footerTop = sitemap.getBoundingClientRect().top;

        // Height of the #contentTable
        var tableHeight = contentTable.offsetHeight;

        if (window.scrollY >= 180) {
            contentTable.classList.add("active");
        } else {
            contentTable.classList.remove("active");
        }

        if (footerTop <= tableHeight) {
            contentTable.classList.add("activebottom");
        } else {
            contentTable.classList.remove("activebottom");
        }
    }
});
document.addEventListener('DOMContentLoaded', function() {
    var buttons = document.querySelectorAll('.intercom-reaction');
    var reactionContainer = document.getElementById('reaction-container');
    var pageId = reactionContainer.getAttribute('data-page-id');
    var pageType = reactionContainer.getAttribute('data-page-type');
    
    buttons.forEach(function(button) {
        button.addEventListener('click', function() {
            var reaction = button.getAttribute('data-reaction-text');

            // Log de waarden
            console.log("Button clicked");
            console.log("Reaction:", reaction);
            console.log("Page ID:", pageId);
            console.log("Page Type:", pageType);
            
            // Stuur de gegevens naar de server
            fetch('/modals/saveReaction', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    reaction: reaction,
                    pageId: pageId,
                    pageType: pageType
                })
            }).then(response => response.json()).then(data => {
                alert('Thanks for your feedback!'); // Voorbeeldbericht
            });
        });
    });
});
  function myFunction(buttonElement) {
    // Haal het ID van het doelelement op uit het data-attribuut van de knop
    const targetId = buttonElement.getAttribute("data-copy-target");
    
    // Selecteer het doelelement met dat ID
    const textToCopy = document.getElementById(targetId).innerText;

    // Kopieer de tekst naar het klembord
    navigator.clipboard.writeText(textToCopy).then(function() {
        // Bewaar de oorspronkelijke inhoud van de knop
        const originalButtonContent = buttonElement.innerHTML;

        // Verander de inhoud van de knop naar een vinkje en de tekst "text copied!"
        buttonElement.innerHTML = "✓ text copied!";

        // Stel een timer in om de inhoud van de knop na 2 seconden terug te veranderen naar de oorspronkelijke inhoud
        setTimeout(function() {
            buttonElement.innerHTML = originalButtonContent;
        }, 2000);

    }).catch(function(err) {
        console.error('Fout bij het kopiëren van de tekst: ', err);
    });
}

jQuery(function ($) {
   /**
    * MOBILE NAV
    */
    
    
    $(document).on('click', function(event) {
        
	    if($(event.target).attr('id') != '32rdph-dd-anchor' && !$(event.target).hasClass('icon-menu')) {
	        $('.top-bar-right > .menu').removeClass("hover");
             $('.top-bar-right > .menu').attr("aria-expanded", false);
             $("#small-navigation-menu")
                .removeClass("is-open")
                .attr("aria-hidden", true);
	    }

	});
	
    
   $(document).on("click", ".top-bar-right > .menu", function () {
      if (!$(this).hasClass("hover")) {
         $(this).addClass("hover");
         $(this).attr("aria-expanded", true);
         $("#small-navigation-menu")
            .addClass("is-open")
            .attr("aria-hidden", false);
      } else {
         $(this).removeClass("hover");
         $(this).attr("aria-expanded", false);
         $("#small-navigation-menu")
            .removeClass("is-open")
            .attr("aria-hidden", true);
      }
   });

   /**
    * STICKY AD
    */
   $(window).on("scroll resize", function () {
      if ($(".sticky-wrapper").hasClass("is-sticky") && $(this).width() < 693) {
         $(".sticky").unstick();
      } else if (
         !$(".sticky-wrapper").hasClass("is-sticky") &&
         $(this).width() > 693
      ) {
         $(".sticky").sticky({ topSpacing: 100 });
      }
   });

   /**
    * AJAX SEARCH
    */

   if ($("#master-search").length) {
      function prodFrontendSearch(inputSelector, listSelector) {
         var q = inputSelector.val();

         if (q.length > 2 /* || q !== "undefined" */) {
            $("#search_in_progress").addClass("active");

            $.ajax({
               url: ajaxurl,
               method: "POST",
               data: {
                  action: "pm_article_search",
                  q: q,
               },
               error: function (jqXHR, textStatus, errorThrown) {
                  console.error("SEARCH AJAX ERROR: " + errorThrown);
               },
            }).done(function (response) {
               if (response.success === true) {
                  /* listSelector.html(response.data);
               return; */
                  //console.log(response.data);
                  listSelector
                     .html(response.data.search_results)
                     .css("width", $("#article-search").outerWidth());

                  /* if (response.data.has_results === true) {
                  listSelector
                     .parent()
                     .append(
                        '<p id="aa-all-search-results-container"><a id="aa-all-search-results">Összes találat <i class="fas fa-chevron-right"></i></a></p>'
                     );
               } else {
                  $("#aa-all-search-results-container").remove();
               } 

               if ($("#aa-all-search-results").length > 0 && s.length > 0) {
                  $("#aa-all-search-results").attr(
                     "href",
                     "/search?q=" + $("#master-search").val()
                  );
               } else {
                  $("#aa-all-search-results").attr("href", "/search");
               }*/

                  //$("body").addClass("prod_frontend_results-open");
                  /* $(".prod_frontend_product_section_right").html(
                  response.data.cat_list
               ); */
                  $("#search_in_progress").removeClass("active");
                  //listSelector.css('height', $('.prod_frontend_product_section_right').outerHeight(true)+'px');
               } else {
                  //location.reload();
               }
            });
         } else {
            $("#search_results_list").html("");
            //$("body").removeClass("prod_frontend_results-open");
            //$(".prod_frontend_product_section_right").html("");
            listSelector.removeAttr("style");
         }
      }

      //prodFrontendSearch($("#master-search"), $("#search_results_list"));

      $(document).on("click", "#search_results_list li", function () {
         window.location = $(this).data("href");
      });

      /** Search input field listener **/

      var TimerS;

      $("#master-search").on("keypress keyup focus", function (e) {
         if (e.which == 13) {
            let q = $("#master-search").val();
            e.preventDefault();

            if (q.length > 0) {
               window.location.href = "/search?q=" + q;
            } else {
               window.location.href = "/search";
            }
         }

         clearTimeout(TimerS);
         TimerS = setTimeout(function () {
            //prodFrontendSearch($("#master-search"), $("#search_results_list"));
         }, 500);
      });

      //$(document).ready(function () {
      const queryString = window.location.search;
      const urlParams = new URLSearchParams(queryString);

      if (queryString !== "") {
         $("#master-search").val($.trim(urlParams.get("q")));
      }
      //});

      $(document).click(function (event) {
         if (
            $(event.target) !== $("#search_results_list") &&
            $(event.target).closest("#search_results_list").length <= 0
         ) {
            //if ($("body").hasClass("prod_frontend_results-open")) {
            $("#search_results_list").html("").removeAttr("style");
            //$(".prod_frontend_product_section_right").html("");
            //$("body").removeClass("prod_frontend_results-open");

            /* if ($("#aa-all-search-results").length > 0) {
               $("#aa-all-search-results-container").remove();
            } */
            //}
         }

         /* if ($(event.target).hasClass("keyw")) {
         $("#master-search").val($(event.target).text());
         prodFrontendSearch(
            $("#master-search"),
            $("#search_results_list")
         );
      } */
      });
   }

   /**
    *  MUSIC PLAYER
    * */

   $.fn.extend({
      uniqueId: (function () {
         var e = 0;
         return function () {
            return this.each(function () {
               this.id || (this.id = "ui-id-" + ++e);
            });
         };
      })(),
      removeUniqueId: function () {
         return this.each(function () {
            /^ui-id-\d+$/.test(this.id) && e(this).removeAttr("id");
         });
      },
   });

   function unicodeToString(unicodeString) {
      return unicodeString;
      //let decoded = decodeURIComponent(JSON.parse('"' + unicodeString.replace(/\"/g, '\\"') + '"'));
      let decoded = decodeURIComponent(JSON.parse('["' + unicodeString + '"]'));
      console.log(unicodeString + " => " + decoded);
      return decoded;
   }

   function handleAudioPlaylistPlayer($objects, isMainPlayer) {
      $objects.each(function (index, object) {
         const $object = $(object),
            tracks = isMainPlayer
               ? [
                    {
                       TrackID: $objects.data("trackid"),
                       Artist: $objects.data("artist"),
                       Song: $objects.data("song"),
                    },
                 ]
               : $object.data("tracks"),
            markup = `
                <div class='player'></div>
                <div class='large-12 title'></div>
                <div class='scrub-bar large-12 medium-12 small-12'>
                    <div class="float-right features timers">
                        <div class="jp-current-time"></div> / <div class="jp-duration"></div>
                    </div>
                    <div class="jp-seek-bar"><div class="jp-play-bar"></div></div>
                </div>
                <div class='controls large-12 medium-12 small-12 grid-x'>
                    <div class='large-1 medium-2 small-3 features play-pause'>
                        <a href='#' class='play icon-player-play' tabindex='1'></a>
                        <a href='#' class='pause icon-player-pause' tabindex='1'></a>
                    </div>
                    <div class='large-1 medium-2 small-3 features mute-unmute'>
                        <a href='#' class='mute icon-player-volume' tabindex='1'></a>
                        <a href='#' class='unmute icon-player-mute' tabindex='1'></a>
                    </div>
                    <div class="large-auto medium-auto small-3 features volume-bar-container"><div class="jp-volume-bar"><div class="jp-volume-bar-value"></div></div></div>
                    <a href="/" target="_top" class="large-2 medium-2 small-3 paste"><img src="${templateDirURI}/img/master-header-logo-blue.png" alt="PASTE" /></a>
                </div>
                <a href='#' class='non next icon-right-dir'></a>`,
            playerId = $object
               .append(markup)
               .find(".player")
               .uniqueId()
               .get(0).id;
         var track = $object.data("track") || 0;
         $object.find(".next").on("click", function (e) {
            if (isMainPlayer) {
               //window.location.href = $objects.data("next-url");
            } else {
               e.preventDefault();
               const $this = $(this);
               track =
                  $object.data("track") + 1 >= tracks.length
                     ? 0
                     : $object.data("track") + 1;
               $object.data("track", track);
               $object
                  .find(".title")
                  .empty()
                  .append(
                     tracks[track].Artist +
                        " - " +
                        tracks[track].Song +
                        "  <span>" +
                        tracks[track].ShowDate +
                        "</span>"
                  );
               var nextTrack = track + 1 >= tracks.length ? 0 : track + 1;
               $object.find(".next").empty().append(tracks[nextTrack].Song);
               $this
                  .parent()
                  .data("player")
                  .jPlayer("setMedia", {
                     mp3:
                        window.location.protocol +
                        "//mb.wolfgangsvault.com/audio/320/" +
                        tracks[track].TrackID +
                        ".mp3",
                  })
                  .jPlayer("play");
            }
         });
         $object.data(
            "player",
            $("#" + playerId).jPlayer({
               ready: function () {
                  if (isMainPlayer) {
                     if ($objects.data("audio-url")) {
                        $object
                           .find(".title")
                           .empty()
                           .append(tracks[track].Song);
                     } else {
                        $object
                           .find(".title")
                           .empty()
                           .append(
                              tracks[track].Artist + " - " + tracks[track].Song
                           );
                     }
                  } else {
                     $object
                        .find(".title")
                        .empty()
                        .append(
                           tracks[track].Artist +
                              " - " +
                              unicodeToString(tracks[track].Song) +
                              "  <span>" +
                              tracks[nextTrack].ShowDate +
                              "</span>"
                        );
                  }
                  if ($objects.data("audio-url")) {
                     $(this).jPlayer("setMedia", {
                        mp3: $objects.data("audio-url"),
                     });
                  } else {
                     $(this).jPlayer("setMedia", {
                        mp3:
                           window.location.protocol +
                           "//mb.wolfgangsvault.com/audio/320/" +
                           tracks[track].TrackID +
                           ".mp3",
                     });
                  }
               },
               play: function () {
                  // To avoid multiple jPlayers playing together.
                  $(this).jPlayer("pauseOthers");
                  if (!$object.data("adJustPlayed")) {
                     $object.data("adJustPlayed", true);
                  }
               },
               ended: function () {
                  $object.find(".next").trigger("click");
               },
               swfPath: window.templateDirURI + "/js",
               supplied: "mp3",
               preload: "metadata",
               volume: 1.0,
               cssSelectorAncestor: "#" + $object.uniqueId().get(0).id,
               cssSelector: {
                  play: ".play",
                  pause: ".pause",
                  seekBar: ".jp-seek-bar",
                  playBar: ".jp-play-bar",
                  mute: ".mute",
                  unmute: ".unmute",
                  currentTime: ".jp-current-time",
                  duration: ".jp-duration",
                  gui: ".controls",
               },
               errorAlerts: false,
               warningAlerts: false,
            })
         );
         var nextTrack = track + 1 >= tracks.length ? 0 : track + 1;
         if (isMainPlayer) {
            $object.find(".next").data("is-main-player", true);
            const nextTitle = $objects.data("next-title");
            if (nextTitle) {
               $object.find(".next").empty().append(nextTitle);
               $object.find(".next").attr("href", $objects.data("next-url"));
            } else {
               $object.find(".next").remove();
            }
         } else {
            $object.find(".next").empty().append(tracks[nextTrack].Song);
         }
      });
   }

   const $audioPlayerContainer = $("#detail-main-audio");
   if ($audioPlayerContainer.length) {
      handleAudioPlaylistPlayer($audioPlayerContainer, true);
      if (window.location.href.indexOf("trackid") > 0) {
         alert($audioPlayerContainer.data("trackid"));
         history.pushState(
            null,
            null,
            window.location.href.substring(
               0,
               window.location.href.indexOf("trackid") - 1
            )
         );
      }
   }

   handleAudioPlaylistPlayer(
      $("#article-detail-container .copy .audio-playlist-embed")
   );

   /**
    * SOCIAL SHARE
    */

   if ($(".article-shares-links").length > 0) {
      const t = $(".article-shares-links");
      t.find(".icon-facebook").on("click", function (e) {
         e.preventDefault(),
            window.open(
               "https://www.facebook.com/sharer/sharer.php?u=" +
                  encodeURIComponent(this.href),
               "fb-share",
               "status=0,toolbar=0,location=1,width=700,height=400"
            );
      }),
         t.find(".icon-twitter").on("click", function (e) {
            e.preventDefault();
            const t = $(this),
               n = $("<div>" + t.data("title") + "</div>").text();
            window.open(
               "https://twitter.com/intent/tweet?via=pastemagazine&related=pastemagazine&url=" +
                  encodeURIComponent(this.href) +
                  "&text=" +
                  encodeURI(n).replace("&", "%26"),
               "tw-share",
               "status=0,toolbar=0,location=1,width=700,height=250"
            );
         }),
         t.find(".icon-reddit-alien").on("click", function (e) {
            e.preventDefault();
            const t = $(this),
               n = $("<div>" + t.data("title") + "</div>").text();
            window.open(
               "https://www.reddit.com/submit?title=" +
                  n +
                  "&url=" +
                  encodeURIComponent(this.href),
               "reddit-share"
            );
         }),
         t.find(".icon-pinterest").on("click", function (e) {
            e.preventDefault();
            const t = $(this),
               n = t.data("image"),
               i = $("<div>" + t.data("title") + "</div>").text();
            window.open(
               "https://pinterest.com/pin/create/button/?url=" +
                  encodeURIComponent(this.href) +
                  "&media=" +
                  n +
                  "&description=" +
                  encodeURI(i).replace("&", "%26"),
               "pi-share",
               "status=0,toolbar=0,location=1,width=700,height=250"
            );
         }),
         t.find(".google-plus").on("click", function (e) {
            e.preventDefault();
            $(this);
            window.open(
               "https://plus.google.com/share?url=" +
                  encodeURIComponent(this.href),
               "google-share",
               "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600"
            );
         }),
         t.find(".linked-in").on("click", function (e) {
            e.preventDefault();
            const t = $(this),
               n = $("<div>" + t.data("title") + "</div>").text();
            window.open(
               "https://www.linkedin.com/shareArticle?mini=true&url=" +
                  encodeURIComponent(this.href) +
                  "&title=" +
                  encodeURI(n).replace("&", "%26"),
               "linkedin-share",
               "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=570,width=520"
            );
         }),
         t.find(".tumblr").on("click", function (e) {
            e.preventDefault();
            const t = $(this),
               n = $("<div>" + t.data("title") + "</div>").text(),
               i = $(
                  "<div>" +
                     $("meta[name='description']").attr("content") +
                     "</div>"
               ).text();
            window.open(
               "http://tumblr.com/widgets/share/tool?canonicalUrl=" +
                  encodeURIComponent(this.href) +
                  "&posttype=link&content=" +
                  encodeURIComponent(t.data("href")) +
                  "&title=" +
                  encodeURI(n).replace("&", "%26") +
                  "&caption=" +
                  encodeURI(i).replace("&", "%26"),
               "tumblr-share",
               "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=540"
            );
         }),
         t.find(".stumble-upon").on("click", function (e) {
            e.preventDefault(),
               window.open(
                  "http://www.stumbleupon.com/submit?url=" +
                     encodeURIComponent(this.href),
                  "stumble-share",
                  "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=800"
               );
         }),
         t.find(".icon-comment").on("click", function (e) {
            e.preventDefault();
            const t = $(this),
               n = t.data("title"),
               i = this.href,
               o = (function () {
                  const e = navigator.userAgent.toLowerCase();
                  var t = [];
                  if (/iP(hone|od|ad)/.test(navigator.platform)) {
                     const e = navigator.appVersion.match(
                        /OS (\d+)_(\d+)_?(\d+)?/
                     );
                     t = [
                        parseInt(e[1], 10),
                        parseInt(e[2], 10),
                        parseInt(e[3] || 0, 10),
                     ];
                  }
                  var n = "sms:";
                  return (
                     (n +=
                        t.length > 0 && t[0] > 7
                           ? "&"
                           : e.indexOf("iphone") > -1 || e.indexOf("ipad") > -1
                           ? ";"
                           : "?"),
                     (n += "body=")
                  );
               })(),
               r = function (e) {
                  return {
                     "&#8220;": '"',
                     "&#8221;": '"',
                     "&#160;": " ",
                     "&#8217;": "'",
                     "&#8212;": "-",
                     "&#133;": "...",
                     "&#39;": "'",
                     "&#8216;": "'",
                     "&#8230;": "...",
                     "&#233;": "E",
                     "&#8211;": "-",
                     "&#246;": "O",
                     "&#42;": "*",
                  }[e];
               },
               s =
                  o +
                  encodeURIComponent(n.replace(/\&#[0-9]+;/g, r)) +
                  "%0A%0D" +
                  encodeURIComponent(i);
            window.open(s, "_self");
         });
   }

   /* if ($("#article-detail-container .copy .dfp").length > 0) {
      // leapfrog ads if necessary
      var articleAds = [].slice.call(
            document.querySelectorAll("#article-detail-container .copy .dfp")
         ),
         debounceArticleAds = [];
      function getViewportInformation() {
         return {
            width: window.innerWidth || document.documentElement.clientWidth,
            height: window.innerHeight || document.documentElement.clientHeight,
            left:
               (typeof window.pageXOffset === "number"
                  ? window.pageXOffset
                  : document.scrollLeft) - (document.clientLeft || 0),
            top:
               (typeof window.pageYOffset === "number"
                  ? window.pageYOffset
                  : document.scrollTop) - (document.clientTop || 0),
         };
      }
      function isInViewport(bounding, viewport) {
         return (
            bounding.top >= 0 &&
            bounding.left >= 0 &&
            bounding.bottom <= viewport.height &&
            bounding.right <= viewport.width
         );
      }
      function getDistance(bounding, viewport) {
         const screen = {
            x: viewport.top + viewport.height / 2,
            y: viewport.left + viewport.width / 2,
         };
         return Math.sqrt(
            Math.pow(bounding.x - screen.x, 2) +
               Math.pow(bounding.y - screen.y, 2)
         );
      }
      function getRelativeAdScreenPositions() {
         const viewport = getViewportInformation();
         let adPositions = [];
         articleAds
            .filter((a) => !a.classList.contains("empty-unit"))
            .forEach((o) => {
               const adLocation = o.getBoundingClientRect(),
                  distance = getDistance(adLocation, viewport),
                  child = o.querySelector("div");
               if (child) {
                  adPositions.push({ distance, id: child.id });
               }
            });
         adPositions.sort((a, b) => {
            return a.distance > b.distance ? 1 : -1;
         });
         return adPositions;
      }
      function leapfrogAds() {
         const config = {
            // If the image gets within 50px in the Y axis, start the download.
            rootMargin:
               Math.max(
                  document.documentElement.clientHeight,
                  window.innerHeight || 500
               ) + "px 0px",
            threshold: 0.01,
         };
         var observer = new IntersectionObserver(function (entries) {
            var list = getRelativeAdScreenPositions();
            if (list && list.length) {
               var emptyEntries = [];
               for (var i = 0; i < entries.length; i++) {
                  if (entries[i].target.classList.contains("empty-unit")) {
                     emptyEntries.push(entries[i]);
                  }
               }
               emptyEntries.forEach(function (entry) {
                  if (entry.intersectionRatio > 0) {
                     const id = list[list.length - 1].id;
                     if (!debounceArticleAds.includes(id)) {
                        const furthestFilled = document.getElementById(id);
                        if (furthestFilled) {
                           debounceArticleAds.push(id);
                           setTimeout(function () {
                              debounceArticleAds = debounceArticleAds.filter(
                                 (v) => v !== id
                              );
                           }, 500);
                           const cssClass =
                              furthestFilled.parentNode.getAttribute("class");
                           furthestFilled.parentNode.setAttribute(
                              "class",
                              "dfp empty empty-unit"
                           );
                           pm.ads.destroy(id);
                           furthestFilled.remove();
                           //const newNode = furthestFilled.cloneNode();
                           const newNode = document.createElement("div");
                           newNode.id = id;
                           entry.target.appendChild(newNode);
                           entry.target.setAttribute("class", "dfp empty");
                           pm.ads.manualRefresh(id);
                        }
                     }
                  }
               });
            }
         }, config);
         articleAds.forEach(function (ad) {
            observer.observe(ad);
         });
      }
      if (articleAds.length > 9) {
         leapfrogAds();
      }
      var debounceJumpLinkChange = false;
      var storeJumplinkInHistory = false;
      function jumplinkUrlChange(jumplinks) {
         const observer = new IntersectionObserver(function (entries) {
            setTimeout(function () {
               if (!debounceJumpLinkChange && storeJumplinkInHistory) {
                  entries.forEach(function (entry) {
                     if (entry.isIntersecting) {
                        const id = entry.target.id,
                           urlWithoutJumplink = window.location.href
                              .substring(
                                 window.location.href.indexOf(
                                    window.location.hostname
                                 ) + window.location.hostname.length
                              )
                              .replace(/#.*$/, "");
                        window.history.pushState(
                           null,
                           document.title,
                           urlWithoutJumplink + "#" + id
                        );
                     }
                  });
               }
            }, 100);
         });
         jumplinks.forEach(function (a) {
            observer.observe(a);
         });
      }
      const $jumplinks = document.querySelectorAll(
         "#article-detail-container .copy .jumplink"
      );
      function checkJumplink(e) {
         const viewport = getViewportInformation();
         storeJumplinkInHistory = viewport.top > 100;
         if (!storeJumplinkInHistory && window.location.hash.length > 0) {
            const urlWithoutJumplink = window.location.href
               .substring(
                  window.location.href.indexOf(window.location.hostname) +
                     window.location.hostname.length
               )
               .replace(/#.*$/, "");
            window.history.pushState(null, document.title, urlWithoutJumplink);
         }
      }
      if ($jumplinks.length) {
         checkJumplink();
         jQuery(window).on(
            "touchstart.jumplink scroll.jumplink",
            checkJumplink
         );
         jumplinkUrlChange($jumplinks);
         window.addEventListener("popstate", (event) => {
            debounceJumpLinkChange = true;
            setTimeout(function () {
               debounceJumpLinkChange = false;
            }, 500);
            if (window.location.hash && window.location.hash.length > 0) {
               setTimeout(function () {
                  const viewport = getViewportInformation(),
                     objectTop = jQuery(window.location.hash).offset().top,
                     headerHeight = jQuery("#master-header").outerHeight();
                  window.scrollTo(viewport.x, objectTop - headerHeight);
               }, 100);
            } else {
               window.scrollTo(0, 0);
            }
         });
      }
   } */
});

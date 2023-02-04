jQuery(function ($) {
   window.initDaytrotterDetail = function () {
      /* detail page */

      var $container = $("#article-detail-container.noisetrade"),
         $tracks = $container.find(".tracks li").on("click", function (e) {
            //alert("here");
            e.preventDefault();
            const $this = $(this),
               $target = $(e.target),
               isPlaying = $this.hasClass("playing"),
               trackId = $this.data("trackid");
            if (!isPlaying) {
               $(".tracks li.playing .icon-pause").trigger("click"); // pause other tracks if, for example, there are more than one player/queue on a single page
            }
            if ($target.hasClass("button")) {
               queue.addTrack(trackId, true);
               if ($container.hasClass("noisetrade")) {
                  if (!$this.data("noisetrade").played) {
                     var temp = $this.data("noisetrade");
                     temp.played = true;
                     $this.data("noisetrade", temp);
                  }
               }
            } else if (
               $target.hasClass("scrub-container") ||
               $target.hasClass("scrub")
            ) {
               var $scrubContainer = $target.hasClass("scrub")
                     ? $target.parent()
                     : $target,
                  scrubContainerWidth = $scrubContainer.width(),
                  percentage = (e.offsetX / scrubContainerWidth) * 100;
               $container
                  .find(".session-player")
                  .jPlayer("playHead", percentage);
            }
         }),
         $player = $container.find(".session-player"),
         $track = null,
         queue = {
            loaded: false,
            playing: false,
            play: function ($newTrack, $oldTrack, start) {
               if ($oldTrack) {
                  $oldTrack.removeClass("playing").removeClass("paused");
               }
               $track = $newTrack;
               if (queue.loaded) {
                  if (queue.playing) {
                     $player.jPlayer("pause");
                  }
                  $player.jPlayer("setMedia", {
                     mp3: $track.data("track").streamingUrl,
                  });
                  if (start) {
                     $player.jPlayer("play");
                  }
               }
            },
            addTrack: function (trackId, start) {
               var fetchTrackInfo = false;
               if (!$track) {
                  fetchTrackInfo = true;
               } else if ($track.data("trackid") != trackId) {
                  fetchTrackInfo = true;
               }
               if (fetchTrackInfo) {
                  const $nextTrack = $tracks.filter(
                     "[data-trackid='" + trackId + "']"
                  );
                  if ($nextTrack.data("track")) {
                     queue.play($nextTrack, $track, start);
                  } else {
                     /* $.ajax({
                        url: pm.page.services + "GetDaytrotterTrack",
                        method: "GET",
                        dataType: "json",
                        data: { trackId: trackId },
                        success: function (data) {
                           const $newTrack = $tracks.filter(
                              "[data-trackid='" + data.trackId + "']"
                           );
                           $newTrack.data("track", data);
                           queue.play($newTrack, $track, start);
                        },
                     }); */
                  }
               } else if (queue.loaded) {
                  if (queue.playing) {
                     $player.jPlayer("pause");
                  } else if (typeof start === "boolean" && start) {
                     // don't auto-start anymore on noisetrade
                     $player.jPlayer("play");
                  }
               }
            },
            loadMetaData: function (e) {
               var duration = e.jPlayer.status.duration,
                  trackInfo = $track.data("track");
               if (duration != trackInfo.duration) {
                  trackInfo.duration = duration;
                  $track.data("track", trackInfo);
               }
            },
            timeUpdate: function (e) {
               var time = e.jPlayer.status.currentTime,
                  trackInfo = $track.data("track"),
                  percentage =
                     Math.round((time / trackInfo.duration) * 10000) / 100;
               if (percentage > 100) {
                  percentage = 100;
               } else if (percentage < 0) {
                  percentage = 0;
               }
               $track.find(".scrub").css({ width: percentage + "%" });
               if (pm.noisetrade && pm.noisetrade.playerTimeUpdateWatch) {
                  pm.noisetrade.playerTimeUpdateWatch(time, $player);
               }
            },
            jPlayerOptions: {
               ready: function (e) {
                  queue.loaded = true;
                  if ($track && !queue.playing) {
                     $player.jPlayer("setMedia", {
                        mp3: $track.data("track").streamingUrl,
                     }); // noisetrade
                  }
               },
               ended: function (e) {
                  var $playingTrack = $track,
                     nextTrackIndex = $playingTrack.index() + 1;
                  if (nextTrackIndex < $tracks.length) {
                     queue.addTrack(
                        $tracks.eq(nextTrackIndex).data("trackid"),
                        true
                     );
                  } else {
                     $track.removeClass("playing").removeClass("paused");
                  }
               },
               pause: function (e) {
                  queue.playing = false;
                  $track.addClass("paused");
               },
               play: function (e) {
                  if (!$track.hasClass("playing")) {
                     $track.addClass("playing");
                  }
                  $track.removeClass("paused");
                  queue.playing = true;
                  if (typeof refreshBids === "function") refreshBids();
               },
               swfPath: window.templateDirURI + "/js",
               solution: "html, flash",
               supplied: "mp3",
               preload: "metadata",
               volume: 1.0,
               muted: false,
               wmode: "window",
               errorAlerts: false,
               warningAlerts: false,
            },
         };
      $player
         .jPlayer(queue.jPlayerOptions)
         .on($.jPlayer.event.error, function (e) {
            switch (e.jPlayer.error.type) {
               case $.jPlayer.error.URL:
                  /*pm.radio.showError("We are having trouble streaming Paste Radio to you at this time.  This is probably due to problems with your Internet connection.  As soon as this problem is resolved we should begin streaming again.", true);*/
                  break;
               default:
                  console.error(e.jPlayer.error.message);
            }
         })
         .on($.jPlayer.event.timeupdate, queue.timeUpdate)
         .on($.jPlayer.event.loadedmetadata, queue.loadMetaData);
      if (/\?tid=([0-9-]+)/.test(window.location.href)) {
         const trackId = window.location.href.match(/\?tid=([0-9-]+)/)[1];
         if ($tracks.filter("[data-trackid=" + trackId + "]").length > 0) {
            queue.addTrack(trackId, true);
         } else {
            queue.addTrack($tracks.eq(0).data("trackid"), true);
         }
      } else {
         queue.addTrack(
            $tracks.eq(0).data("trackid"),
            !$container.hasClass("noisetrade")
         );
      }
   };
   window.initDaytrotterDetail();
});

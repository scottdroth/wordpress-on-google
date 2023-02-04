{
    "use strict";
    window.pm = window.pm || {};
    var PWT = {};
    PWT.jsLoaded = function () {
        //Submitting a round of bidding.  The first argument we pass is the array of ad slots for bidding, the second is the callback function that will run when the round of bidding is complete
        //console.log("PWT loadingXXX");       
        window.pm.ads.loadPwt('all_units');
	     
    };
    var gptLoaded = false;
    var initAdserverSet = false;
    var pwtBids = [];
    var allUnits = [];
	
    window.pm.ads = {
        "referencesLoaded": 0,
        "unitsReady": false,
        "logging": /enableadlogging=true/.test(window.location.href),
        "initializeUnit": function(code) {
            allUnits[allUnits.length] = code;
            this.units.states[code] = {
                "enabled": true,
                "timeout": null,
                "drawing": false,
                "viewable": false,
                "empty": true,
                "shown": 0,
                "attempted": 0,
                "lastShown": null,
                "bids": {
                    "timeout": null,
                    "a9": false,
                    "pwt": false,
                    "fetching": false
                },
                "retries": {
                    "timer": {
                        "long": null,
                        "short": null
                    },
                    "background": 0,
                    "viewable": 0
                }
            }
        },
        "documentVisible": !document.hidden,
        "lastInteraction": new Date(),
        "lastInteractionThreshold": 60 * 1000,
        "settings": {
            "retries": {
                "viewability": {
                    "wait": 5000,
                    "limit": 3
                },
                "background": {
                    "limit": 2
                }
            }
        },
        "disable": function(units) {
            var dis = this;
            if (typeof units === "object") {
                units.forEach(function(unit) {
                    dis.units.states[unit].enabled = false;
                });
            } else {
                this.units.pwt.forEach(function(unit) {
                    dis.units.states[unit.code].enabled = false;
                }); 
            }
        },
        "enable": function(units) {
            var dis = this;
            if (typeof units === "object") {
                units.forEach(function(unit) {
                    dis.units.states[unit].enabled = true;
                    dis.dispatchEvent('adrendered', unit);
                });
            } else {
                this.units.pwt.forEach(function(unit) {
                    dis.units.states[unit.code].enabled = true;
                }); 
            }
        },
        "unitElegableForDraw": function(code, nobids) {
            var state = this.units.states[code];
            if (!state.enabled) {
                return false;
            } else if (nobids) {
                return !state.drawing && this.documentVisible;
            } else {
                return state.bids.a9 && !state.drawing && !state.bids.fetching && (this.documentVisible || state.retries.background < this.settings.retries.background.limit);
            }
        },
        "units": { "pwt": [], "a9": [], "dfp": [], "states": {} },
        "getLayout": function () {
            return window.matchMedia("screen and (max-width: 39.9375em)").matches ? "small" : window.matchMedia("screen and (min-width: 40em) and (max-width: 63.9375em)").matches ? "medium" : "large";
        },
        "isMobile": function () {
            return /(iPhone|iPad|iPod|Android|SymbianOS|RIM|BlackBerry|Palm|Windows\s+CE)/.test(navigator.userAgent);
        },
        "dispatchEvent": function(event, data) {
            if (typeof document.dispatchEvent === "function" && typeof CustomEvent === "function") {
                document.dispatchEvent(new CustomEvent(event, { "detail": data }));
            }
        },
        "destroy": function(code) {
            var dis = this;
            this.units.dfp.forEach(function(slot) {
                if (slot.getSlotId().getDomId() === code) {
                    googletag.destroySlots(slot);
                    dis.units.states[code].viewable = false;
                }
            });
        },
        "manualRefresh": function (code) {
            if (window.pm.ads.logging) {
                console.log("adsdebug moving", code, (new Date()).getTime() - window.pageloadTimer.getTime(), pm.ads.units.states[code]);
            }
            if (this.units.states[code].timeout) { // clear the timout if one exists and we've already started drawing
                clearTimeout(this.units.states[code].timeout);
                this.units.states[code].timeout = null;
            }
            this.prime(code);
            this.load(code, true, true);
        },
        "timeout": 1500,
        "loadPwt": function(adUnitCode, force, fromViewableEvent) {
            if (window.pm.ads.logging) {
                console.log("loadPwt");
                console.log("loadPwt adUnitCode ",adUnitCode);
            }
            if (!adUnitCode) console.warn('calling loadPwt with no adUnitCode defined');
            var dis = this;
            if (typeof PWT.requestBids === 'function') {
                if (window.pm.ads.logging) {
                    console.log("PWT.requestBids A");
                }
                var code = adUnitCode;
                if (this.logging) {
                    console.log("adsdebug fetching", code, (new Date()).getTime() - window.pageloadTimer.getTime(), "pwt");
                }
                var start_time = new Date().getTime();
                var units = dis.units.pwt;
                if (window.pm.ads.logging) {
                    console.log("units A ",units);
                }
                if (adUnitCode !== 'all_units') {
                    units = [];
                    for(var i=0;i<dis.units.pwt.length;i++) {
                        var unit = dis.units.pwt[i];
                        if (unit.code === adUnitCode) {
                            units.push(unit);
                        }
                    }
                }
                for(var i=0;i<units.length;i++) {
                    var unit = units[i];
                    dis.units.states[unit.code].bids.pwt = false;
                }
                if (typeof PWT.requestBids === 'function') {
                    if (window.pm.ads.logging) {
                        console.log("PWT.requestBids B");
                        console.log("units B ",units);
                    }
                    PWT.requestBids(
                        units,
                        function (adUnitsArray) {
                            var end_time = new Date().getTime();
                            var diff = end_time - start_time;
                            //console.log("OpenWrap bidding just finished. Time taken: " + diff.toString());
                            pwtBids.push(adUnitsArray);
                            for(var i=0;i<units.length;i++) {
                                var unit = units[i];
                                dis.units.states[unit.code].bids.pwt = true;
                            }
                            var eventObject = { "adUnitCode": adUnitCode, "fromViewableEvent": fromViewableEvent };
                            dis.dispatchEvent('bidsrecieved', eventObject);
                        }
                    );
                } else {
                    pwtFinished = true;
                }
            } else {
                pwtFinished = true;
                if (window.pm.ads.logging) {
                    console.log("pwtFinished");
                }
                initAdserver();
            }
        },
        "loadA9": function(adUnitCode, force, fromViewableEvent) {
            if (window.pm.ads.logging) {
                console.log("loadA9");
            }
            var dis = this;
            if (!adUnitCode) console.warn('calling loadA9 with no adUnitCode defined');
            if (typeof apstag !== "undefined" && adUnitCode) {
                var code = adUnitCode;
                if (this.logging) {
                    console.log("adsdebug fetching", code, (new Date()).getTime() - window.pageloadTimer.getTime(), "A9");
                }
                var units = dis.units.a9;
                if (adUnitCode !== 'all_units') {
                    units = [];
                    for(var i=0;i<dis.units.a9.length;i++) {
                        var unit = dis.units.a9[i];
                        if (unit.slotID === adUnitCode) {
                            units.push(unit);
                        }
                    }
                }
                var a9requestBids = function(units) {
                    apstag.fetchBids({
                        "slots": units,
                        "timeout": dis.timeout
                    }, function(bids) {
                        apstag.setDisplayBids();
                        for(var i=0;i<units.length;i++) {
                            var unit = units[i];
                            dis.units.states[unit.slotID].bids.a9 = true;
                        }
                        var eventObject = { "adUnitCode": adUnitCode, "fromViewableEvent": fromViewableEvent};
                        dis.dispatchEvent('bidsrecieved', eventObject);
                    });
                };
                for(var i=0;i<units.length;i++) {
                    var unit = units[i];
                    dis.units.states[unit.slotID].bids.a9 = false;
                }
                a9requestBids(units);
            }
        },
        "load": function(adUnitCode, force, fromViewableEvent) {
            var dis = this;
            var code = adUnitCode;
            if (!adUnitCode) console.warn('calling load with no adUnitCode defined');
            if (fromViewableEvent === true) {
                this.units.states[code].viewable = true;
            }
            if (!this.units.states[code].bids.fetching || force) {
                if (force || (code !== "all_units" && (this.documentVisible || this.units.states[code].retries.background < this.settings.retries.background.limit))) {
                    this.units.states[code].bids.fetching = true;
                    this.loadA9(adUnitCode, force, fromViewableEvent);
					
                    this.loadPwt(adUnitCode, force, fromViewableEvent);
                    if (this.units.states[code].timeout) {
                        window.clearTimeout(this.units.states[code].timeout);   
                        this.units.states[adUnitCode].timeout = null;
                    }
                    var eventObject = { "adUnitCode": adUnitCode, "fromViewableEvent": fromViewableEvent};
                    this.units.states[code].timeout = window.setTimeout(function() { dis.dispatchEvent("timeout", eventObject); }, this.timeout);
                }
            }
        },
        "prime": function(code) {
            var dis = this;
            if (!('IntersectionObserver' in window)) {
                //this.load(code); we don't need this because we have a time-based refresh that will cover old browsers
            } else {
                // It is supported, load the images
                var threshold = 0.5,
				    config = {
				        rootMargin: Math.floor(window.innerHeight * 2 / 3) + 'px 0px',
				        threshold: [0, threshold]
				    },
				    unit = document.getElementById(code);
                var observer = new IntersectionObserver(function(entries) {
                    // Loop through the entries
                    entries.forEach(function(entry) {
                        // Are we in viewport?
                        if (entry.isIntersecting) {
                          
                            if (entry.intersectionRatio > threshold) {
                                dis.units.states[code].viewable = true;
                                dis.dispatchEvent("viewable", code);
                            }
                        } else {
                            dis.units.states[code].viewable = false;
                            /*if (this.units.states[code].enabled) {
							    dis.dispatchEvent("viewable", code);
						    }*/
                        }
                    });
                }, config);
                observer.observe(unit);
            }
            if (code === 'bottom_leaderboard_rectangle' || code === 'bottom_leaderboard') {
                this.unitsReady = true;
                this.dispatchEvent('library', 'unitsready');
            }
        },
        "translateUnits": function(type, units) {
            var response = [];
            switch (type) {
                case 'pwt':
                    for(var i=0;i<units.length;i++) {
                        var unit = units[i];
                        response.push({
                            code: unit.code,
                            divId: unit.code,
                            adUnitId: '/1225956/paste-viewable/' + unit.code,
                            adUnitIndex: "0",
                            mediaTypes: unit.mediaTypes
                        });
                    }
                    break;
                case 'a9':
                    for(var i=0;i<units.length;i++) {
                        var unit = units[i];
                        response.push({
                            slotID: unit.code,
                            slotName: '/1225956/paste-viewable/' + unit.code,
                            sizes: unit.mediaTypes.banner.sizes.length === 2 ? this.getLayout() === 'small' ? unit.mediaTypes.banner.sizes[1] : unit.mediaTypes.banner.sizes[0] : unit.mediaTypes.banner.sizes
                        });
                    }
                    break;
            }
            return response;
        },
        "initialize": function(units, timeoutInMs, refreshInMs, interactionThresholdInMs) {
            var dis = this;
            this.timeout = timeoutInMs;
            if (interactionThresholdInMs) { this.lastInteractionThreshold = interactionThresholdInMs; }
            var desktopSizes = [[728,90],[300,600],[300,250],];
            var tabletSizes = [[728,90],[300,250]];
            var phoneSizes = [[320,50],[320,100],[300,250]];
            var allSizes = [[320,50],[320,100],[300,250],[728,90],[300,600]];
            // Start special cases
            if ((pm.page.type === "Search" || pm.page.type === "Tag") && this.getLayout() !== "large") {
                var indexesToRemove = [];
                for(var i=0;i<units;i++) {
                    var code = units.code;
                    if (/(top|middle|bottom)_rectangle/.test(code)) {
                        indexesToRemove.push(i);
                    }
                };
                indexesToRemove.reverse().forEach(function(index) {
                    units.splice(index,1);
                });
            }
            // End special cases
            for(var i=0;i<units.length;i++) {
                var code = units[i].code;
                this.initializeUnit(code); // set states object for this unit
            }
            this.units.pwt = this.translateUnits('pwt', units);
            this.units.a9 = this.translateUnits('a9', units);
            var scriptCallback = function(src) {
            
                dis.referencesLoaded++;
                if (dis.logging) {
                    console.log("adsdebug", src, (new Date()).getTime() - window.pageloadTimer.getTime(), "references", dis.referencesLoaded); // src is "loaded [resourcename]"
                }
                if (src.indexOf("/gpt.js") > 0) {
                    function getScreenLocation(code) {
                        switch(code){
                            case 'top_leaderboard':
                                return 'top';
                                break;
                            case "mid_leaderboard_rectangle_1":
                                return 'article_top';
                                break;
                            case 'top_rectangle' || "right_halfpage":
                                return 'top_right';
                                break;
                            case "middle_rectangle":
                                return 'center_right';
                                break;
                            case "bottom_rectangle":
                                return 'bottom_right';
                                break;
                            case "bottom_leaderboard" || "bottom_leaderboard_rectangle":
                                return 'bottom';
                                break;
                            default:
                                return 'center';
                        }
                    }
                    function getSizeMapping(code) {
                        switch(code){
                            case "top_leaderboard":
                                return googletag.sizeMapping().addSize([0,0],[320,50]).addSize([750,200],[728,90]).build();
                                break;
                            case "bottom_leaderboard":
                            case "bottom_leaderboard_rectangle":
                                return googletag.sizeMapping().addSize([0,0],[[320,50],[320,100]]).addSize([750,200],[728,90]).build();
                                break;
                            case "right_halfpage":
                                return googletag.sizeMapping().addSize([0,0],[300,600]).build();
                                break;
                            case "top_rectangle":
                            case "bottom_rectangle":
                                return googletag.sizeMapping().addSize([0,0],[300,250]).addSize([1000,200],[[300,600]]).build();
                                break;
                            default: // now includes middle_rectangle, per Bill/josh
                                return googletag.sizeMapping().addSize([0,0],[300,250]).build();
                        }
                    }
                    googletag.cmd.push(function() {
                        for(var i=0;i<units.length;i++) {
                            var unit = units[i];
                            dis.units.dfp.push(googletag.defineSlot("/1225956/paste-viewable/" + unit.code, unit.mediaTypes.banner.sizes, unit.code).setTargeting('screen_location',getScreenLocation(unit.code)).defineSizeMapping(getSizeMapping(unit.code)).addService(googletag.pubads()));
                        };
                        googletag.pubads().setTargeting("url", window.location.pathname);
                        googletag.pubads().setTargeting("mediatype", pm.page.mediaType);
                        googletag.pubads().setTargeting("articletype", pm.page.articleType);
                        googletag.pubads().setTargeting("aid", "a" + pm.page.articleId);
                        googletag.pubads().setTargeting("development", (pm.servers.pastemagazine.indexOf("www") === -1).toString().toLowerCase());
                        googletag.pubads().setTargeting("hostname", pm.servers.pastemagazine);
                        googletag.pubads().setTargeting("in_app", /FBAV/.test(window.navigator.userAgent).toString());
                        googletag.pubads().setTargeting("ads_in_page", "a" + dis.units.dfp.length);
                        googletag.pubads().setTargeting("viewability", "3.0");
                        googletag.pubads().enableSingleRequest();
                        googletag.pubads().disableInitialLoad();
                        googletag.pubads().addEventListener('slotRenderEnded', function(e) {
                 
                            var code = e.slot.getSlotElementId();
                            dis.units.states[code].empty = e.isEmpty;
                            if (e.isEmpty) {
                                var adContainer = document.getElementById(code).parentNode;
                                if (adContainer.parentNode.classList.contains("copy")) { // only hide containers if they are in the body of an article
                                    adContainer.classList.add("empty");
                                } else if (code === 'bottom_leaderboard' || code === 'bottom_leaderboard_rectangle') {
                                    document.getElementById(code).classList.remove("show");
                                }
                            } else {
                                if ((code === 'bottom_leaderboard' || code === 'bottom_leaderboard_rectangle') && !document.getElementById(code).classList.contains("show")) {
                                    document.getElementById(code).classList.add("show");
                                }
                                if (code !== "bottom_rectangle") {
                                    document.getElementById(code).removeAttribute("style");
                                }
                            }
                            // Remove last PubWrap key-value to prime for next refresh
                            for(var i=0;i<dis.units.dfp.length;i++) {
                                if (dis.units.dfp[i].getSlotElementId() === code) {
                                    PWT.removeKeyValuePairsFromGPTSlots(dis.units.dfp[i]);
                                }
                            }
                            dis.dispatchEvent('adrendered', code);
                            e.slot.setTargeting("AdRefresh", "true"); // with this we're assuming that we never loat all_units except at page load
                        });
                        googletag.enableServices();
                        for(var i=0;i<units.length;i++) {
                            var unit = units[i];
                            googletag.display(unit.code);
                        }
                    });
                }
                if (src.indexOf("/pwt.js") > 0) {
                    // any pubmatic wrapper config that waits for instantiation is in PWT.jsLoaded
                }
                if (src.indexOf("/apstag.js") > 0) {
                    //Initialize the Library
                    apstag.init({
                        "pubID": '9d883dc1-db2e-4fb8-960f-4ea07f2b85d8',
                        "adServer": "googletag"
                    });
                    dis.loadA9('all_units');
                }
                if (window.pm.ads.logging) {
                    console.log("dis.referencesLoaded ",dis.referencesLoaded);
                }

                // AAX Ad Blocker workaround
                window.aax = window.aax || {};
                window.aax.cmd = window.aax.cmd || [];
                window.aax.cmd.push(function() {
                    if (window.aax.getAbpStatus()) {
                        window.googletag = window.googletag || {};
                        window.googletag.cmd = window.googletag.cmd || [];
                        window.googletag.cmd.push(function() {
                            googletag.pubads().refresh();
                        });
                    }
                });
                if (window.pm.ads.logging) {
                    console.log("AAX Loaded");
                }
                if (dis.referencesLoaded > 2) { // attach event listeners when all ad resoruces are loaded
                    var initalDFPCheckTimeout = null;
                    pm.page.adblocker = false;
                    gtmEventSend();
                    for (var code in dis.units.states) {
                        if (dis.units.states.hasOwnProperty(code) && code !== 'all_units') {
                            dis.units.states[code].bids.fetching = true;
                        }
                    }
                    var drawAds = function(code, fromViewableEvent, isAllUnits = false) {
                        if (window.pm.ads.logging) {
                            console.log("drawAds ",code);
                        }
                        if (typeof googletag.pubads === "function" && !dis.units.states[code].drawing) { // to prevent calling this too early
                            // console.log("drawAds A");
                            dis.units.states[code].drawing = true;
                            if (dis.units.states[code].timeout) { // clear the timout if one exists and we've already started drawing
                                clearTimeout(dis.units.states[code].timeout);
                                dis.units.states[code].timeout = null;
                            }
                            if (!fromViewableEvent && code !== 'all_units') {
                                dis.units.states[code].retries.viewable = 0;
                                if (!dis.documentVisible) {
                                    dis.units.states[code].retries.background++;
                                } else {
                                    dis.units.states[code].retries.background = 0;
                                }
                            }
                            var au;
                            if (window.pm.ads.logging) {
                                console.log("pwtBids.shift()");
                            }
                            while (au = pwtBids.shift()) {
                                if (window.pm.ads.logging) {
                                    console.log("pwtBids.shift() loop");
                                    console.log(au);
                                }
                                PWT.addKeyValuePairsToGPTSlots(au);
                            }
                            var slots = [];
                            dis.units.dfp.forEach(function(slot) {
                                if (slot.getSlotId().getDomId() === code) {
                                    slots.push(slot);
                                    slot.setTargeting("AdRefresh", "" + !isAllUnits);
                                    slot.setTargeting("document_hidden", (!dis.documentVisible).toString()); // set key-values per unit if we're only refreshing certain units
                                    slot.setTargeting("retry", "" + dis.units.states[code].retries.viewable); // So we can tell server-side the retry we're on
                                }
                            });
                            googletag.cmd.push(function() { 
                                if (dis.logging) {
                                    console.log("adsdebug drawing", code, (new Date()).getTime() - window.pageloadTimer.getTime(), "attempts", dis.units.states[code].attempted, "shown", dis.units.states[code].shown);
                                }
                                googletag.pubads().refresh(slots);
                            });
                        }
                    }
                        document.addEventListener("bidsrecieved", function(e) {
                            //console.log("bidsrecieved");
                            if (e.detail) {
                                function emit(id, fromViewableEvent, isAllUnits = false) {
                                    var state = dis.units.states[id];
                                    if (state.bids.a9 && state.bids.pwt &&  !state.drawing) {
                                        dis.units.states[id].bids.fetching = false;
                                        if (dis.unitElegableForDraw(id)) {
                                            drawAds(id, fromViewableEvent, isAllUnits);
                                        }
                                    }
                                }
                                    var code = e.detail.adUnitCode,
                                                   fromViewableEvent = e.detail.fromViewableEvent;
                                    if (code === 'all_units') {
                                        allUnits.forEach(function(myCode) {

                                            emit(myCode, fromViewableEvent,true);
                                        });
                                        //emit(dis.units.states[i].code, fromViewableEvent);
				 
                                        for(var i=0;i<dis.units.pwt.length;i++) {
                                            var unit = dis.units.pwt[i];
                                            var id = unit.code;
                                            dis.units.states[id].viewable = true; // on the first page-load draw all untis even if they're not viewable
                                            emit(id, fromViewableEvent, true);
                                        } 
                                    } else {
                                        if (fromViewableEvent === true) {
                                            dis.units.states[code].viewable = true;
                                        }
                                        emit(code, fromViewableEvent);
                                    }
                            
                                }
                            });
                            document.addEventListener("timeout", function(e) {
                                if (e.detail) {
                                    var code = e.detail.adUnitCode,
                                        fromViewableEvent = e.detail.fromViewableEvent;
                                    if (dis.units.states[code]) {
                                        dis.units.states[code].bids.fetching = false;
                                        if (dis.unitElegableForDraw(code, true)) {
                                            drawAds(code, fromViewableEvent);
                                        }
                                    }
                                }
                            });
                            document.addEventListener("viewable", function(e) {
                                if (e.detail) {
                                    var state = dis.units.states[e.detail],
                                        code = e.detail,
                                        waitingover = state.retries.timer.long === null;
                                    // first, if this is empty but now viewable make another request per Bill - requiring one attempted should prevent extra viewable refreshes right at page load
                                    if ((state.empty || waitingover) && state.viewable && (!state.bids.a9 && !state.bids.fetching && !state.drawing) && state.retries.viewable < dis.settings.retries.viewability.limit) {
                                        dis.units.states[code].retries.viewable++;
                                        dis.units.states[code].retries.timer.short = window.setTimeout(function() { dis.dispatchEvent('viewable', code); }, dis.settings.retries.viewability.wait);
                                        dis.load(e.detail, false, true);
                                    }
                                }
                            });
                            document.addEventListener("waitingover", function(e) {
                                if (e.detail) {
                                    if (dis.units.states[e.detail].retries.timer.long) {
                                        clearTimeout(dis.units.states[e.detail].retries.timer.long);
                                        dis.units.states[e.detail].retries.timer.long = null;
                                    }
                                    dis.units.states[e.detail].retries.viewable = 0;
                                    if (dis.unitElegableForDraw(e.detail, true)) {
                                        dis.load(e.detail);
                                    }
                                }
                            });
                            document.addEventListener("adrendered", function(e) {
                                // console.log("adrendered");
                                if (e.detail) {
							
                                    var code = e.detail;
                                    dis.units.states[code].attempted++;
                                    if (code) {
                                        if (!dis.units.states[code].empty) {
                                            dis.units.states[code].shown++;
                                            dis.units.states[code].lastShown = new Date();
                                            dis.units.states[code].retries.viewable = 0;
                                            if (dis.units.states[code].retries.timer.long) {
                                                window.clearTimeout(dis.units.states[code].retries.timer.long);   
                                                dis.units.states[code].retries.timer.long = null;
                                            }
                                        }
                                        if (dis.units.states[code].retries.timer.short) {
                                            window.clearTimeout(dis.units.states[code].retries.timer.short);   
                                            dis.units.states[code].retries.timer.short = null;
                                        }
                                        dis.units.states[code].bids.a9 = false;
                                        dis.units.states[code].bids.pwt = false;
                                        dis.units.states[code].bids.fetching = false;
                                        dis.units.states[code].drawing = false;
                                        // set the longer timer - viewable shouldn't mess with this
                                        if (!dis.units.states[code].retries.timer.long) {
                                            dis.units.states[code].retries.timer.long = window.setTimeout(function() { dis.dispatchEvent("waitingover", e.detail); }, refreshInMs);
                                        }
                                        if (dis.units.states[code].empty && dis.units.states[code].viewable) {
                                            dis.units.states[code].retries.timer.short = window.setTimeout(function() { dis.dispatchEvent('viewable', code); }, dis.settings.retries.viewability.wait);
                                        }
                                    }
                                    if (dis.logging) {
                                        document.getElementById(code).setAttribute('data-empty', dis.units.states[code].empty);
                                        document.getElementById(code).setAttribute('data-retries-viewable', dis.units.states[code].retries.viewable);
                                        document.getElementById(code).setAttribute('data-retries-background', dis.units.states[code].retries.background);
                                    }
                                }
                            });
                            var checkAllUnits = function() {
                                for (var code in dis.units.states) {
                                    if (dis.units.states.hasOwnProperty(code) && code !== 'all_units') {
                                        var state = dis.units.states[code];
                                        if (dis.unitElegableForDraw(code)) {
                                            drawAds(code);
                                        } else if (!state.fetchingBids && state.viewable && dis.documentVisible) {
                                            dis.load(code);
                                        }
                                    }
                                }
                            }
                            var hidden, state, visibilityChange = null;
                            if (typeof document.hidden !== "undefined") {
                                hidden = "hidden";
                                visibilityChange = "visibilitychange";
                                state = "visibilityState";
                            } else if (typeof document.mozHidden !== "undefined") {
                                hidden = "mozHidden";
                                visibilityChange = "mozvisibilitychange";
                                state = "mozVisibilityState";
                            } else if (typeof document.msHidden !== "undefined") {
                                hidden = "msHidden";
                                visibilityChange = "msvisibilitychange";
                                state = "msVisibilityState";
                            } else if (typeof document.webkitHidden !== "undefined") {
                                hidden = "webkitHidden";
                                visibilityChange = "webkitvisibilitychange";
                                state = "webkitVisibilityState";
                            }
                            if (visibilityChange) {
                                document.addEventListener(visibilityChange, function() {
                                    if (document[hidden]) {
                                        dis.documentVisible = false;
                                    } else {
                                        dis.documentVisible = true;
                                        checkAllUnits();
                                    }
                                    dis.lastInteraction = new Date();
                                }, false);
                            }
                            document.addEventListener("library", function(e) {
                                if (e.detail) {
                                    if (dis.unitsReady && dis.referencesLoaded > 1) { // everything is ready, so request all units
                                        // if we're going to bundle initial page requests for senigleRequestMode, maybe do it here?
                                        // but also it may be better to generalize it so iw works on any ad refresh
                                    }
                                }
                            });
                            dis.dispatchEvent('library', 'resourcesready');
                        }
                        }
                    var erroredReferences = 0;
                    function loadAndWatchScript(src, callbackname) {
                        if (window.pm.ads.logging) {
                            console.log("loadAndWatchScript", callbackname);
                        }
                        var script = document.createElement('script'),
                            ref = document.getElementsByTagName('script')[0];
                        script.src = src;
                        script.setAttribute("async", "async");
                        script.onload = function() { scriptCallback("loaded " + callbackname); }
                        script.onerror = function() { erroredReferences++; gtmEventSend(); }
                        ref.parentNode.insertBefore(script, ref);
                    }
                    loadAndWatchScript('https://c.amazon-adsystem.com/aax2/apstag.js', '/apstag.js');
                    var purl = window.location.href;
                    var url = 'https://ads.pubmatic.com/AdServer/js/pwt/160494/' + (this.getLayout() !== 'large' ? 3743 : 3742);
                    var profileVersionId = '';
                    if (purl.indexOf('pwtv=') > 0) {
                        var regexp = /pwtv=(.*?)(&|$)/g;
                        var matches = regexp.exec(purl);
                        if (matches.length >= 2 && matches[1].length > 0) {
                            profileVersionId = '/' + matches[1];
                        }
                    }
                    //console.log("load pwt file");
                    loadAndWatchScript(url + profileVersionId + '/pwt.js', '/pwt.js'); 
                    loadAndWatchScript('https://securepubads.g.doubleclick.net/tag/js/gpt.js', '/gpt.js');
                },
                "enableLogging": function() {
                    var dis = this;
                    function allTrue(type, subtype) {
                        var units = dis.units.pwt;
                        var response = true;
                        switch (subtype) {
                            case 'bids':
                                for(var i=0;i<units.length;i++) {
                                    var unit = units[i];
                                    var code = unit.code;
                                    if (!dis.units.states[code][subtype][type]) {
                                        response = false;
                                    }
                                }
                                break;
                            case 'drawing':
                                for(var i=0;i<units.length;i++) {
                                    var unit = units[i];
                                    var code = unit.code;
                                    if (!dis.units.states[code][subtype]) {
                                        response = false;
                                    }
                                }
                                break;
                        }
                        return response;
                    }
                    document.addEventListener("adrendered", function(e) {if (e.detail) { console.log("adsdebug adrendered", e.detail, (new Date()).getTime() - window.pageloadTimer.getTime(), "empty", dis.units.states[e.detail].empty, "retries.background", dis.units.states[e.detail].retries.background, "retries.viewable", dis.units.states[e.detail].retries.viewable); }})
                    document.addEventListener("bidsrecieved", function(e) {if (e.detail) { console.log("adsdebug bidsrecieved", e.detail.adUnitCode, (new Date()).getTime() - window.pageloadTimer.getTime(), "a9", e.detail.adUnitCode === 'all_units' ? allTrue('a9', 'bids') : dis.units.states[e.detail.adUnitCode].bids.a9, "pwt", e.detail.adUnitCode === 'all_units' ? allTrue('pwt', 'bids') : dis.units.states[e.detail.adUnitCode].bids.pwt, "drawing", e.detail.adUnitCode === 'all_units' ? allTrue('pwt', 'drawing') : dis.units.states[e.detail.adUnitCode].drawing); }});
                    document.addEventListener("waitingover", function(e) {if (e.detail) { console.log("adsdebug waitingover", e.detail, (new Date()).getTime() - window.pageloadTimer.getTime()); }});
                    document.addEventListener("viewable", function(e) {if (e.detail) { console.log("adsdebug viewable", e.detail, (new Date()).getTime() - window.pageloadTimer.getTime(), "empty", dis.units.states[e.detail].empty, "attempted", dis.units.states[e.detail].attempted, "retries.background", dis.units.states[e.detail].retries.background, "retries.viewable", dis.units.states[e.detail].retries.viewable); }});
                    document.addEventListener("timeout", function(e) {if (e.detail) { console.log("adsdebug timeout", e.detail.adUnitCode, (new Date()).getTime() - window.pageloadTimer.getTime(), e.detail.fromViewableEvent); }});
                    document.addEventListener("library", function(e) {if (e.detail) { console.log("adsdebug library", e.detail, (new Date()).getTime() - window.pageloadTimer.getTime()); }});
                },
                "report": function() {
                    var dis = this;
                    var div = document.getElementById('viewableads_report');
                    var html = '<div class="table-headers"><b></b></div><ul class="nof table">';
                    for(var i=0;i<this.units.pwt.length;i++) {
                        var code = this.units.pwt[i].code,
                            state = this.units.states[code],
                            bids = [],
                            info = 'waiting';
                        if (state.bids.a9) bids.push('A9');
                        if (state.bids.pwt) bids.push('OpenWrap');
                        if (state.bids.fetching) info = 'fetching';
                        else if (state.drawing) info = 'drawing';
                        info = code + (bids.length > 0 ? ' [' + bids.join(',') + ']' : '') + ' (' + info + ')';
                        html += '<li data-shown="' + state.shown + '" data-attempted="' + state.attempted + '">';
                        if (this.units.states[code].viewable) {
                            html += '<b>' + info + '</b><b></b></li>';
                        } else {
                            html += '<b></b><b>' + info + '</b></li>';
                        }
                    }
                    html += '</ul>';
                    div.innerHTML = html;
                }   
            }
            if (typeof adCommands !== "undefined") {
                if (pm.ads.logging) {
                    window.pm.ads.enableLogging();
                }
                adCommands.forEach(function(command) { command(window.pm.ads); });
                Object.defineProperty(adCommands, "push", {
                    enumerable: false, // hide from for...in
                    configurable: false, // prevent further meddling...
                    writable: false, // see above ^
                    value: function(command) { command(window.pm.ads); }
                });
            }
        }
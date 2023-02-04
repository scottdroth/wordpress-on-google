var adCommands = [];
adCommands.push(function (ads) {
   const REFRESH_INTERVAL = 31000;
   const PREBID_TIMEOUT = 500;
   const layout = ads.getLayout();
   const adUnits = [
      {
         code: "top_leaderboard",
         mediaTypes: {
            banner: {
               sizes:
                  layout === "small"
                     ? [[320, 50]]
                     : layout === "medium"
                     ? [[728, 90]]
                     : [[728, 90]],
            },
         },
         bids:
            layout === "small"
               ? [
                    {
                       bidder: "appnexus",
                       params: {
                          placementId: "16586479",
                       },
                    },
                    {
                       bidder: "rubicon",
                       params: {
                          accountId: "17188",
                          position: "atf",
                          siteId: "273474",
                          zoneId: "1364260",
                       },
                    },
                    {
                       bidder: "ix",
                       params: {
                          siteId: "397490",
                          size: [320, 50],
                       },
                    },
                    {
                       bidder: "pubmatic",
                       params: {
                          adSlot: "3452956",
                          publisherId: "160365",
                       },
                    },
                 ]
               : [
                    {
                       bidder: "appnexus",
                       params: {
                          placementId: "16586480",
                       },
                    },
                    {
                       bidder: "rubicon",
                       params: {
                          accountId: "17188",
                          position: "atf",
                          siteId: "273474",
                          zoneId: "1364258",
                       },
                    },
                    {
                       bidder: "ix",
                       params: {
                          siteId: "397488",
                          size: [728, 90],
                       },
                    },
                    {
                       bidder: "pubmatic",
                       params: {
                          adSlot: "3452956",
                          publisherId: "160365",
                       },
                    },
                 ],
      },
      {
         code: "mid_leaderboard_rectangle_1",
         mediaTypes: {
            banner: {
               sizes: [[300, 250]],
            },
            video: {
               context: "outstream",
               playerSize: [[300, 250]],
            },
         },
         bids: [
            {
               bidder: "appnexus",
               params: {
                  placementId: "16586481",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "btf",
                  siteId: "273474",
                  zoneId: "1364258",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "397498",
                  size: [300, 250],
               },
            },
            {
               bidder: "pubmatic",
               params: {
                  adSlot: "3452960",
                  publisherId: "160365",
               },
            },
            {
               bidder: "appnexus",
               params: {
                  placementId: "14384668",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "atf",
                  siteId: "355144",
                  zoneId: "1895518",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "600002",
                  size: [300, 250],
               },
            },
         ],
      },
      {
         code: "mid_leaderboard_rectangle_2",
         mediaTypes: {
            banner: {
               sizes: [[300, 250]],
            },
            video: {
               context: "outstream",
               playerSize: [[300, 250]],
            },
         },
         bids: [
            {
               bidder: "appnexus",
               params: {
                  placementId: "16586482",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "btf",
                  siteId: "273474",
                  zoneId: "1364258",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "397499",
                  size: [300, 250],
               },
            },
            {
               bidder: "pubmatic",
               params: {
                  adSlot: "3452961",
                  publisherId: "160365",
               },
            },
            {
               bidder: "appnexus",
               params: {
                  placementId: "14384640",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "btf",
                  siteId: "355144",
                  zoneId: "1895518",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "600003",
                  size: [300, 250],
               },
            },
         ],
      },
      {
         code: "mid_leaderboard_rectangle_3",
         mediaTypes: {
            banner: {
               sizes: [[300, 250]],
            },
            video: {
               context: "outstream",
               playerSize: [[300, 250]],
            },
         },
         bids: [
            {
               bidder: "appnexus",
               params: {
                  placementId: "16586483",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "btf",
                  siteId: "273474",
                  zoneId: "1364258",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "397500",
                  size: [300, 250],
               },
            },
            {
               bidder: "pubmatic",
               params: {
                  adSlot: "3452962",
                  publisherId: "160365",
               },
            },
            {
               bidder: "appnexus",
               params: {
                  placementId: "20549494",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "btf",
                  siteId: "355144",
                  zoneId: "1895518",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "600004",
                  size: [300, 250],
               },
            },
         ],
      },
      {
         code: "mid_leaderboard_rectangle_4",
         mediaTypes: {
            banner: {
               sizes: [[300, 250]],
            },
            video: {
               context: "outstream",
               playerSize: [[300, 250]],
            },
         },
         bids: [
            {
               bidder: "appnexus",
               params: {
                  placementId: "16586484",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "btf",
                  siteId: "273474",
                  zoneId: "1364258",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "397501",
                  size: [300, 250],
               },
            },
            {
               bidder: "pubmatic",
               params: {
                  adSlot: "3452963",
                  publisherId: "160365",
               },
            },
            {
               bidder: "appnexus",
               params: {
                  placementId: "20549496",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "btf",
                  siteId: "355144",
                  zoneId: "1895518",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "600005",
                  size: [300, 250],
               },
            },
         ],
      },
      {
         code: "mid_leaderboard_rectangle_5",
         mediaTypes: {
            banner: {
               sizes: [[300, 250]],
            },
            video: {
               context: "outstream",
               playerSize: [[300, 250]],
            },
         },
         bids: [
            {
               bidder: "appnexus",
               params: {
                  placementId: "16586485",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "btf",
                  siteId: "273474",
                  zoneId: "1364258",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "397502",
                  size: [300, 250],
               },
            },
            {
               bidder: "pubmatic",
               params: {
                  adSlot: "3452964",
                  publisherId: "160365",
               },
            },
            {
               bidder: "appnexus",
               params: {
                  placementId: "20549497",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "btf",
                  siteId: "355144",
                  zoneId: "1895518",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "600006",
                  size: [300, 250],
               },
            },
         ],
      },
      {
         code: "mid_leaderboard_rectangle_6",
         mediaTypes: {
            banner: {
               sizes: [[300, 250]],
            },
            video: {
               context: "outstream",
               playerSize: [[300, 250]],
            },
         },
         bids: [
            {
               bidder: "appnexus",
               params: {
                  placementId: "16586486",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "btf",
                  siteId: "273474",
                  zoneId: "1364258",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "397503",
                  size: [300, 250],
               },
            },
            {
               bidder: "pubmatic",
               params: {
                  adSlot: "3452965",
                  publisherId: "160365",
               },
            },
            {
               bidder: "appnexus",
               params: {
                  placementId: "20549498",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "btf",
                  siteId: "355144",
                  zoneId: "1895518",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "600007",
                  size: [300, 250],
               },
            },
         ],
      },
      {
         code: "mid_leaderboard_rectangle_7",
         mediaTypes: {
            banner: {
               sizes: [[300, 250]],
            },
            video: {
               context: "outstream",
               playerSize: [[300, 250]],
            },
         },
         bids: [
            {
               bidder: "appnexus",
               params: {
                  placementId: "16586487",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "btf",
                  siteId: "273474",
                  zoneId: "1364258",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "397504",
                  size: [300, 250],
               },
            },
            {
               bidder: "pubmatic",
               params: {
                  adSlot: "3452966",
                  publisherId: "160365",
               },
            },
            {
               bidder: "appnexus",
               params: {
                  placementId: "20549499",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "btf",
                  siteId: "355144",
                  zoneId: "1895518",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "600008",
                  size: [300, 250],
               },
            },
         ],
      },
      {
         code: "mid_leaderboard_rectangle_8",
         mediaTypes: {
            banner: {
               sizes: [[300, 250]],
            },
            video: {
               context: "outstream",
               playerSize: [[300, 250]],
            },
         },
         bids: [
            {
               bidder: "appnexus",
               params: {
                  placementId: "16586488",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "btf",
                  siteId: "273474",
                  zoneId: "1364258",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "397505",
                  size: [300, 250],
               },
            },
            {
               bidder: "pubmatic",
               params: {
                  adSlot: "3452967",
                  publisherId: "160365",
               },
            },
            {
               bidder: "appnexus",
               params: {
                  placementId: "20549501",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "btf",
                  siteId: "355144",
                  zoneId: "1895518",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "600009",
                  size: [300, 250],
               },
            },
         ],
      },
      {
         code: "mid_leaderboard_rectangle_9",
         mediaTypes: {
            banner: {
               sizes: [[300, 250]],
            },
            video: {
               context: "outstream",
               playerSize: [[300, 250]],
            },
         },
         bids: [
            {
               bidder: "appnexus",
               params: {
                  placementId: "16586489",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "btf",
                  siteId: "273474",
                  zoneId: "1364258",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "397506",
                  size: [300, 250],
               },
            },
            {
               bidder: "pubmatic",
               params: {
                  adSlot: "3452968",
                  publisherId: "160365",
               },
            },
            {
               bidder: "appnexus",
               params: {
                  placementId: "20549503",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "btf",
                  siteId: "355144",
                  zoneId: "1895518",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "600010",
                  size: [300, 250],
               },
            },
         ],
      },
      {
         code: "mid_leaderboard_rectangle_10",
         mediaTypes: {
            banner: {
               sizes: [[300, 250]],
            },
            video: {
               context: "outstream",
               playerSize: [[300, 250]],
            },
         },
         bids: [
            {
               bidder: "appnexus",
               params: {
                  placementId: "16586490",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "btf",
                  siteId: "273474",
                  zoneId: "1364258",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "397507",
                  size: [300, 250],
               },
            },
            {
               bidder: "pubmatic",
               params: {
                  adSlot: "3452969",
                  publisherId: "160365",
               },
            },
            {
               bidder: "appnexus",
               params: {
                  placementId: "20549504",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "btf",
                  siteId: "355144",
                  zoneId: "1895518",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "600011",
                  size: [300, 250],
               },
            },
         ],
      },
      {
         code: "top_rectangle",
         mediaTypes: {
            banner: {
               sizes: [
                  [300, 600],
                  [300, 250],
               ],
            },
            video: {
               context: "outstream",
               playerSize: [[300, 250]],
            },
         },
         bids: [
            {
               bidder: "appnexus",
               params: {
                  placementId: "16586491",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "btf",
                  siteId: "273474",
                  zoneId: "1364258",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "397508",
                  size: [300, 250],
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "397493",
                  size: [300, 600],
               },
            },
            {
               bidder: "pubmatic",
               params: {
                  adSlot: "3452957",
                  publisherId: "160365",
               },
            },
            {
               bidder: "appnexus",
               params: {
                  placementId: "14384668",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "atf",
                  siteId: "355144",
                  zoneId: "1895518",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "600012",
                  size: [300, 250],
               },
            },
         ],
      },
      {
         code: "middle_rectangle",
         mediaTypes: {
            banner: {
               sizes: [[300, 250]],
            },
            video: {
               context: "outstream",
               playerSize: [[300, 250]],
            },
         },
         bids: [
            {
               bidder: "appnexus",
               params: {
                  placementId: "16586492",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "btf",
                  siteId: "273474",
                  zoneId: "1364258",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "397509",
                  size: [300, 250],
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "397494",
                  size: [300, 600],
               },
            },
            {
               bidder: "pubmatic",
               params: {
                  adSlot: "3452958",
                  publisherId: "160365",
               },
            },
            {
               bidder: "appnexus",
               params: {
                  placementId: "20549509",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "btf",
                  siteId: "355144",
                  zoneId: "1895518",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "600013",
                  size: [300, 250],
               },
            },
         ],
      },
      {
         code: "bottom_rectangle",
         mediaTypes: {
            banner: {
               sizes: [
                  [300, 600],
                  [300, 250],
               ],
            },
            video: {
               context: "outstream",
               playerSize: [[300, 250]],
            },
         },
         bids: [
            {
               bidder: "appnexus",
               params: {
                  placementId: "16586494",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "btf",
                  siteId: "273474",
                  zoneId: "1364258",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "397510",
                  size: [300, 250],
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "397495",
                  size: [300, 600],
               },
            },
            {
               bidder: "pubmatic",
               params: {
                  adSlot: "3452959",
                  publisherId: "160365",
               },
            },
            {
               bidder: "appnexus",
               params: {
                  placementId: "20549511",
               },
            },
            {
               bidder: "rubicon",
               params: {
                  accountId: "17188",
                  position: "btf",
                  siteId: "355144",
                  zoneId: "1895518",
               },
            },
            {
               bidder: "ix",
               params: {
                  siteId: "600014",
                  size: [300, 250],
               },
            },
         ],
      },
      {
         code: "bottom_leaderboard",
         mediaTypes: {
            banner: {
               sizes:
                  layout === "small"
                     ? [
                          [320, 50],
                          [320, 100],
                       ]
                     : layout === "medium"
                     ? [[728, 90]]
                     : [[728, 90]],
            },
         },
         bids:
            layout === "small"
               ? [
                    {
                       bidder: "appnexus",
                       params: {
                          placementId: "16586501",
                       },
                    },
                    {
                       bidder: "rubicon",
                       params: {
                          accountId: "17188",
                          position: "atf",
                          siteId: "273474",
                          zoneId: "1364260",
                       },
                    },
                    {
                       bidder: "ix",
                       params: {
                          siteId: "397491",
                          size: [320, 50],
                       },
                    },
                    {
                       bidder: "pubmatic",
                       params: {
                          adSlot: "3452970",
                          publisherId: "160365",
                       },
                    },
                 ]
               : [
                    {
                       bidder: "appnexus",
                       params: {
                          placementId: "16586496",
                       },
                    },
                    {
                       bidder: "rubicon",
                       params: {
                          accountId: "17188",
                          position: "atf",
                          siteId: "273474",
                          zoneId: "1364258",
                       },
                    },
                    {
                       bidder: "ix",
                       params: {
                          siteId: "397489",
                          size: [728, 90],
                       },
                    },
                    {
                       bidder: "pubmatic",
                       params: {
                          adSlot: "3452970",
                          publisherId: "160365",
                       },
                    },
                 ],
      },
   ];

   window.pm.adUnitsX = adUnits;
   //ads.initialize(adUnits, PREBID_TIMEOUT, REFRESH_INTERVAL, REFRESH_INTERVAL + 5000);
});


setTimeout(
   loadScript,
   1500,
   `${templateDirURI}/js/ads-gam-a9-ow.js?ver=b&cb=39.2022.10209.11821.2`,
   function () {
      window.pm.ads.initialize(window.pm.adUnitsX, 500, 31000, 31000 + 5000);
   }
);

/* if (!window.isFrontPage) {
   setTimeout(
      loadScript,
      2000,
      `https://tag.bounceexchange.com/3869/i.js`,
      function () {}
   );
} */

// moment.js language configuration
// language : great britain english (en-gb)
// author : Chris Gedrim : https://github.com/chrisgedrim

(function (factory) {
    if (typeof define === 'function' && define.amd) {
        define(['moment'], factory); // AMD
    } else if (typeof exports === 'object') {
        module.exports = factory(require('../moment')); // Node
    } else {
        factory(window.moment); // Browser global
    }
}(function (moment) {
    return moment.lang('da', {
        months : "January_February_March_April_May_June_July_August_September_October_November_December".split("_"),
        monthsShort : "Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec".split("_"),
        weekdays : "Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_"),
        weekdaysShort : "Sun_Mon_Tue_Wed_Thu_Fri_Sat".split("_"),
        weekdaysMin : "Su_Mo_Tu_We_Th_Fr_Sa".split("_"),
        longDateFormat : {
            LT : "HH:mm",
            L : "DD/MM/YYYY",
            LL : "D MMMM YYYY",
            LLL : "D MMMM YYYY LT",
            LLLL : "dddd, D MMMM YYYY LT"
        },
        calendar : {
            sameDay : '[Today at] LT',
            nextDay : '[Tomorrow at] LT',
            nextWeek : 'dddd [at] LT',
            lastDay : '[Yesterday at] LT',
            lastWeek : '[Last] dddd [at] LT',
            sameElse : 'L'
        },
        relativeTime : {
            future : "in %s",
            past : "%s ago",
            s : "a few seconds",
            m : "a minute",
            mm : "%d minutes",
            h : "an hour",
            hh : "%d hours",
            d : "a day",
            dd : "%d days",
            M : "a month",
            MM : "%d months",
            y : "a year",
            yy : "%d years"
        },
        ordinal : function (number) {
            var b = number % 10,
                output = (~~ (number % 100 / 10) === 1) ? 'th' :
                (b === 1) ? 'st' :
                (b === 2) ? 'nd' :
                (b === 3) ? 'rd' : 'th';
            return number + output;
        },
        week : {
            dow : 1, // Monday is the first day of the week.
            doy : 4  // The week that contains Jan 4th is the first week of the year.
        }
    });
}));
// // moment.js language configuration
// // language : danish (da)
// // author : Ulrik Nielsen : https://github.com/mrbase

// (function (factory) {
//     if (typeof define === 'function' && define.amd) {
//         define(['moment'], factory); // AMD
//     } else if (typeof exports === 'object') {
//         module.exports = factory(require('../moment')); // Node
//     } else {
//         factory(window.moment); // Browser global
//     }
// }(function (moment) {
//     return moment.lang('da', {
//         months : "januar_februar_marts_april_maj_juni_juli_august_september_oktober_november_december".split("_"),
//         monthsShort : "jan_feb_mar_apr_maj_jun_jul_aug_sep_okt_nov_dec".split("_"),
//         weekdays : "søndag_mandag_tirsdag_onsdag_torsdag_fredag_lørdag".split("_"),
//         weekdaysShort : "søn_man_tir_ons_tor_fre_lør".split("_"),
//         weekdaysMin : "sø_ma_ti_on_to_fr_lø".split("_"),
//         longDateFormat : {
//             LT : "HH:mm",
//             L : "DD/MM/YYYY",
//             LL : "D MMMM YYYY",
//             LLL : "D MMMM YYYY LT",
//             LLLL : "dddd D. MMMM, YYYY LT"
//         },
//         calendar : {
//             sameDay : '[I dag kl.] LT',
//             nextDay : '[I morgen kl.] LT',
//             nextWeek : 'dddd [kl.] LT',
//             lastDay : '[I går kl.] LT',
//             lastWeek : '[sidste] dddd [kl] LT',
//             sameElse : 'L'
//         },
//         relativeTime : {
//             future : "om %s",
//             past : "%s siden",
//             s : "få sekunder",
//             m : "et minut",
//             mm : "%d minutter",
//             h : "en time",
//             hh : "%d timer",
//             d : "en dag",
//             dd : "%d dage",
//             M : "en måned",
//             MM : "%d måneder",
//             y : "et år",
//             yy : "%d år"
//         },
//         ordinal : '%d.',
//         week : {
//             dow : 1, // Monday is the first day of the week.
//             doy : 4  // The week that contains Jan 4th is the first week of the year.
//         }
//     });
// }));
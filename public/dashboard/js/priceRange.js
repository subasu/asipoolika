$(function() {
    $("#range_sell").ionRangeSlider({
        hide_min_max: true,
        keyboard: true,
        min: 0,
        max: 1000000000,
        from: 0,
        to: 400000000,
        type: 'double',
        step: 1,
        postfix: " تومان ",
        grid: true,
        prettify_separator: "."
    });
    $("#range_rahn").ionRangeSlider({
        hide_min_max: true,
        keyboard: true,
        min: 0,
        max: 50000000,
        from: 0,
        to: 40000000,
        type: 'double',
        step: 1,
        postfix: "تومان",
        grid: true,
        prettify_separator: "."
    });
    $("#range_rent").ionRangeSlider({
        hide_min_max: true,
        keyboard: true,
        min: 0,
        max: 50000000,
        from: 0,
        to: 30000000,
        type: 'double',
        step: 1,
        postfix: "تومان",
        grid: true,
        prettify_separator: "."
    });
    $("#range_sell_details").ionRangeSlider({
        hide_min_max: true,
        keyboard: true,
        min: 0,
        max: 1000000000,
        from: 0,
        to: 400000000,
        type: 'double',
        step: 1,
        postfix: " تومان ",
        grid: true,
        prettify_separator: "."
    });
    $("#range_rahn_details").ionRangeSlider({
        hide_min_max: true,
        keyboard: true,
        min: 0,
        max: 50000000,
        from: 0,
        to: 40000000,
        type: 'double',
        step: 1,
        postfix: "تومان",
        grid: true,
        prettify_separator: "."
    });
    $("#range_rent_details").ionRangeSlider({
        hide_min_max: true,
        keyboard: true,
        min: 0,
        max: 50000000,
        from: 100000,
        to: 30000000,
        type: 'double',
        step: 1,
        postfix: "تومان",
        grid: true,
        prettify_separator: "."
    });
    $("#range_meter_details").ionRangeSlider({
        hide_min_max: true,
        keyboard: true,
        min: 50,
        max: 2000,
        from: 50,
        to: 700,
        type: 'double',
        step: 1,
        postfix: "متر",
        grid: true,
        prettify_separator: "."
    });



});

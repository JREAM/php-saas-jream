jQuery(document).ready(function($) {
    // $('.carousel').carousel({interval: 5000});
    $("[data-toggle=tooltip]").tooltip();
    $("[data-toggle=popover]").popover({
        trigger: 'hover'
    });

    $("a[href='#top']").click(function() {
        $("html, body").animate({ scrollTop: 0 }, "slow");
        return false;
    });

    if ($.isFunction($.fn.autosize)) {
        $('textarea .autosize').autosize();
    }

    if ($.isFunction($.fn.expander)) {
        $('div.expandable').expander({
            slicePoint:       150,
            expandPrefix:     ' ',
            expandText:       '(more)',
            collapseTimer:    5000,
            userCollapseText: '(less)',
            expandEffect: 'slideDown',
            collapseEffect: 'slideUp',
            preserveWords: true
        });
    }

    $(".disable-click").click(function(evt) {
        $(this).addClass('disabled');
        return true;
    });

    if ($.isFunction($.fn.dataTable)) {
        $('.data-table').dataTable();
    }

    $(document).scroll(function () {
        var scroll  = $(this).scrollTop();
        if (scroll > 600) {
            $('#goto-top').removeClass('hide');
        } else {
            $('#goto-top').addClass('hide');
        }
    });

    toggleLogo();
    // Detect when to show min logo
    $(window).resize(function() {
        toggleLogo();
    });

    function toggleLogo() {
        if ($(window).width() < 980) {
            $("#logo").addClass('hide');
            $("#logo-ico").removeClass('hide');
        } else {
            $("#logo").removeClass('hide');
            $("#logo-ico").addClass('hide');
        }
    }

});
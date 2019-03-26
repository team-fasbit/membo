//<script>
$(document).ready(function() {
    $(document).on('click', '#sidebar-toggle', function() {
        var $toggle = $(this).attr('data-toggle');
        if ($toggle == 0) {
            $(this).attr('data-toggle', 1);
            $('.sidebar').addClass('sidebar-open');
            $('.ossn-page-container').addClass('sidebar-open-page-container');
            $('.topbar .right-side').addClass('right-side-space');
            $('.topbar .right-side').addClass('sidebar-hide-contents-xs');
            $('.ossn-inner-page').addClass('sidebar-hide-contents-xs');
        }
        if ($toggle == 1) {
            $(this).attr('data-toggle', 0);
            $('.sidebar').removeClass('sidebar-open');
            $('.ossn-page-container').removeClass('sidebar-open-page-container');
            $('.topbar .right-side').removeClass('right-side-space');
            $('.topbar .right-side').removeClass('sidebar-hide-contents-xs');
            $('.ossn-inner-page').removeClass('sidebar-hide-contents-xs');

            $('.topbar .right-side').addClass('right-side-nospace');
            $('.sidebar').addClass('sidebar-close');
            $('.ossn-page-container').addClass('sidebar-close-page-container');

        }
        var document_height = $(document).height();
        $(".sidebar").height(document_height);
    });
    var $chatsidebar = $('.ossn-chat-windows-long .inner');
    if ($chatsidebar.length) {
        $chatsidebar.css('height', $(window).height() - 45);
    }
    $(document).scroll(function() {
        if ($chatsidebar.length) {
            if ($(document).scrollTop() >= 50) {
                $chatsidebar.addClass('ossnchat-scroll-top');
                $chatsidebar.css('height', $(window).height());
            } else if ($(document).scrollTop() == 0) {
                $chatsidebar.removeClass('ossnchat-scroll-top');
                $chatsidebar.css('height', $(window).height() - 45);
            }
        }
    });
    var isMobile = function() {
        var envValues = ["xs", "sm", "md", "lg"];

        var $el = $('<div>');
        $el.appendTo($('body'));

        for (var i = envValues.length - 1; i >= 0; i--) {
            var envVal = envValues[i];

            $el.addClass('hidden-' + envVal);
            if ($el.is(':hidden')) {
                $el.remove();
                return envValues[i]
            }
        }
    };
	var moveSidebar = function(){
		var dtype = isMobile();
		if(dtype == 'xs' || dtype == 'sm'){
			$('.sidebar-cont').appendTo('.ossn-layout-newsfeed');	
		} else {
			$('.sidebar-cont').prependTo('.ossn-layout-newsfeed');	
		}
	};
    $(window).resize(function() {
      	moveSidebar();
    });
	moveSidebar();
});
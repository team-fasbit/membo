<script>
    $(document).ready(function() {
        $(window).resize(setMinHeight);
        setMinHeight();

        function setMinHeight () {
            var ossn_layout_startup = $('.ossn-inner-page .ossn-layout-startup')
            if (ossn_layout_startup.length) {
                ossn_layout_startup.css('min-height', ($(window).innerHeight() - $('.topbar').outerHeight())) + 'px';
            }
        }
    });
</script>
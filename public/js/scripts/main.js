
import '../../plugins/scrollbar/scrollbar.min.js';
import '../../plugins/scrollbar/custom-scroll.js';

(function ($) {
    $(function () {
        "use strict";

        setTimeout(() => {
            const overlay = $('#overlay');
            if ($('html').hasClass('theme')) {
                overlay.text('Failed to load page due to programming error. If this issue persists contact the administrator')
            }
            else {
                overlay.hide();
            }
        });



        window.onbeforeunload = () => {
           
            Overlay(true, 'Reloading')

            $(".reload-dismiss").remove();
            $(".reload-hide").hide();
            $('.swal-modal').remove();
        };

        

        // setTimeout(() => {
        $(".scrollable").each(function () {
            const track = $(this).next("div.scrollable-track");
            const thumb = $(".scrollable-thumb", track);
            const scrollableHeight = $(this)[0].scrollHeight;
            const height = $(this).height();
            const diff = scrollableHeight - height;
            // thumb.css({height: diff + 'px'})
            // alert(thumb.length)

            $(this).on("scroll", function (e) {
                thumb.css("top", e.target.scrollTop + "px");
            });
        });
        // }, 8000);

        
        

        $(window).on("resize", function (e) {
            $(".dropdown-container.show").each(function () {
                const dropdownMenu = $(".dropdown-menu", this);
                const trigger = $(".dropdown-toggle", this);
                const gaps = dropdownMenu.attr("set-cordinates");

                if (dropdownMenu.attr("set-cordinates")) {
                    const [gapX, gapY] = gaps
                        .split(",")
                        .map((item) => parseFloat(item) || 0);
                    const cordinates = trigger[0].getBoundingClientRect();

                    dropdownMenu.css({
                        top: `${gapY + cordinates.top}px`,
                        left: `${gapX + cordinates.left}px`,
                    });
                }
            });
        });

        $("img[gender]").each(function () {
            const img = $(this);
            const gender = $(this).attr("gender") || "u";
            const old = $(this).attr("old-src");

            img.on("error", function () {
                $(this).attr("src", "/images/avatar-" + gender + ".png");
            });
        });

        $(".scrollable").each(function () {
            var scrollbarTrack = $(this).next(".scrollable-track");
            let scrollbarThumb;
            if (scrollbackTrack.length === 0) {
                scrollbarTrack = $('<div>');
                scrollbarTrack.addClass('scrollable-track');
                scrollbarTrack.insertAfter($(this));
                scrollbarThumb = $('<div>');
                scrollbarThumb.addClass('scrollable-thumb');
                scrollbarTrack.append(scrollbarThumb);
            }
            else {
                scrollbarThumb = $(".scrollable-thumb", scrollbarTrack);
            }
            const container = $(this)[0];
        
            function updateScrollbar() {
                var contentHeight = container.scrollHeight;
                var visibleHeight = scrollbarTrack[0].clientHeight;
                var scrollableHeight = contentHeight - visibleHeight;
                var scrollPercentage = (container.scrollTop / scrollableHeight) * 100;
                var ratio = visibleHeight / contentHeight;
                var thumbHeight = visibleHeight * ratio;
        
                scrollbarThumb.css({
                    top: scrollPercentage + "%",
                    height: thumbHeight + "px",
                });
            }
        
            function handleScroll() {
                var contentHeight = container.scrollHeight;
                var visibleHeight = container.clientHeight;
                var scrollableHeight = contentHeight - visibleHeight;
                var scrollPosition = (container.scrollTop / scrollableHeight) * 100;
        
                var thumbPosition = (scrollPosition * visibleHeight) / 100;
                scrollbarThumb.css("top", thumbPosition + "px");
            }
        
            $(this).on("scroll", function () {
                updateScrollbar();
                handleScroll();
            });
            setTimeout(function () {
                updateScrollbar();
            }, 5000);
        
            window.addEventListener("resize", updateScrollbar);
        });
    });
})(jQuery);


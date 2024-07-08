
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
            const loading = $("#isLoading");
            

            $("#loadingText", loading).text("Reloading...");
            loading.addClass('show');

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

        $("inputx[type=file][preview-at]").each(function () {
            const preview = $(this).attr("preview-at");
            const image = $(preview);

            if (image.length > 0) {

                $(this).on("change", function () {
                    const files = this.files;
                    if (!files || files.length === 0) {
                        return;
                    }
                    const file = files[0];
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        const dataURL = e.target.result;
                        image[0].src = dataURL;
                    };

                    reader.readAsDataURL(file);
                });
            }
        });
    });
})(jQuery);


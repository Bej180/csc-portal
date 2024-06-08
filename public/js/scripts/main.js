(function ($) {
    $(function () {
        "use strict";
        offOverlay(3000);

        // setTimeout(() => {
        $(".scrollable").each(function () {
            const track = $(this).next("div.scrollable-track");
            const thumb = $(".scrollable-thumb", track);
            const scrollableHeight = $(this)[0].scrollHeight;
            const height = $(this).height();
            const diff = scrollableHeight - height;
            // thumb.css({height: diff+'px'})
            // alert(thumb.length)
            console.log(track);

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

        $("input[type=file][preview-at]").each(function () {
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


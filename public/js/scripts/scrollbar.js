$(document).ready(function () {
    $(".v-scroll").each(function () {
        const wrapper = $(this);
        const content = wrapper.clone(true);

        const container = $('<div class="vertical-scrollbar"></div>');
        const scrollbar = $('<div class="v-scrollbar"></div>');
        const track = $('<div class="v-track"></div>');
        const thumb = $('<div class="v-thumb"></div>');

        track.append(thumb);
        scrollbar.append(track);
        container.append(content, scrollbar);
        content.attr("class", "content-scrolling");
        wrapper.replaceWith(container);

        function updateThumb() {
            const contentHeight = content[0].scrollHeight;
            const containerHeight = content.height();
            const scrollRatio = containerHeight / contentHeight;
            const thumbHeight = Math.max(scrollRatio * containerHeight, 20); // Minimum height of 20px

            thumb.height(thumbHeight);

            const scrollTop = content.scrollTop();
            const thumbTop =
                (scrollTop * (containerHeight - thumbHeight)) /
                (contentHeight - containerHeight);

            thumb.css("top", `${thumbTop}px`);
        }

        content.on("scroll", updateThumb);

        let isDragging = false;
        let startY;
        let startTop;

        thumb.on("mousedown", function (e) {
            isDragging = true;
            startY = e.clientY;
            startTop = parseInt(thumb.css("top"), 10);
            $("body").css("user-select", "none");
        });

        $(document).on("mousemove", function (e) {
            if (!isDragging) return;
            const deltaY = e.clientY - startY;
            const newTop = Math.min(
                track.height() - thumb.height(),
                Math.max(0, startTop + deltaY)
            );
            thumb.css("top", `${newTop}px`);
            const scrollRatio = newTop / (track.height() - thumb.height());
            content.scrollTop(
                scrollRatio * (content[0].scrollHeight - content.height())
            );
        });

        $(document).on("mouseup", function () {
            isDragging = false;
            $("body").css("user-select", "auto");
        });

        updateThumb();
        $(window).on("resize", updateThumb);
    });
});

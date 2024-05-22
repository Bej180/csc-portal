app.directive("mask", function () {
    return {
        restrict: "A",
        // scope: {
        //     // ngModel: '=',
        //     // pattern: '@',
        // },
        link: function (scope, element, attr) {
            const pattern = element.attr("mask");
            const patt = pattern.replace(/\d/g, "_");

            element.on("focus", function () {
                $(this).val(patt);
                this.select();
                this.setSelectionRange(0, 0);
            });

            element.on("blur", function () {
                if ($(this).val() === patt) {
                    $(this).val("");
                }
            });

            element.on("keydown", function (e) {
                //     alert(pattern);
            });
        },
    };
});
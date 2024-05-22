(function ($) {
    "use strict";
    $.mask.definitions["~"] = "[+-]";
    $('.mk-session').mask('9999/9999');
    $(".mk-date").mask("99/99/9999");
    $('.mk-1').mask('9');
    $('.mk-2').mask('99');
    $('.mk-reg_no').mask('99999999999');
    $('.mk-3').mask('999');
    $('.mk-4').mask('9999');
    $(".mk-phone").mask("(999) 999-9999-9");
    $("input[type=tel]").mask("(999) 999-9999-9");
    $("#phoneExt").mask("(999) 999-9999-9? x99999");
    $("#iphone").mask("+33 999 999 999");
    $("#tin").mask("99-9999999");
    $("#ccn").mask("9999 9999 9999 9999");
    $("#ssn").mask("999-99-9999");
    $("mk-currency").mask("999,999,999.99");
    $("#product").mask("a*-999-a999", { placeholder: " " });
    $("#eyescript").mask("~9.99 ~9.99 999");
    $("#po").mask("PO: aaa-999-***");
    $("mk-percent").mask("99%");
    $("[input-mask]").each(function(){
        const mask = $(this).attr('input-mask');
        $(this).mask(mask);
    })
    $("#phoneAutoclearFalse").mask("(999) 999-9999", { autoclear: false });
    $("#phoneExtAutoclearFalse").mask("(999) 999-9999? x99999", {
        autoclear: false,
    });
    $("input")
        .blur(function () {
            $("#info").html("Unmasked value: " + $(this).mask());
        })
        .dblclick(function () {
            $(this).unmask();
        });
})(jQuery);

app.filter("more", function () {
    return function (number) {
        
        number = parseInt(number || 0);
        if (isNaN(number)) {
            return 0;
        }
        if (number > 9) {
            number += '+';
        }
        return number;
    };
  });
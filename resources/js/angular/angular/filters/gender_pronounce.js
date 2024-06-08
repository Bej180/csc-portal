app.filter("his", function () {
  return function (gender) {
      if (!gender) return gender;
      switch(gender.toLowerCase()) {
        case 'male': return 'his';
        case 'female': return 'her';
        default: return 'their';
      }
  };
});

app.filter("him", function () {
  return function (gender) {
      if (!gender) return gender;
      switch(gender.toLowerCase()) {
        case 'male': return 'him';
        case 'female': return 'her';
        default: return 'them';
      }
  };
});

app.filter("he", function () {
  return function (gender) {
      if (!gender) return gender;
      switch(gender.toLowerCase()) {
        case 'male': return 'he';
        case 'female': return 'she';
        default: return 'they';
      }
  };
});
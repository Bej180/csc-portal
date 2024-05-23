app.filter("maskEmail", function () {
  return function (email) {
      if (!email) return email;
      var parts = email.split("@");
      if (parts.length !== 2) return email;

      const [username, domain] = parts;

      // Determine the length of the username and how much of it to mask
      const usernameLength = username.length;
      const maskedLength = Math.max(Math.ceil(usernameLength / 2), 3); // Mask at least 3 characters or half of the username, whichever is greater
      let mask = "";
      for (let i = 0; i < usernameLength - maskedLength; i++) {
          mask += "*";
      }

      // Mask the username
      const maskedUsername = username.substring(0, maskedLength) + mask;

      // Reassemble the masked email address
      return maskedUsername + "@" + domain;
  };
});
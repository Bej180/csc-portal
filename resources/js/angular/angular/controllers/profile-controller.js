app.controller("AccountSetting", function ($scope) {
    // $scope.two_factor={};

    $scope.updateUserLogins = (userData) => {
        return $scope.api(
            '/app/admin/user/resetlogins',
            userData
        );
    };
    

    $scope.uploadImage = async () => {
        const formData = new FormData();
        formData.append("image", $scope.image);
        const csrfToken = await getCSRFToken();
        let headers = {};

        if (csrfToken && init.method === "POST") {
            headers["X-CSRF-TOKEN"] = csrfToken;
        }

        fetch("/api/app/user/update_profile", {
            method: "POST",
            file: formData,
            headers: headers,
        })
            .then((res) => console.log(res))
            .catch((err) => console.error(err));
    };
    $scope.onChangeFile = (event) => {
        console.log(event);
    };
    $scope.saveTwoFactorSettings = (profile) => {

      let data = {
        type: '2fa',
        two_factor_status: profile.two_factor_status,
      };
      

      if (profile.two_factor_status == 'enabled') {
        data.two_factor_frequency = profile.two_factor_frequency;
        data.two_factor_method = profile.two_factor_method;
      }

      console.log(data)
      
        return $scope.api(
            "/app/user/update_profile",
            {
                type: "2fa",
                data,
            },
            (res) => {
                console.log(res);
            },
            (err) => console.err(err)
        );
    };

    $scope.SettingsFor = () => {
        $scope.api(
            "/app/user/view_profile",
            {},
            (res) => {
                
               
                $scope.profile = res;
            },
            (err) => console.error(err)
        );
    };

    $scope.updateProfile = (type) => {
        
        return $scope.api(
            "/app/user/update_profile",
            {
                type: type,
                data: $scope.profile,
            },

            (res) => console.log(res),
            (err) => console.error(err)
        );
    };
});

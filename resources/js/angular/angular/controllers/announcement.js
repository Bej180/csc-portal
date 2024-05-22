app.controller('AnnouncementController', function($scope) {
  $scope.displayAnnouncement=false;
  $scope.show_notifications = false;
  $scope.announcements = [];

  $scope.closeAnnouncement = (event) => {
    if ($(event.target).is('.backdrop')) {
      $scope.displayAnnouncement=false;
    }
  }

  $scope.toggleNotificationVisibility = () => {
    $scope.show_notifications = !$scope.show_notifications;
  }
  $scope.hideNotifications = (event) => {
    if ($(event.target).is('.backdrop')) {
      $scope.show_notifications = false;
    }
  }

  $scope.toggleAnnouncementBell = (event) => {
    event.preventDefault();
    $scope.displayAnnouncement = !$scope.displayAnnouncement;
  }


  $scope.Announce = (announcement) => {
    return $scope.api(
      '/app/announcement/announce',
      announcement,
      res => {
        $scope.announcements = res.data
      },
      err => console.log({err}),
    )
  }

  $scope.initAnnouncement = () => {
    

    return $scope.api(
      '/app/announcements/announcer_index',
      {}, 
      res => {
        $scope.announcements = res;
      }
    );
    
  }

})
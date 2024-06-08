/**
 * Class Controller
 * Manages the functionality related to classes in the application.
 * Responsible for adding, viewing, and finding classes.
 */
app.controller("ClassController", function ($scope) {

  // Initialize variables
  $scope.addClass = false;        // Flag to control the visibility of the add class section
  $scope.displayClass = null;     // Holds the details of the currently displayed class
  $scope.class_name = Location.get('class');  // Retrieves the class name from the location

  /**
   * View Class
   * Fetches and displays details of a specific class.
   * @param {string} class_name - The name of the class to view.
   */
  $scope.viewClass = (class_name) => {
      // Fetch class details from the server
      api("/class", {
          class_name,
      })
      .then((res) => {
          // Update scope with class details and open view class pop-up
          $scope.displayClass = res;
          $scope.popend("view_class", 'popend-half');
          $scope.$apply();
          Location.set({class:class_name});  // Update location with class ID
      })
      .catch((err) => {
          console.log(err); // Log any errors
      });
  };

  /**
   * Save Class
   * Creates a new class with the provided details.
   */
  $scope.saveClass = () => {
      // Send request to create a new class
      api('/class/create', {
          name: $scope.session,
          advisor_id: $scope.advisor
      })
      .then(res => {
          console.log(res); // Log success response
      })
      .catch(err => {
          console.error(err); // Log any errors
      });
  };

  /**
   * Initialize
   * Initializes the class controller.
   * If a class ID is provided, it automatically views the class.
   */
  $scope.init = () => {
      // If class ID is provided, automatically view the class
      if ($scope.class_name) {
          $scope.viewClass($scope.class_name);
      }
  };

  /**
   * Find Class
   * Searches for classes based on the provided year.
   */
  $scope.findClass = () => {
      // Fetch classes based on the provided year
      if ($scope.year) {
          api("/class", {
              start_year: $scope.year,
          })
          .then((res) => {
              $scope.academicClass = res; // Update scope with fetched classes
              $scope.$apply();
          })
          .catch((err) => console.error(err)); // Log any errors
      }
  };
});

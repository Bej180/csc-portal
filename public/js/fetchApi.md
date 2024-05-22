## FetchApi Class

This class provides a convenient wrapper around the Fetch API, simplifying HTTP requests and offering additional functionalities like error handling, data parsing, and authorization support.

```javascript
export default class FetchApi {
  // ... constructor and other methods ...
}
```

**Explanation:**

* The class utilizes the Fetch API for making asynchronous HTTP requests, offering a cleaner and more modern approach compared to older methods like XMLHttpRequest.
* It provides additional features for a more robust development experience:
  * **Error Handling:** Handles potential errors during requests, providing a centralized location for error management.
  * **Data Parsing:** Parses response data based on the specified data type (e.g., JSON, text) for easier consumption within your application.
  * **Authorization Support:** Facilitates adding authorization headers to requests, streamlining authentication processes.

**Usage:**

Here are some examples showcasing the `FetchApi` class in action:

### 1. Basic Request Options

**1. Basic GET Request:**

```javascript
const api = new FetchApi('https://api.example.com/users');

api.get() // No data argument needed for GET requests
  .then(data => {
    console.log(data); // Array of users
  })
  .catch(error => {
    console.error('Error fetching users:', error);
  });
```

**2. POST Request with JSON Data:**

```javascript
const api = new FetchApi('https://api.example.com/posts');

const newPostData = {
  title: 'My New Post',
  content: 'This is the content of my new post.'
};

api.post(newPostData)
  .then(data => {
    console.log(data); // Newly created post object
  })
  .catch(error => {
    console.error('Error creating post:', error);
  });
```

**3. Uploading a File:**

```javascript
const api = new FetchApi('https://api.example.com/uploads');

const fileInput = document.getElementById('fileInput');
const file = fileInput.files[0];

api.file(file)
  .then(data => {
    console.log(data); // Upload response
  })
  .catch(error => {
    console.error('Error uploading file:', error);
  });
```

**4. Flexible `ajax` Method:**

```javascript
const api = new FetchApi('https://api.example.com/data');

api.ajax({
  type: 'PUT', // Use PUT for updates
  data: { id: 1, update: 'value' }, // Data for update
  dataType: 'json', // Expect JSON response
  success: data => {
    console.log(data); // Updated data object
  },
  error: error => {
    console.error('Error updating data:', error);
  },
})
```


**Purpose:**

* Perform flexible HTTP requests using the Fetch API.
* Offer configuration options for request type, data, response handling, and callbacks.
* Provide a Promise-based interface for handling results.

**Method Breakdown:**

1. **Options Parameters:**
   * `url`: The target URL for the request.
   * `options`: An optional object with configuration properties:
     * `type` (default: "GET"): HTTP method (e.g., GET, POST, PUT, DELETE).
     * `data` (default: {}): Data to send in the request body, typically as an object.
     * `dataType` (default: "json"): Expected response data type (e.g., "json", "text", "blob").
     * `success` (optional): Callback function for successful requests.
     * `error` (optional): Callback function for error responses.
     * `init` (optional): Additional Fetch API options (e.g., headers, credentials).
     * `done` (optional): Callback function to execute after request completion, regardless of success or error.
2. **Setting Default Values and Callbacks:**
   * Assigns default values to options if not provided.
   * Stores `success` and `error` callbacks for later use.
3. **Setting Content Type Header:**
   * Calls `this.setContentTypeHeader(init, dataType)` to set appropriate content type header based on expected response data type. (Implementation of this method isn't provided.)
4. **Creating AbortController:**
   * Instantiates an `AbortController` to enable request cancellation.
   * Creates a `signal` for tracking the request's abort status.
   * Exposes `abortRequest` function to allow request cancellation.
5. **Attempting Request:**
   * Uses `fetch` to initiate the request with configured options and signal for abortability.
   * Checks response status for success (`response.ok`).
     * Throws an error if status is not successful.
   * Calls `this.handleResponse(response)` to process the response (implementation not provided).
     * Returns the result of the success callback if provided.
     * Otherwise, resolves the Promise with the processed response data.
6. **Handling Errors:**
   * Catches errors during request or response handling.
     * Invokes the error callback if provided and the error is not an "AbortError".
     * Throws the error otherwise.
7. **Executing 'done' Callback:**
   * Calls the `done` callback (if provided) in a `finally` block, ensuring it executes regardless of success or error.

**Key Points:**

* Offers flexibility for various HTTP request scenarios.
* Provides callbacks for custom success and error handling.
* Supports different response data types.
* Enables request cancellation with AbortController.
* Uses Promises for asynchronous handling.


**### 5. Flexible `sanitizeInput` Method:**
This method helps prevent Cross-Site Scripting (XSS) vulnerabilities by sanitizing user input data before sending it in requests. It typically iterates through the provided data object, escaping potentially harmful characters in strings.

Example Usage:

```javascript
const userInput = {
  name: "<script>alert('XSS Attack!')</script>",
  message: "This is a safe message."
};

const sanitizedInput = api.sanitizeInput(userInput);

console.log(sanitizedInput); // Output: { name: "<script>alert('XSS Attack!')</script>", message: "This is a safe message." }
```

**6. Method `escapeHtml`:**
This method escapes special characters in a string to prevent XSS vulnerabilities. It replaces characters like `<`, `>`, and `&` with their HTML entity equivalents, rendering them as harmless text within the HTML context.

### 2. Advanced Request Options:

The `FetchApi` class might allow for setting various request options using an `options` object passed to methods like `get`, `post`, etc. These options can fine-tune the behavior of the request:

**Headers**: Set custom headers for the request.

```javascript
api.get('/users', {
  headers: {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${accessToken}`
  }
})
```

**Body**: Provide data for `POST`, `PUT`, or `PATCH` requests (typically as JSON).

**Example:**

```javascript
const newUserData = { name: 'John Doe', email: 'john.doe@example.com' };
api.post('/users', newUserData)
  // ...
```

**Credentials**: Specify whether to send cookies across domains (use with caution).

```javascript
api.get('/protected', { credentials: 'include' })
  // ...
```

**Progress**: Utilize progress events for tracking upload or download progress.

**Example:**

```javascript
api.file('/uploads', file)
  .on('progress', (event) => {
    const progress = Math.round((event.loaded * 100) / event.total);
    console.log(`Upload progress: ${progress}%`);
  })
  // ...
```

## 3. Utility Methods:

The `FetchApi` class might provide additional utility methods for common tasks:

**formData(data)**: Creates a FormData object from a plain object for form data uploads.

```javascript
const formData = api.formData({ name: 'file', content: 'This is the file content' });
api.post('/upload', formData)
  // ...
```




**Method Breakdown:**

* **`useAuth(authName)`:** This static method allows you to set the authorization header name used in subsequent requests made through the `FetchApi` class.
* **`authName` (parameter):** This string parameter represents the desired name of the authorization header. It typically holds the access token or other authentication credentials.
* **`this.authName = authName`:** Inside the method, the provided `authName` is assigned to the class property `authName`. This property likely stores the authorization header name globally within the `FetchApi` class instance.

**Purpose:**

* The `useAuth` method offers customization for the authorization header name used in requests.
* By default, the class might have a pre-defined name (e.g., "accessToken").
* This method allows you to override that default and use a different header name according to your API's requirements.

**Example Usage:**

```javascript
const api = new FetchApi('https://api.example.com');

// Assuming your API uses "X-Auth-Token" as the authorization header name
api.useAuth('X-Auth-Token');

// Subsequent requests made through the `api` instance will use "X-Auth-Token" for authorization headers.
```


## Method `get`

**Purpose**

* Executes a GET request to a specified URL using the Fetch API.
* Constructs the request with appropriate options and calls an internal method for execution.

**Method Breakdown:**

1. **Parameters:**
   * `url`: The target URL for the GET request.
   * `data` (optional, defaults to {}): Data to be sent as query parameters (appended to the URL).
   * `options` (optional, defaults to {}): An object containing request configuration options.
2. **Setting Request Method:**
   * Explicitly sets the `method` property within the `options` object to "GET", ensuring a GET request is made.
3. **Calling Internal Method:**
   * Passes the `url`, `data`, and `options` to a method named `this.makeRequest` (implementation not provided).
   * Awaits the result of this internal method, likely handling the actual request execution.
   * Returns a Promise that resolves with the response data or rejects with an error.

**Key Points:**

* Encapsulates GET request logic for convenience.
* Likely builds upon a more generic request handling method (`this.makeRequest`).
* Returns a Promise for asynchronous handling.

**Example Usage:**

JavaScript

```
const api = new FetchApi('https://api.example.com');

api.get('/users')
  .then(data => {
    console.log('Received user data:', data);
  })
  .catch(error => {
    console.error('Error fetching data:', error);
  });
```

## Method `post`


This is very similar to the `get` method we discussed earlier. Here's a breakdown:

**Purpose:**

* Executes a POST request to a specified URL using the Fetch API.
* Constructs the request with appropriate options, including data in the request body, and calls an internal method for execution.

**Method Breakdown:**

1. **Parameters:**
   * `url`: The target URL for the POST request.
   * `data` (optional, defaults to {}): Data to be sent in the request body (typically as an object).
   * `options` (optional, defaults to {}): An object containing request configuration options.
2. **Setting Request Method:**
   * Explicitly sets the `method` property within the `options` object to "POST", ensuring a POST request is made.
3. **Calling Internal Method:**
   * Passes the `url`, `data`, and `options` to a method named `this.makeRequest` (implementation not provided). This method likely handles the actual request execution with the configured options.
   * Awaits the result of this internal method.
   * Returns a Promise that resolves with the response data or rejects with an error.

**Key Points:**

* Encapsulates POST request logic for convenience.
* Likely builds upon a more generic request handling method (`this.makeRequest`) that can handle different HTTP methods.
* The `data` parameter becomes the request body for the POST request.
* Returns a Promise for asynchronous handling.

**Example Usage:**

```javascript
const api = new FetchApi('https://api.example.com');

const newUserData = { name: 'John Doe', email: 'john.doe@example.com' };

api.post('/users', newUserData)
  .then(data => {
    console.log('User created successfully:', data);
  })
  .catch(error => {
    console.error('Error creating user:', error);
  });
```


## Method `put`

* **Purpose:** Executes a PUT request to a specified URL.
* **Parameters:**
  * `url`: Target URL for the request.
  * `data` (optional): Data to send in the request body (typically an object).
  * `options` (optional): Configuration options for the request.
* **Internal Method:** Calls `this.makeRequest` (not shown) to handle the actual request execution with the configured options.
* **Return Value:** Returns a Promise that resolves with the response data or rejects with an error.
  pen_spark

## Method `file`

* Uploads a file to a specified URL using the Fetch API.
* Handles necessary FormData setup and content type for file uploads.

1. **Parameters:**
   * `url`: The target URL for the file upload request.
   * `file`: The `File` object representing the file to be uploaded.
   * `name` (optional, defaults to "file"): The name of the file field in the form data.
   * `options` (optional): An object containing request configuration options.
2. **Creating FormData:**
   * Initializes a new `FormData` object, which is specifically designed for handling form-based data, including file uploads.
3. **Appending File Data:**
   * Uses `formData.append(name, file)` to add the provided file to the FormData object with the specified name.
4. **Setting Content Type Header:**
   * Sets the `Content-Type` header to "multipart/form-data", which is required for file uploads.
   * Merges with any existing headers provided in the options object.
5. **Calling Internal Method:**
   * Passes the `url`, `formData`, and `options` (with updated headers) to the `this.makeRequest` method for actual request execution.
   * Awaits the result of this internal method.
   * Returns a Promise that resolves with the response data or rejects with an error.

**Key Points:**

* Leverages FormData to correctly format file upload requests.
* Sets the appropriate content type header for file uploads.
* Uses the `makeRequest` method for underlying request handling.
* Returns a Promise for asynchronous handling.

**Example Usage:**

```javascript
const api = new FetchApi('https://api.example.com');
const myImage = document.getElementById('my-image').files[0]; // Get a File object from a file input

api.file('/uploads', myImage)
  .then(response => {
    console.log('File uploaded successfully:', response);
  })
  .catch(error => {
    console.error('Error uploading file:', error);
  });
```


## Method `patch`

* Executes a PATCH request to a specified URL using the Fetch API.
* Constructs the request with appropriate options, including data in the request body (if provided), and calls an internal method for execution.

1. **Parameters:**

   * `url`: The target URL for the PATCH request.
   * `data` (optional, defaults to {}): Data to send in the request body (typically as an object). This data specifies the partial modifications to be applied on the server.
   * `options` (optional, defaults to {}): An object containing request configuration options.
2. **Setting Request Method:**

   * Explicitly sets the `method` property within the `options` object to "PATCH", ensuring a PATCH request is made.
3. **Calling Internal Method:**

   * Passes the `url`, `data`, and `options` to a method named `this.makeRequest` (implementation not provided). This method likely handles the actual request execution with the configured options.
   * Awaits the result of this internal method.
   * Returns a Promise that resolves with the response data or rejects with an error.

   **Difference from PUT:**

   * While both `PUT` and `PATCH` can update data on the server, they have a key distinction:
     * `PUT` is used to replace an entire resource with the provided data.
     * `PATCH` is used to apply specific partial modifications to a resource.

   **Example Usage:**

   ```javascript
   const api = new FetchApi('https://api.example.com');

   const updatedUserData = { name: 'Jane Doe' }; // Update only the name

   api.patch('/users/123', updatedUserData)
     .then(data => {
       console.log('User partially updated successfully:', data);
     })
     .catch(error => {
       console.error('Error partially updating user:', error);
     });
   ```

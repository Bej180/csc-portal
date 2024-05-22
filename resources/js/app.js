import './bootstrap';
import toastr from 'toastr';
import 'toastr/build/toastr.css';
import ApexCharts from 'apexcharts';
import '../../public/js/storage.js';

import './angular/helpers.js';
import './request.js';
import '../../public/js/http';
import './angular/bootstrap.js';
import '../../public/js/location.js';
// import Echo from 'laravel-echo';
// import config from './config';
// import Pusher from 'pusher-js';

window.toastr = toastr;
window.ApexCharts = ApexCharts;

// const eventSource = new EventSource('/api/notifications/stream');

// eventSource.onmessage = (event) => {
//   const notification = JSON.parse(event.data);
//   // Handle notification data
//   console.log(notification.message); // Example: log notification message
//   // You can display notifications using libraries like toastr or custom UI elements. 
// };



// window.Echo = new Echo(config);


// window.Echo.channel('announcement').listen('AnnouncementEvent', (e) => {
//   // const messageElement = document.createElement('p');
//   // messageElement.textContent = e.message;
//   console.log(e.message)
//   //messages.appendChild(messageElement);
// });

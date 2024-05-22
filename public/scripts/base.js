import { Location } from "../js/extend.js";
import { load_html } from "./retrieve.js";


function updateTodoList(event) {
  const target = event.target;
  const id = target.value;
  
  if (id) {
    api('/todo/complete', {id})
      .then(data => console.log(data))
      .catch(error => console.error(error));
  }
}
window.updateTodoList = updateTodoList;



window.submitTodo = function(event) {
 
  if (this.todo) {
    api('/addTodo', {
      title: this.todo
    })
    .then(data => console.log(data))
    .catch(error=>console.log(error));
  }
}

function offOverlay(time) {
  setTimeout(() => {
    const overlay = id('overlay');
    if (overlay) {
      id('overlay').style.display = 'none';
    }
  }, time);
}
const layouts = document.querySelectorAll('.scrollable');

layouts.forEach(layout => {
  layout.addEventListener('scroll', function  (evt) {
    if (this.scrollTop === (this.scrollHeight - this.offsetHeight)) {
      evt.preventDefault();
    } else if (this.scrollTop === 0) {
      evt.preventDefault();
    } else {
      layouts.forEach(otherLayout => {
        if (otherLayout !== this) {
          otherLayout.scrollTop  = this.scrollTop;
        }
      });
    }
  });
});

document.querySelectorAll("form input[type=submit]").forEach((element) =>{
  element.addEventListener("click", () => {
    id('overlay').style.display = 'flex';
  });
});







// addEvent('.close-transcript-generator',function(e){
//   console.log(this);
// })


// window.addEventListener('resize', () => {
//   loadScroller();
//   alert(1);
// });

// window.onbeforeunload = ()=>{
//   onOverlay();
// }

// document.querySelectorAll('input[type=file][preview="previewImage"]').forEach((input) => {
//   const previewId = input.getAttribute('preview');
//   input.addEventListener('change', (e) => {
//     const image = e.target.files[0];
//     id(previewId).src = URL.createObjectURL(image);
//   });
// })


// // document.querySelectorAll("a[href]:not([href^='#']").forEach(element => {
// //   element.addEventListener("click", function(event){
// //     event.preventDefault();
// //     event.stopPropagation();

    
// //     const href = this.getAttribute('href');
// //     Location.load(href);
// //   }, true);
// // })




// loadScroller();
// offOverlay(1);

// // Create a MutationObserver instance
// var observer = new MutationObserver(function(mutations) {
//   mutations.forEach(function(mutation) {
//     // Check if nodes were added to the DOM
//     if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
//       loadScroller();
//     }
//   });
// });

// // Configure the observer to watch for changes in the DOM
// var observerConfig = {
//   childList: true,
//   subtree: true
// };

// Start observing the document
observer.observe(document.body, observerConfig);
alert('Dne')
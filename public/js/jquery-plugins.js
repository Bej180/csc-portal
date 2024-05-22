
$.fn.findScrollableParent = function() {
  var $element = $(this);
  var $parent = $element.parent();
  
  // If the element has no parent, or we've reached the window, return null
  if ($parent.length === 0 || $parent.is('body,html')) {
      return $(window);
  }
  
  // Check if the parent is scrollable
  var overflowY = $parent.css('overflow-y');
  if (overflowY === 'scroll' || overflowY === 'auto') {
      return $parent;
  }
  
  // Otherwise, continue checking the parent's parent
  return $parent.findScrollableParent();
}

$.fn.scrollToBottomIfNeeded = function() {
  var $element = $(this);
  var elementRect = $element[0].getBoundingClientRect();
  var win = $element.findScrollableParent(); 
  var pageYOffset = win.get(0).pageYOffset;
  var absoluteElementTop = elementRect.top + pageYOffset;
  var middle = absoluteElementTop - (win.innerHeight() / 2);
  middle += 100;

  if (middle > pageYOffset) {
      win.get(0).scrollTo({ top: middle, behavior: 'smooth' });
  }
}

$.addEvent = function (event, selector, callback) {
  $(document).on(event, function(e) {
    const elem = $(e.target);

    if (elem.is(selector)) {
      callback.apply(elem, e);
    }
  })
}


$.confirm = async function(content, obj) {
  if (typeof obj !== 'object' || obj == null) {
      obj = {};
  }
  obj = {accept: ()=>{}, reject: ()=>{}, acceptText: 'Accept', rejectText: 'Reject', title:'Confirm', type:'confirm', ...obj};
  
  const body = $("body");
  const confirmElement = $(`<div class="confirm-backdrop show"><div class="confirm-container confirm-center"><div aria-labelledby="confirm-title" aria-describedby="confirm-content" class="confirm-popup confirm-modal"
       tabindex="-1" role="dialog" aria-live="assertive" aria-modal="true"><div class="confirm-header"><h2 ng-if="title" class="confirm-title"></h2><button type="button" class="confirm-close confirm-dismiss" aria-label="Close this dialog">×</button></div><div class="confirm-content"><div id="confirm-content" class="confirm-html-container"></div></div><div class="confirm-actions"><button type="button" class="confirm-confirm btn btn-primary" aria-label="" style="display: inline-block;">Accept</button><button type="button" class="confirm-deny" aria-label="" style="display: none;">Deny</button>
          <button type="button" class="confirm-cancel confirm-dismiss btn btn-danger ml-1" aria-label="" style="display: inline-block;">Cancel</button></div></div></div>`);
  // $('.confirm-backdrop', body).remove();

  body.append(confirmElement);

  const confirmTitle = $(".confirm-title", confirmElement);
  const confirmDismiss = $(".confirm-dismiss", confirmElement);
  const confirmContent = $("#confirm-content", confirmElement);
  const confirmAccept = $(".confirm-confirm", confirmElement);
  const confirmDeny = $(".confirm-deny", confirmElement);

  

  confirmTitle.text(obj.title);
  confirmAccept.text(obj.acceptText);
  confirmDeny.text(obj.denyText);
  confirmContent.text(content);

  if (obj.type === 'alert') {
    $('.confirm-cancel', confirmElement).remove();
    confirmAccept.text('OK');
    $('.confirm-actions', confirmElement).addClass('justify-end');
  }
  
  confirmDismiss.on('click', function(){
      confirmElement.remove();
  });
  confirmAccept.on('click', function(){
      obj.accept.call(confirmElement);
      confirmElement.remove();
  });
  confirmDeny.on('click', function(){
      obj.deny.call(confirmElement);
      confirmElement.remove();
  });
  confirmElement.on('click',function (e) {
      e.preventDefault();
      e.stopPropagation();
      
      confirmElement.remove();
  });

};

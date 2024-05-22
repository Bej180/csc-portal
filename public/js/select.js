document.querySelectorAll('select').forEach(menu => {
  const options = menu.childNodes;
  const onSelect = menu.getAttribute('onSelect');
  const multiple = menu.hasAttribute('multiple');
  const placeholder = menu.getAttribute('placeholder');
  const drop = menu.getAttribute('drop') || 'left';
  let name = menu.getAttribute('name');


  const dropdown = document.createElement('div');
  dropdown.classList.add('dropdown');
  dropdown.classList.add(`drop-${drop}`);

  const input = document.createElement('input');
  
  input.setAttribute('type', 'hidden');
  if (name) {
    if (multiple) {
      name = `${name}[]`;
    }
    input.setAttribute('name', name);
  }
  
  const toggler = document.createElement('button');
  toggler.classList.add('dropdown-toggle');
  toggler.type = 'button';
  toggler.setAttribute('id', 'dropdownMenuButton');
  toggler.classList.add('input');
  toggler.setAttribute('aria-haspopup', 'true');
  toggler.setAttribute('aria-expanded', 'false');
  
  const togglerText = document.createElement('span');
  togglerText.classList.add('dropdown-text');
  togglerText.innerHTML = placeholder ? placeholder : 'Select';
  toggler.appendChild(togglerText);

  const caret = document.createElement('span');
  caret.classList.add('caret');
  toggler.appendChild(caret);

  dropdown.appendChild(toggler);

  const dropdownMenu = document.createElement('div');
  dropdownMenu.classList.add('dropdown-menu');
  dropdownMenu.setAttribute('aria-labelledby', 'dropdownMenuButton');

  for (let i = 0; i < options.length; i++) {
    if (options[i].nodeName === 'OPTION') {
      const item = document.createElement('a');
      item.classList.add('dropdown-item');
      item.href = '#';
      item.innerText = options[i].innerText;
      
      item.onclick = (event) => {
        if (onSelect) {
          const value = options[i].getAttribute('value');
          eval(onSelect + '("' + value + '")'); // Execute onSelect function with value
        }
        if (event.ctrlKey && multiple) {
              
        } else {
          togglerText.innerHTML = options[i].innerText; // Update button text with selected option
          dropdownMenu.querySelectorAll('.dropdown-item').forEach(item => {
            item.classList.remove('selected');
          });
          item.classList.add('selected');
          dropdownMenu.classList.remove('show');
        }
      };
      dropdownMenu.appendChild(item);
    }
  }

  dropdown.appendChild(dropdownMenu);

  toggler.addEventListener('click', () => {
    const dropdownRect = dropdownMenu.getBoundingClientRect();
    const viewportHeight = window.innerHeight;
    const togglerRect = toggler.getBoundingClientRect();
    

    if (dropdownRect.bottom > viewportHeight) {
      dropdown.classList.add('drop-top');
    } else {
      dropdown.classList.remove('drop-top');
    }

    dropdownMenu.classList.toggle('show');
  });

  menu.replaceWith(dropdown);
});




const listItems = document.querySelectorAll('.dropdown .dropdown-item');

document.addEventListener('keydown', (event) => {
  const opennedMenu = document.querySelector('.dropdown-menu.show');
  if (event.key === 'Escape') {
    const activeItems = document.querySelectorAll('.dropdown-menu.show');
    activeItems.forEach(element => {
      element.classList.remove('show');
      const hover = element.querySelector('.hover');
      if (hover) {
        hover.classList.remove('hover');
      }
    });
  }
  else if (['ArrowUp', 'ArrowDown'].includes(event.key) && opennedMenu) {

    const hovered = opennedMenu.querySelector('.dropdown-item.hover') || opennedMenu.firstElementChild;
    const prev = hovered.previousSibling || hovered.parentNode.lastElementChild;
    const next = hovered.nextSibling || hovered.parentNode.firstElementChild;
    
    
    hovered.classList.remove('hover');

    if (event.key === 'ArrowUp' && prev) {
      
      prev.classList.add('hover');
    }
    else if (event.key === 'ArrowDown' && next) {
      next.classList.add('hover')
    }
  }
  
  

  
});

listItems.forEach(item => {
  item.addEventListener('keydown', function(e){
    alert(e);
  })
})


document.querySelectorAll('.dropdown-item').forEach(item => {
  item.addEventListener('mouseenter', function() {
    this.classList.add('hover');
  });

  item.addEventListener('mouseleave', function() {
    this.classList.remove('hover');
  });
})

listItems.forEach(item => {
  item.addEventListener('click', (event) => {
    if (!event.ctrlKey) {
      const parentUl = item.parentNode;
      if (!event.shiftKey) {
        parentUl.querySelectorAll('li.selected').forEach(activeItem => {
          activeItem.classList.remove('selected');
        });
      }
      item.classList.toggle('selected');
    } else {
      item.classList.toggle('selected');
    }
  });
});

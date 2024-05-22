
$(function () {
    window.alert = function(text, header = 'Alert') {
      const alertWrapper = $('body').find('alert-wrapper');
      alertWrapper.remove();
      const html = `
        <div class="alert-wrapper">
          <div class="alert">
            <div class="alert-header">${header}</div>
            <div class="alert-header">${text}</div>
            <div class="alert-actions">
              <button class="btn btn-white btn-sm" type="button" data-dismiss="alert">Close</button>
            </div>
          </div>
        </div>`;
        $('body').append(html);
    }
    $.fn.onHover = function() {
      if ($(this).length > 0) {
        const dropdown = $(this).dropdown();
        $('.dropdown-menu.hover', dropdown).removeClass('hover');

        $(this).focus();
        $(this).addClass("hover");

      }
    }
    // $.fn.get = function(name, defaultValue = null) {
    //   return $(this).attr(name) || defaultValue;
    // };
    $.fn.dropdown = function() {
      if ($(this).is('.dropdown')) {
        return $(this);
      }
      return $(this).closest('.dropdown');
    };

    $.fn.dropdownItems = function(selector) {
      let dropdown = $(this).dropdown();
      return dropdown.find(`.dropdown-item${selector||''}`);
    };

    $.fn.updateDisplayingText = function () {
        const parent = $(this).dropdown();
        const items = parent.find('.dropdown-item.selected');
        const inputArea = parent.find('.inputArea');
        const dropdownText = parent.find('.dropdown-text');
        const placeholder = parent.get('data-placeholder', 'Select');
        const name = parent.attr('data-name');
       

        

        if (items.length == 0) {
          inputArea.empty();
          dropdownText.text(placeholder);

        }
        else if (/\[\]$/.test(name)) {
          let placeholders = '';
          
          for(let i = 0; i < items.length; i++) {
              const elem = items.eq(i);
              const key = elem.data('key');
              placeholders += `<span class="pill" data-selected="${key}">${elem.text()}<i class="drop-selection" data-drop-key="${key}"></i></span>`;
          }
          
          dropdownText.html(placeholders.length < 1 ? placeholder : placeholders);
        }
        else {
          items.first().addClass('selected');
          dropdownText.text(items.first().text());
        }
    };

    $.fn.updateInput = function() {
      const type = 'hidden';
      const parent = $(this).dropdown();
      const inputArea = $('.inputArea', parent);

      const key = $(this).data('key');
      const name = parent.data('name');
      const multiple = /\[\]$/.test(name);
      const value = $(this).data('value');
      const text = $(this).text();

      const dropdownText = $('.dropdown-text', parent);
      const pill = $(`span[data-selected=${key}]`, dropdownText)
      const placeholder = parent.get('data-placeholder', 'Select');
   
      
      let input = inputArea.find(`input[key="${key}"]`);
      const newInput = $('<input>').attr({ type, value, key, name });
      const newPill = `<span class="pill" data-selected="${key}">${text}<i class="drop-selection" data-drop-key="${key}"></i></span>`;

      
      if ($(this).is('.selected')) {
        // input.remove();
        // console.log(1);
        // pill.remove();
        // $(this).removeClass('selected');
      }
      else if (multiple === true) {
        inputArea.append(newInput);
        $(this).addClass('selected');
        dropdownText.append(newPill);
        console.log($(this), );
      }
      else {
        inputArea.empty();
        
        inputArea.append(newInput);
        $(this).addClass('selected');
        dropdownText.append(newPill);
      }

    };


    $.fn.updateSelectedValues = function() {
      if ($(this).is('.dropdown-item')) {
        const item = $(this);
        const parent = item.dropdown();
        const value = item.data('value');
        const inputArea = parent.find('.inputArea');
        const key = item.data('key')
        let input = inputArea.find(`input[key="${key}"]`);
        const name = parent.attr('data-name');
    
        const attrs = {
          type: 'hidden',
          value: value,
          key: key
        }
        if (name) {
          attrs.name = name;
        }
        
        if (!/^\[\]$/.test(name)) {
            inputArea.empty();
        }
        if (item.hasClass('selected')) {
          
            const input = $('<input>').attr(attrs);
            
           inputArea.append(input);
        }
        else if (input.length > 0) {
            input.remove();
        }
        
       
       // parent.updateDisplayingText();
      }
    };
    $(document).on('click', 'button[data-dismiss="alert"]', function(event) {
      event.preventDefault();
      const alertWrapper = $(this).parents('.alert-wrapper');
      alertWrapper.remove();
    })
    $("select.custom").each(function () {
        const options = $(this).children();
        const onSelect = $(this).attr("onSelect");
        let placeholder = $(this).attr("placeholder");
        let name = $(this).attr("name");

        let selectAttrs = Array.from($(this)[0].attributes);
        if (!placeholder) {
          const form = $(this).closest("form");
          const label = $(`label[for="${name}"]`, form);
          placeholder = 'Select';
          if (label.length > 0) {
            placeholder = label.text();
            label.remove();
          }
        }
        const drop = $(this).attr("drop") || "auto";
        const max = parseInt($(this).attr("max")||3);

        let relative = $(this).attr('relative');
        let multiple = /\[\]$/.test(name);
        

        const dropdown = $("<span>")
            .addClass("dropdown")
            .addClass(`drop-${drop}`);
            //dropdown.attr(selectAttrs);
        if (name) {
          dropdown.attr("data-name", name);
        }
        if (max > 0) {
          dropdown.attr("data-selection-max", max);
        }

        dropdown.attr('data-placeholder', placeholder);
        const inputArea = $("<span>");
        inputArea.addClass('inputArea');


        const dropdownText = $('<span>');
        dropdownText.addClass('dropdown-text');
        dropdownText.html(placeholder);

        const toggler = $("<button>")
            .addClass("dropdown-toggle input")
            .attr({
                type: "button",
                id: "dropdownMenuButton",
                "aria-haspopup": "true",
                "aria-expanded": "false",
            })
            .append(dropdownText)
            .append($("<span>").addClass("caret"));
        
        const input = $('<input>').attr({type: 'hidden', name: name});
        if (!multiple) {
            inputArea.append(input);
        }

        const dropdownMenu = $("<span>")
            .addClass("dropdown-menu")
            .attr("aria-labelledby", "dropdownMenuButton");

        if (relative) {
          dropdownMenu.attr({relative});
        }

       

        
        const header = $('<span>');
        header.addClass('dropdown-header');
        header.text(placeholder);
        const dropdownBody = $('<span>').addClass("dropdown-body");
        
        dropdownMenu.append(header);
        options.each(function (i) {
          if (this.nodeName === "OPTION" && $(this).val().trim().length > 0) {
            
           
            const item = $("<a>")
                    .addClass("dropdown-item")
                    .attr("href", "#")
                    .text($(this).text())
                    .attr('data-key', i)
                    .attr('data-value', $(this).val());

                item.click(function (event) {
                  var value = $(this).data('value');
                  

                  event.preventDefault();
                  
                    if (onSelect) {
                      try {
                        eval(onSelect + '("' + value + '")');
                      } catch(e) {}
                    }

                    if (multiple && value) { 
                     
                          const parent = $(this).dropdown();
                          const max = parent.data('selection-max');

                          if (!event.ctrlKey && !event.metaKey) {
                            parent.removeClass("show");
                          }
                          
                          if (item.is('.selected')) {
                            item.removeClass('selected');
                          }
                          else if (max && parent.dropdownItems('.selected').length >= max) {
                            alert('The maximum number of items to be selected is '+max);
                          }
                          else {
                            //item.addClass('selected'();
                          }
                          //item.updateSelectedValues(); 
                          item.updateInput();
                     

                    } else {
                      
                        dropdown.removeClass("show");
                        
                        inputArea.empty();
                        inputArea.append($('<input>').attr({
                          name: name, 
                          value: value,
                          type: 'hidden',
                        }));
                        
                        if ($(this).hasClass('selected')) {
                          input.val('');
                          $(this).removeClass('selected')
                          return;
                        }

                        toggler.find(".dropdown-text").html($(this).text());
                        input.val($(this).attr('data-value'));
                        dropdownMenu
                            .find(".dropdown-item")
                            .removeClass("selected");
                        $(this).addClass("selected");
                    }
                });

                dropdownBody.append(item);
            }
        });
        dropdownMenu.append(dropdownBody);
        
        
        
        if (multiple) {
          dropdownMenu.append($('<marquee>Ctrl to select more</marquee>').addClass('dropdown-footer'));
        }
        
        dropdown.append(inputArea, toggler, dropdownMenu);

        toggler.on('click',function (event) {
            
            if ($(event.target).is('.drop-selection')) {
              return;
            }


            

            const relative = dropdownMenu.attr('relative');
            
            if (relative) {
              const isVisible = dropdown.hasClass('show');
              const dropdownBody = $('.dropdown-body', dropdown);
              if (!isVisible) {
                dropdown.addClass('show');
              }

              const dropdownRect = dropdownMenu[0].getBoundingClientRect();
              const togglerParent = dropdownMenu.closest(relative);
              
              if (!isVisible) {
                dropdown.removeClass('show');
              }

              dropdownBody.click();



              const class_list = dropdown.attr('class');
              const drop = dropdown.attr('drop');
              const match = class_list.match(/\bdrop-(top|bottom)(-(left|right))?\b/);

              const togglerRect = togglerParent.length > 0 ? togglerParent[0].getBoundingClientRect() : false;
              
              if (!drop && togglerParent && !match) {
                
                  let x = 'left';
                  let y = 'bottom';

                  
                  if (dropdownRect.right > togglerRect.right) {
                    x = 'right';
                  }
                  else if (dropdownRect.right < togglerRect.right ) {
                    x = 'left'
                    
                  }
                  else if (dropdownRect.left < togglerRect.left) {
                    x = 'left';
                    
                    console.log({name:'third',drop: dropdownRect.right, cont: togglerRect.right})
                  }


                  if (dropdownRect.bottom > togglerRect.bottom) {
                    y = 'top';
                  }
                  else if (dropdownRect.bottom < togglerRect.bottom) {
                    y = 'bottom';
                  }
                  else if (dropdownRect.top < togglerRect.top) {
                    y = 'bottom';
                  }
                  else if (dropdownRect.top > togglerRect.top) {
                    y = 'top';
                  }
                  const newClass = `drop-${y}-${x}`;
                  
                  if (drop) {
                    dropdown.addClass(drop).removeClass(all);
                    
                  }
                  dropdown.removeClass(all).addClass(newClass);
                  dropdown.attr('drop', newClass);
                  
                  
                }
                console.log(dropdownRect.right, togglerRect.right);
            }
            $('.dropdown.show').not(dropdown).removeClass('show');
            
            dropdown.toggleClass("show");
            
        });

        $(this).replaceWith(dropdown);
    });

    $(document).on('click', '.drop-selection', function(e) {
      e.preventDefault();
      const dropdown = $(this).closest('.dropdown');
      const dropKey = $(this).data('drop-key');
      
      const placeholder = dropdown.data('placeholder') || 'Select';
      const dropdownText = dropdown.find('.dropdown-text');
      const inputItem = dropdown.find(`input[key=${dropKey}]`);
      const textItem = $(this).parent(`.pill[data-selected=${dropKey}]`);
      const dropdownItem = dropdown.find(`.dropdown-item[data-key=${dropKey}]`);

      
      textItem.remove();
      inputItem.remove();
      dropdownItem.removeClass('selected');

      const text = dropdownText.text().trim();

      if (text.length < 1) {
        dropdownText.text(placeholder);
      }


    })

    $(document).on('click', '.dropdown-item', function(e) {
      e.preventDefault();
    });

    $(document).on('keydown', function (event) {
        const elem = $('.dropdown.show .dropdown-toggle');



        
        
        if (elem.length > 0) {
          const dropdown = elem.dropdown();
          const dropdownBody = $('.dropdown-body', dropdown);
          const hovered = dropdown.attr('hovered');
          const name = dropdown.data('name');

          if (["ArrowUp", "ArrowDown"].includes(event.key)) {
            

              const current = $('.dropdown-item.hover', dropdown);
              const first = $('.dropdown-item:first', dropdown);
              const last = $('.dropdown-item:last', dropdown);

              current.removeClass("hover");
              const items = $('.dropdown-item', dropdownBody);
            

              if (!hovered) {
                dropdown.attr('hovered', true);

                switch(event.key) {
                  case 'ArrowUp':
                    last.onHover();
                    break;

                  case 'ArrowDown':
                    first.onHover();
                    
                    break;
                }

              }
              else {
                const height = dropdownBody.innerHeight();
                const scrollTop = dropdownBody[0].scrollTop;
                
                switch(event.key) {
                  case 'ArrowUp':
                    
                   
                    if (current.is($(':first', dropdownBody))) {
                      last.onHover();
                    }
                    else {
                      
                      current.prev().onHover();
                    }
                    break;
                  case 'ArrowDown':
                    
                    if (current.is($(':last', dropdownBody))) {
                      first.onHover()
                    }
                    else {
                      
                      current.next().onHover();
                    }
                    break;


                }
              }
           
          }
          else if (event.key === 'Enter') {
            const hover = dropdown.find('.dropdown-item.hover');
            
            if (!/\[\]$/.test(name)) {
              $('.dropdown-item.selected', dropdown).removeClass('selected');
              hover.addClass('selected');
              dropdown.removeClass('show');
            }
            else if (!event.ctrlKey) {
              hover.addClass('selected');
              //$('.dropdown-item:first', dropdownBody).onHover();
              dropdown.removeClass('show');
            }
            
            if (hover.length > 0) {
              hover.first().updateSelectedValues();
            }
          }
        }
        if (event.key === 'Escape') {
          // parent.removeClass("show").find(".hover").removeClass("hover");
          const alertWrapper = $('.alert-wrapper');
          alertWrapper.remove();
        }
    });


    $(document).on("mouseenter", ".dropdown-item", function () {
        const dropdown = $(this).dropdown();
        $('.dropdown-item.hover', dropdown).removeClass('hover');
        $(this).onHover();
        
    });

    $(document).on("mouseleave", ".dropdown-item", function () {
     
        $(this).removeClass("hover");
    });

    $(document).on("mouseenter", ".dropdown li", function (event) {
        if (!event.ctrlKey) {
            const parentUl = $(this).parent();
            if (!event.shiftKey) {
                parentUl.find("li.selected").removeClass("selected");
            }
            $(this).toggleClass("selected");
        }
    });

    $(document).on("mouseleave", ".dropdown li", function (event) {
        $(this).toggleClass("selected");
    });
    
 

  $(document).click(function(event) {
    //setTimeout(function() { 
    if (!$(event.target).closest('.dropdown').length && !$(event.target).is('.dropdown-toggle')) {
        $('.dropdown').removeClass('show');
      }
    //}, 500);
  });
});
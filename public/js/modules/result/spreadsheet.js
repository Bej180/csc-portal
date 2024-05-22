$(document).ready(function() {
  // Initial grid dimensions
  var numRows = 10;
  var numCols = 10;

  // Generate initial grid
  generateGrid(numRows, numCols);

  // Add click event listeners to rows and columns
  $(document).on('click', '.horizontal-entry', function(event) {
    const parent = $(this).parent('tr');
    const row = $(this).attr('row');
    const col = $(this).attr('col');

    $('[row], [col]').removeClass('active-cell');
    
    $(`[row=${row}]`).toggleClass('active-cell');
    $('td.clicked').removeClass('clicked');
    

    if (event.ctrlKey) {

    }
    else {
      $('tr').removeClass('highlighted');
      parent.toggleClass('highlighted');
    }    
  });
  $(document).on('contextmenu', '.horizontal-entry', function(event){
    event.preventDefault();
    
    const parent = $(this).closest('.excelGrid');
    const offset = $(this).offset();
    
    parent.addClass('show-h-menu');
    const height = $('.cell-h-menu', parent).innerHeight() / 2;
    const bounds = $('.cell-h-menu', parent)[0].getBoundingClientRect();
    const dataY = bounds.top + bounds.height;
    const parentHeight = parent.innerHeight();

    if (dataY <= parentHeight) {
      $('.cell-h-menu', parent).css({
        top: `${offset.top - height}px`,
        bottom: 'auto'
      });
    }
    else {
      $('.cell-h-menu', parent).css({
        top: 'auto',
        bottom: '0px'
      });
    }
  });

  $(document).on('click', function(e) {
    $('.excelGrid.show-h-menu').removeClass('show-h-menu');
  })

  $(document).on('click', '.vertical-entry', function(event) {
    const parent = $(this).parent('tr');
    const row = $(this).attr('row');
    const col = $(this).attr('col');

    $('[row], [col]').removeClass('active-cell');
    
    $(`[col=${col}]`).toggleClass('active-cell');

    $('td.clicked').removeClass('clicked');
    

    if (event.ctrlKey) {

    }
    else {
      $('tr').removeClass('highlighted');
      parent.toggleClass('highlighted');
    }    
  });



  $(document).on('blur', 'td[contenteditable]', function(event) {
    $(this).removeAttr('contenteditable');
  });
  
  $(document).on('click', 'td:not(.horizontal-entry)', function(event) {

    const elem = $(this);
    const parent = elem.parent('tr');
    const table = elem.closest('table.excelGrid')
    $('td.clicked').removeClass('clicked');
    $('tr.highlighted').removeClass('highlighted');
    $('tr.active-row').removeClass('active-row');
    
    const col = elem.attr('col');
    const row = elem.attr('row');
    $('[row=0], [col=0]').removeClass('active-cell');

    $(`[row=0][col=${col}]`).addClass('active-cell');
    $('td.active-cell').removeClass('active-cell');
    $(`[col=0][row=${row}]`).addClass('active-cell');

    
    
    elem.addClass('clicked');
    
    
  });
  $(window).on('keydown', function(e){
    const elem = $('td.clicked');
    
    const col = parseInt(elem.attr('col'));
    const row = parseInt(elem.attr('row'));

    if (e.key === 'Tab') {
      
      const next = $(`td[row=${row}][col=${col+1}]`);
      if (next.length > 0) {
        elem.removeClass('clicked');
        next.addClass('clicked');
      }
    }
    else if (e.key === 'Enter') {
      const below = $(`td[row=${row+1}][col=${col}]`);
      if (below.length > 0) {
        elem.removeClass('clicked');
        below.addClass('clicked');
      }
    }
  });
  $(document).on('dblclick', 'td,th', function() {
    $(this).attr('contenteditable', true).focus();

    $(this).on('keydown', function(e){
      if (e.ctrlKey) {
      }
      else if (e.key === 'Enter') {
        //alert(e.ctrlKey)
        e.preventDefault();
      }
      
    });


  });

 

  $('.vertical-entry:after').each(function(){
      ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        $(this).on(eventName, preventDefaults);
      });

      ['dragenter', 'dragover'].forEach(eventName => {
        // $(this).on(eventName, highlight);
      });

      ['dragleave', 'drop'].forEach(eventName => {
        // $(this).on(eventName, unhighlight);
      });

      function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
      }
  })

  // Function to generate initial grid
  function generateGrid(cols) {
    cols++;
    var container = $('#excelGrid');
    const table = $('<table>');
    
    const thead = $('<thead>');
    const tbody = $('<tbody>');
    const days = ['Mon','Tue','Wed','Thu','Fri','Sat'];
    let rows = days.length + 1;

    for (var i = 0; i < rows; i++) {
      if (i === 0) {
        var rowHead = $('<tr>');
        for (var j = 0; j < cols; j++) {
          var cell;
          
            cell = $('<th>').addClass('vertical-entry').text(j === 0 ? '' : 'C' + j);
            if (j > 0) {
              cell.append($('<span>').addClass('vertical-after'));
            }
          cell.attr({row:i, col:j, tabindex:0});
          rowHead.append(cell);
        }
        thead.append(rowHead);
        table.append(thead);
      }
      else {
        
        
        var rowBody = $('<tr>');
        for (var j = 0; j < cols; j++) {
          var cell;
          
         
          
          if (j === 0) {
            cell = $('<td></td>').addClass('horizontal-entry').text(i === 0 ? '' : days[i-1]);
          } else {
            cell = $('<td></td>').text('');
          }
          cell.attr({row:i, col:j, tabindex:0});
          rowBody.append(cell);
        }
        tbody.append(rowBody);
        table.append(tbody);
      }
    }

    const horizontalMenu = $('<ul>').addClass('cell-menu').addClass('cell-h-menu');
    const insertRowBefore = $('<li>').text('Insert Before');
    const insertRowAfter = $('<li>').text('Insert After');
    const removeRow = $('<li>').text('Remove Row');
    const moveRowUp = $('<li>').text('Move Up');
    const moveRowDown = $('<li>').text('Move Down');
    horizontalMenu.append(insertRowBefore).append(insertRowAfter).append(removeRow).append(moveRowUp).append(moveRowDown);
    


    const verticalMenu = $('<ul>').addClass('cell-menu').addClass('cell-v-menu');
    const insertColBefore = $('<li>').text('Insert Before');
    const insertColAfter = $('<li>').text('Insert After');
    const removeCol = $('<li>').text('Remove Column');
    const moveColLeft = $('<li>').text('Move Left');
    const moveColRight = $('<li>').text('Move Right');
    verticalMenu.append(insertColBefore).append(insertColAfter).append(removeCol).append(moveColLeft).append(moveColRight);
    
    container.addClass('relative');
    container.append(horizontalMenu);
    container.append(verticalMenu);
    container.append(table);
    
  }
});
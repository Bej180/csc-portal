/**
 * handleContextMenuAction
 * Performs the appropriate action based on the context menu action triggered.
 * @param {string} action - The context menu action triggered.
 */
function handleContextMenuAction(action) {
    var rowIndex = parseInt(document.getElementById('context-menu').dataset.rowIndex);
    var colIndex = parseInt(document.getElementById('context-menu').dataset.colIndex);
    var table = document.getElementById('students-table');
    var rows = table.getElementsByTagName('tr');
    var selectedRow = rows[rowIndex];

    switch(action) {
        case 'insert-row-above':
            insertRow(rowIndex);
            break;
        case 'insert-row-below':
            insertRow(rowIndex + 1);
            break;
        case 'insert-column-before':
            insertColumn(colIndex);
            break;
        case 'insert-column-after':
            insertColumn(colIndex + 1);
            break;
        case 'duplicate-row':
            var newRow = selectedRow.cloneNode(true);
            table.insertBefore(newRow, rows[rowIndex + 1]);
            break;
        case 'duplicate-column':
            duplicateColumn(colIndex);
            break;
        case 'clear-row':
            // Clear the content of cells in the selected row
            for (var i = 1; i < selectedRow.cells.length; i++) {
                selectedRow.cells[i].textContent = '';
            }
            break;
        case 'clear-column':
            // Clear the content of cells in the selected column
            for (var i = 0; i < rows.length; i++) {
                rows[i].cells[colIndex].textContent = '';
            }
            break;
        case 'move-up':
            if (rowIndex > 1) {
                table.insertBefore(selectedRow, rows[rowIndex - 1]);
            }
            break;
        case 'move-down':
            if (rowIndex < rows.length - 1) {
                table.insertBefore(selectedRow, rows[rowIndex + 2]);
            }
            break;
        case 'move-left':
            if (colIndex > 1) {
                moveColumn(colIndex, colIndex - 1);
            }
            break;
        case 'move-right':
            if (colIndex < selectedRow.cells.length - 1) {
                moveColumn(colIndex, colIndex + 1);
            }
            break;
        case 'delete-row':
            deleteRow(rowIndex);
            break;
        case 'delete-column':
            deleteColumn(colIndex);
            break;
    }
    
    // Hide the context menu after action
    document.getElementById('context-menu').style.display = 'none';
}

// List of Excel actions
const excel_actions = [
    'insert-row-above',
    'insert-row-below',
    'insert-column-before',
    'insert-colunn-after', // Typo: 'insert-column-after'
    'duplicate-row',
    'clear-row',
    'clear-column',
    'move-up',
    'move-down',
    'move-left',
    'move-right',
    'delete-row',
    'delete-column'
];

// Attach event listeners to each context menu action
excel_actions.forEach(action => {
    document.getElementById(action).addEventListener('click', () => handleContextMenuAction(action));
});

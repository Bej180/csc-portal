

$(function(){
  $(document).on('click', '[data-table-export]', function(e) {
    alert(1);
  })
})
$.fn.exportTable = function(tableSelector, format = 'xlsx') {
  this.alert(1);
  return;
  const table = $(tableSelector);
  const exporter = new TableExport(table.get(0));
  exporter.export(format);

this.on('click', function(e) {
  exporter.onClick();
});

}
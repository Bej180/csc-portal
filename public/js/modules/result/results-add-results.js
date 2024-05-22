function typeScore(event) {
  var maxLength = 2;
  var text = event.target.innerText;
  var type = event.target.getAttribute('data-type');
  
  
  
  
  var sanitizedText = text.replace(/\D/g, '');
  if (sanitizedText.length > maxLength) {
    sanitizedText = sanitizedText.slice(0, maxLength);
  }
  event.target.innerText = sanitizedText;
  const int = parseInt(sanitizedText);
 
  this[type] = Nan(int) ? '' : int;

  this.total = parseInt(this.lab||0) + parseInt(this.test||0) + parseInt(this.exam||0);

  
  switch(true) {
    case this.total >= 70: this.grade = 'A';break;
    case this.total >= 60: this.grade = 'B';break;
    case this.total >= 50: this.grade = 'C';break;
    case this.total >= 45: this.grade = 'D';break;
    case this.total >= 40: this.grade = 'E';break;
    default: this.grade = 'F';
  }
  this.remark = this.grade === 'F' ? 'FAILED':'PASSED';

  // Restore cursor position
  var range = document.createRange();
  var sel = window.getSelection();
  if (sanitizedText.length > 0) {

    range.setStart(event.target.childNodes[0], sanitizedText.length);
    range.collapse(true);
    sel.removeAllRanges();
    sel.addRange(range);
  }

}
window.typeScore = typeScore;

function saveCursorPosition(event) {
  event.target.dataset.selectionStart = event.target.selectionStart;
  event.target.dataset.selectionEnd = event.target.selectionEnd;
}

function submitData() {
  var studentsData = [];

  document.querySelectorAll('#spreadsheet table tbody tr').forEach(row => {

    var student = {
      reg_no: row.cells[2].textContent,
      lab: row.cells[4].textContent,
      test: row.cells[5].textContent,
      exam: row.cells[6].textContent,
      score: row.cells[7].textContent
    };
    studentsData.push(student);
  })

  api('/save-results', {
    results: studentsData,
    course_id: document.getElementById('spreadsheet').getAttribute('data-course'),
  })
  .then(resp => {
    console.log(resp);
  })
  .catch(error => console.log(error));
}

document.querySelector('#submitResult').addEventListener('click', submitData);


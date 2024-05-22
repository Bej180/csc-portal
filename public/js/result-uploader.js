async function convertAndSave() {
  const fileInput = document.getElementById('fileInput');
  const file = fileInput.files[0];

  if (!file) {
    alert("Please select a file.");
    return;
  }

  try {
    // Read file content
    const fileContent = await readFileAsync(file);

    let csvData;

    // Convert PDF to CSV if file is a PDF

    if (file.type === 'application/pdf') {
      const pdfDoc = await PDFLib.PDFDocument.load(fileContent);

      let pdfText = '';
      for (let i = 0; i < pdfDoc.getPageCount(); i++) {
        const page = await pdfDoc.getPage(i);
        pdfText += await page.getText();
      }
      csvData = Papa.parse(pdfText).data;
    } else if (['application/vnd.openxmlformats-officedocument.spreadsheet', 'application/vnd.openxmlformats-officedocument.spreadsheet.sheet'].includes(file.type)) {

      return;
    }

    const response = await api('/save-result', {result: csvData});

  } catch(e) {
    console.error('Error: ',e.message);
    alert('An error occurred while saving');

  }

}

function readFileAsync(file) {
  return new Promise((resolve, reject) => {
   const reader = new FileReader();
   reader.onload = () => resolve(reader.result);
   reader.onerror = () => reject;
   reader.readAsArrayBuffer(file); 
  });
}
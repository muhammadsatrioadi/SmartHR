const fs = require('fs');
const pdf = require('pdf-parse');

const pdfPath = 'docs/Blueprint Pilar Kel.9-1.pdf';

// Baca file PDF
const dataBuffer = fs.readFileSync(pdfPath);

// Parse PDF
pdf(dataBuffer).then(function(data) {
    // Tampilkan teks yang diekstrak
    console.log('=== Teks dari PDF ===');
    console.log(data.text);
}).catch(function(error) {
    console.error('Error membaca PDF:', error);
});
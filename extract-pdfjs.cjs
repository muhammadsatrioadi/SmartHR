const fs = require('fs');
const pdfjsLib = require('pdfjs-dist/legacy/build/pdf.js');

// Atur path worker untuk pdfjs-dist legacy
pdfjsLib.GlobalWorkerOptions.workerSrc = require.resolve('pdfjs-dist/legacy/build/pdf.worker.min.js');

const pdfPath = 'docs/Blueprint Pilar Kel.9-1.pdf';

async function extractTextFromPDF() {
    try {
        // Baca file PDF sebagai buffer
        const dataBuffer = fs.readFileSync(pdfPath);
        const typedArray = new Uint8Array(dataBuffer);
        
        // Load PDF
        const pdf = await pdfjsLib.getDocument({ data: typedArray }).promise;
        let fullText = '';
        
        // Loop setiap halaman
        for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
            const page = await pdf.getPage(pageNum);
            const textContent = await page.getTextContent();
            const pageText = textContent.items.map(item => item.str).join(' ');
            fullText += `=== Halaman ${pageNum} ===\n${pageText}\n\n`;
        }
        
        // Tampilkan teks
        console.log(fullText);
        
        // Simpan ke file teks
        fs.writeFileSync('extracted-text.txt', fullText, 'utf8');
        console.log('Teks berhasil disimpan ke extracted-text.txt');
    } catch (error) {
        console.error('Error membaca PDF:', error);
    }
}

extractTextFromPDF();
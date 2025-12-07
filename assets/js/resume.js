function generatePDF() {
    const element = document.getElementById('doc-target');
    const filename = element.getAttribute('data-filename') || 'Mon_CV.pdf';
    // Objet pour définir le PDF
    const opt = {
        margin:       0,
        filename:     filename,
        image:        { type: 'jpeg', quality: 0.98 },
        // Trasnformation en HTML puis PDF
        html2canvas:  { scale: 2 },
        jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
    };
    // Appliquer les options
    html2pdf().set(opt).from(element).save();
}
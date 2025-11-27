window.jsPDF = window.jspdf.jsPDF;
function generatePdf() {
    const htmlElement = document.getElementById('doc-target');

    if (!htmlElement) {
        console.error("Element #doc-target introuvable");
        return;
    }

    // version simple et compatible
    const pdf = new jsPDF('p', 'pt', 'letter');

    pdf.html(htmlElement, {
        callback: function (doc) {
            doc.save("Test.pdf");
        },
        margin: [20, 20, 20, 20],
        html2canvas: {
            scale: 2,
        }
    });
}
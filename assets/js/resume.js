window.jsPDF = window.jspdf.jsPDF;
function generatePdf() {
    const htmlElement = document.getElementById('doc-target');

    if (!htmlElement) {
        console.error("Element #doc-target introuvable");
        return;
    }

    html2canvas(htmlElement, {
        scale: 2,
        useCORS: true,
        allowTaint: false
    }).then(canvas => {

        const imgData = canvas.toDataURL("image/png");

        const pdf = new jsPDF({
            orientation: "p",
            unit: "px",
            format: [canvas.width, canvas.height]
        });

        pdf.addImage(imgData, "PNG", 0, 0, canvas.width, canvas.height);

        pdf.save("CV.pdf");
    }).catch(err => {
        console.error("HTML2Canvas Error:", err);
    });
}
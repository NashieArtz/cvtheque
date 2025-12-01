window.generatePDF = function () {
    const element = document.getElementById('doc-target');

    if (!element) {
        console.error("❌ #doc-target introuvable !");
        return;
    }

    // Appliquer mode PDF
    element.classList.add("pdf-mode");

    const options = {
        margin: 0,
        filename: 'cv.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: {
            scale: 2,
            useCORS: true,
            scrollY: 0
        },
        jsPDF: {
            unit: "pt",
            format: "a4",
            orientation: "portrait"
        },
        pagebreak: { mode: ['css', 'legacy'] }
    };

    html2pdf()
        .set(options)
        .from(element)
        .save()
        .then(() => {
            // Retirer le mode PDF après la génération
            element.classList.remove("pdf-mode");
        });
};
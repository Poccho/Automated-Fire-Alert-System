// Function to export to PDF
function exportToPDF() {
  // Remove the event listener to prevent multiple clicks
  const exportButton = document.getElementById("exportButton");
  exportButton.removeEventListener("click", exportToPDF);

  // Get the chart container elements
  const pieChartContainer = document.getElementById("barangayPieChart");
  const areaChartContainer = document.getElementById("barangayAreaChart");

  // Create an array of promises to capture each chart as an image
  const promises = [
    domtoimage.toPng(pieChartContainer),
    domtoimage.toPng(areaChartContainer),
  ];

  // Resolve promises and create an array of images
  Promise.all(promises).then((images) => {
    // Create a PDF document definition with the images
    const docDefinition = {
      content: images.map((image) => ({ image, width: 500 })),
    };

    // Use pdfmake to generate and download the PDF
    pdfMake.createPdf(docDefinition).download("Charts.pdf");
  });
}

// Wait for the DOM to be fully loaded
document.addEventListener("DOMContentLoaded", () => {
  // Add an event listener to the export button
  const exportButton = document.getElementById("exportButton");
  exportButton.addEventListener("click", exportToPDF);
});

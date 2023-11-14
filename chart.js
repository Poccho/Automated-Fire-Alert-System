// Assuming you have data for barangays in Gensan
var barangayData = {
  labels: ["Barangay1", "Barangay2", "Barangay3", "Barangay4", "Barangay5"],
  datasets: [
    {
      data: [30, 20, 15, 10, 25], // You should replace these values with the actual data
      backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56", "#4CAF50", "#9966FF"],
    },
  ],
};

// Get the canvas element
var ctx = document.getElementById("barangayPieChart").getContext("2d");

// Create a pie chart
var myPieChart = new Chart(ctx, {
  type: "pie",
  data: barangayData,
  options: {
    title: {
      display: true,
      text: "Barangays in Gensan",
    },
    plugins: {
      datalabels: {
        color: "#fff", // Set label text color
        font: {
          size: 12, // Set label font size
        },
        formatter: function (value, context) {
          return context.chart.data.labels[context.dataIndex];
        },
      },
    },
    legend: {
      position: "top",
      align: "start",
    },
  },
});

// Function to check if data is empty
function isDataEmpty(data) {
  return data.datasets.length === 0;
}

// Preprocess data to handle zero values
function preprocessData(data) {
  for (var i = 0; i < data.datasets.length; i++) {
    var dataset = data.datasets[i];
    var dataPoints = dataset.data;

    // Find first non-zero value and set previous values to zero
    var startIndex = dataPoints.findIndex((value) => value !== 0);
    for (var j = 0; j < startIndex; j++) {
      dataPoints[j] = 0;
    }

    // Find last non-zero value and set subsequent values to zero
    var lastIndex = dataPoints
      .slice()
      .reverse()
      .findIndex((value) => value !== 0);
    lastIndex = dataPoints.length - 1 - lastIndex;
    for (var k = lastIndex + 1; k < dataPoints.length; k++) {
      dataPoints[k] = 0;
    }
  }
  return data;
}

if (isDataEmpty(fireOccurrencesData)) {
  // If data is empty, display message
  document.getElementById("linechart").innerHTML =
    "<h1 style='margin-top:25vh; margin-left:20vw;'>No Records Saved</h1>";
} else {
  // If data is not empty, preprocess and create the chart
  fireOccurrencesData = preprocessData(fireOccurrencesData);

  var ctx = document.getElementById("fireOccurrencesChart").getContext("2d");
  var fireOccurrencesChart = new Chart(ctx, {
    type: "line",
    data: fireOccurrencesData,
    options: {
      responsive: true, // Set responsive to true
      maintainAspectRatio: false, // Ensure chart maintains aspect ratio
      plugins: {
        title: {
          display: true,
          text: "Fire Occurrences per Month per Barangay in General Santos City",
        },
        legend: {
          display: true, // Ensure the legend is displayed
          position: 'top', // You can adjust the position ('top', 'bottom', 'left', 'right')
        },
        colorschemes: {
          scheme: "brewer.Paired12", // Use a color scheme with 12 distinct colors
        },
      },
      scales: {
        x: {
          title: {
            display: true,
            text: "Months", // Label for x-axis
          },
          ticks: {
            maxRotation: 0,
            autoSkip: false,
            callback: function(value, index, values) {
              // Assuming the labels are 1-12 for the months
              return ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'][value - 0] || '';
            }
          }
        },
        y: {
          title: {
            display: true,
            text: "No. of Incidents", // Label for y-axis
          },
          ticks: {
            beginAtZero: true,
            stepSize: 1, // Ensure y-axis labels are whole numbers
            precision: 0 // Display only whole numbers
          },
        },
      },
      elements: {
        line: {
          tension: 0.4, // Adjust tension for smoother lines
        },
      },
    },
  });
}

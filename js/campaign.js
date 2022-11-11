import header, { notificationsOpen } from "./imports/header.js";
import logout from "./imports/menu.js";
import { notificationsClose, markReadNotification } from "./imports/notifications.js";

// const ctx = document.getElementById("staticGraph").getContext("2d");

// const myChart = new Chart(ctx, {
//   type: "bar",
//   data: {
//     labels: [
//       "Sprint 1",
//       "Sprint 2",
//       "Sprint 3",
//       "Sprint 4",
//       "Sprint 5",
//       "Sprint 6",
//       "Sprint 7",
//       "Sprint 8",
//       "Sprint 9",
//     ],
//     datasets: [
//       {
//         label: "Sprints",
//         data: [50, 250, 1250, 6250, 31250, 156250, 781250, 3906250, 19531250],
//         backgroundColor: [
//           "rgba(255, 99, 132, 1.0)",
//           "rgba(54, 162, 235, 1)",
//           "rgba(255, 206, 86, 1)",
//           "rgba(75, 192, 192, 1)",
//           "rgba(153, 102, 255, 1)",
//           "rgba(255, 159, 64, 1)",
//         ],
//         borderColor: [
//           "rgba(255, 99, 132, 1)",
//           "rgba(54, 162, 235, 1)",
//           "rgba(255, 206, 86, 1)",
//           "rgba(75, 192, 192, 1)",
//           "rgba(153, 102, 255, 1)",
//           "rgba(255, 159, 64, 1)",
//         ],
//         borderWidth: 2,
//       },
//     ],
//   },
//   options: {
//     responsive: true,
//     plugins: {
//       title: {
//         display: true,
//         text: "Sprint Information",
//       },
//     },
//     scales: {
//       y: {
//         ticks: {
//           beginAtZero: 0,
//           color: "white",
//           minTicksLimit: 2000,
//           stepSize: 2,
//         },
//         // defining min and max so hiding the dataset does not change scale range
//         min: 0,
//         max: 20000000,
//       },
//       x: {
//         ticks: {
//           color: "white",
//           maxTicksLimit: 20,
//         },

//         // defining min and max so hiding the dataset does not change scale range
//       },
//     },
//   },
// });

// // Fixing canvas height and width on larger devices
// window.onload = () => {
//   const canvas = document.getElementById("staticGraph");
// };

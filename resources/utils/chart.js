import {
    Chart,
    BarController,
    BarElement,
    PieController,
    ArcElement,
    LineController,
    LineElement,
    PointElement,
    RadarController,
    RadialLinearScale,
    DoughnutController,
    CategoryScale,
    LinearScale,
    Tooltip,
    Legend,
    Title,
    Filler,
  } from "chart.js";
  
  // Register all components globally
  Chart.register(
    BarController,
    BarElement,
    PieController,
    ArcElement,
    LineController,
    LineElement,
    PointElement,
    RadarController,
    RadialLinearScale,
    DoughnutController,
    CategoryScale,
    LinearScale,
    Tooltip,
    Legend,
    Title,
    Filler
  );
  
  export default Chart;
  
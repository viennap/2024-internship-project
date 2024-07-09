import React, { useState, useEffect } from 'react';
import { Line } from "react-chartjs-2";
import { CategoryScale } from 'chart.js';
import Chart from "chart.js/auto";
import "./styles.css";

Chart.register(CategoryScale);

const defaultChartData = {
  labels: [],
  datasets: [{
    label: 'Wheel Speed',
    data: [],
    tension: 0.1
  }]
};

export default function VehicleSpeed({selectedTrajectoryId, markedTimestamp}) {
  const [chartData, setChartData] = useState(defaultChartData);
  // console.log(markedTimestamp); 

  useEffect(() => {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `https://ransom.isis.vanderbilt.edu/ViennaFolder/endpoints_python/get_vehicle_signal?signal_name=speed&trajectory_id=${selectedTrajectoryId}`);
    xhr.onload = function () {
      if (xhr.status === 200) {
        const parsed = JSON.parse(xhr.responseText);

        const data = {
          labels: parsed['time'],
          datasets: [{
            label: 'Wheel Speed',
            data: parsed['signal'],
            borderWidth: 1
          }]
        };

        setChartData(data);
      } else {
        console.log('Error fetching data.');
      }
    };
    xhr.send();
  }, [selectedTrajectoryId, markedTimestamp]);

  return (
    <div className="chart-container">
      <h3 style={{ textAlign: "center", marginBottom: "-20px" }}>Wheel Speed Over Time</h3>
      <Line
        data={chartData}
        options={{
          plugins: {
            title: {
              display: true,
            },
            legend: {
              display: true,
              position: 'top'
            }
          }
        }}
      />
    </div>
  );
}
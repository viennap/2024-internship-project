import React, { useState, useEffect } from 'react';
import { Line } from "react-chartjs-2";
import { CategoryScale } from 'chart.js';
import Chart from "chart.js/auto";
import "./styles.css";

Chart.register(CategoryScale);

export default function VehicleSpeed({selectedTrajectoryId}) {
  const [chartData, setChartData] = useState({ labels: [], datasets: [] });

  useEffect(() => {
    if (!selectedTrajectoryId) return;

    const xhr = new XMLHttpRequest();
    xhr.open('GET', `https://ransom.isis.vanderbilt.edu/ViennaFolder/endpoints_python/get_vehicle_signal?signal_name=speed&trajectory_id=${selectedTrajectoryId}`);
    xhr.onload = function () {
      if (xhr.status === 200) {
        const parsed = JSON.parse(xhr.responseText);
        console.log(parsed);
        console.log(parsed['time']);

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
  }, [selectedTrajectoryId]);

  return (
    <div className="chart-container">
      <h2 style={{ textAlign: "center" }}>Wheel Speed Over Time</h2>
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
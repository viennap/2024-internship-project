import React, { useState, useEffect } from 'react';
import { Line } from "react-chartjs-2";
import { CategoryScale } from 'chart.js';
import Chart from "chart.js/auto";
import "./styles.css";

Chart.register(CategoryScale);

export default function VehicleSteer ({selectedTrajectoryId}) {
  const [chartData, setChartData] = useState({ labels: [], datasets: [] });

  useEffect(() => {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'https://ransom.isis.vanderbilt.edu/ViennaFolder/endpoints_python/get_vehicle_signal?signal_name=steer&trajectory_id=2021-01-04-22-36-11_2T3Y1RFV8KC014025');
    xhr.onload = function () {
      if (xhr.status === 200) {
        const parsed = JSON.parse(xhr.responseText);
        console.log(parsed);
        console.log(parsed['time']);

        const data = {
          labels: parsed['time'],
          datasets: [{
            label: 'Steering Angle',
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
  }, []);

  return (
    <div className="chart-container">
      <h2 style={{ textAlign: "center" }}>Steering Angle Over Time</h2>
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

import React, { useState, useEffect } from 'react';
import { Line } from "react-chartjs-2";
import { CategoryScale } from 'chart.js';
import Chart from "chart.js/auto";
import "./styles.css";

Chart.register(CategoryScale);

const defaultChartData = {
  labels: [],
  datasets: [{
    label: 'Steering Angle',
    data: [],
    tension: 0.1
  }]
};

export default function VehicleSteer ({selectedTrajectoryId}) {
  const [chartData, setChartData] = useState(defaultChartData);

  useEffect(() => {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `https://ransom.isis.vanderbilt.edu/ViennaFolder/endpoints_python/get_vehicle_signal?signal_name=steer&trajectory_id=${selectedTrajectoryId}`);
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
  }, [selectedTrajectoryId]);

  return (
    <div className="chart-container">
      <h3 style={{ textAlign: "center", marginBottom: "-20px" }}>Steering Angle Over Time</h3>
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

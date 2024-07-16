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
  const [xValue, setXValue] = useState(0); 
  const [yValue, setYValue] = useState(0); 
  const [parsed, setParsed] = useState([]);

  useEffect(() => {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `https://ransom.isis.vanderbilt.edu/ViennaFolder/endpoints_python/get_vehicle_signal?signal_name=speed&trajectory_id=${selectedTrajectoryId}`);
    xhr.onload = function () {
      if (xhr.status === 200) {
        const parsed = JSON.parse(xhr.responseText);
        setParsed(parsed); 
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

  useEffect(() => {
    if (parsed && parsed['time'] && parsed['signal'] && selectedTrajectoryId != 'All Trajectories' && selectedTrajectoryId != '') {
      console.log("Marked Timestamp: " + markedTimestamp);
      
      // if (markedTimestamp >= parsed['time'].at(0) && markedTimestamp <= parsed['time'].at(-1)) {
      //   console.log("In bounds");
      // }
      // else {
      //   console.log("Out bounds");
      // }

      // Find the nearest recorded timestamp to markedTimestamp
      var nearestIndex = parsed['time'].reduce((prev, curr, index) => {
        return (Math.abs(curr - markedTimestamp) < Math.abs(parsed['time'][prev] - markedTimestamp) ? index : prev);
      }, 0);
      console.log(nearestIndex);
  
      if (nearestIndex !== -1) {
        // console.log("xValue: ", parsed['time'][nearestIndex]);
        setXValue(nearestIndex); // xValue is set to index in parsed['time']
        // setXValue(parsed['time'][nearestIndex]);
        setYValue(parsed['signal'][nearestIndex]);
      } else {
        setXValue(0);
        setYValue(0);
      }
    }
  }, [markedTimestamp, parsed]);

  const createOptions = () => {

    const showAnnotation = selectedTrajectoryId !== 'All Trajectories' && selectedTrajectoryId !== '';

    return {
      plugins: {
        title: {
          display: true
        },
        legend: {
          display: true,
          position: 'top'
        },
        annotation: {
          annotations: showAnnotation ? {
            point1: {
              type: 'point',
              xValue: xValue, // index in parsed['time']
              yValue: yValue, // actual value in parsed['signal']
              backgroundColor: 'green',
              radius: 10, // Reduced radius for better visibility
              borderColor: 'green',
              borderWidth: 2
            }
          } : {}
        },
        scales: {
          x: {
            type: 'linear',
            position: 'bottom'
          }
        }
      }
    };
  };
  return (
    <div className="chart-container">
      <h3 style={{ textAlign: "center", marginBottom: "20px" }}>Wheel Speed Over Time</h3>
      <Line
        data={chartData}
        options={createOptions()}
      />
    </div>
  );
}
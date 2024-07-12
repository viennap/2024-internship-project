import React, { useState, useEffect } from 'react';
import { Line } from "react-chartjs-2";
import { CategoryScale } from 'chart.js';
import "./styles.css";
import { Chart } from 'chart.js';
import annotationPlugin from 'chartjs-plugin-annotation';

Chart.register(annotationPlugin);
Chart.register(CategoryScale);

const defaultChartData = {
  labels: [],
  datasets: [{
    label: 'Steering Angle',
    data: [],
    tension: 0.1
  }]
};

export default function VehicleSteer({ selectedTrajectoryId, markedTimestamp }) {
  const [chartData, setChartData] = useState(defaultChartData);
  const [xValue, setXValue] = useState(-1); 
  const [yValue, setYValue] = useState(-1); 
  const [parsed, setParsed] = useState([]);

  useEffect(() => {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `https://ransom.isis.vanderbilt.edu/ViennaFolder/endpoints_python/get_vehicle_signal?signal_name=steer&trajectory_id=${selectedTrajectoryId}`);
    xhr.onload = function () {
      if (xhr.status === 200) {
        const parsedData = JSON.parse(xhr.responseText);
        setParsed(parsedData); // Save parsed data to state
        if (parsedData && parsedData['time'] && parsedData['signal']) {
          const data = {
            labels: parsedData['time'],
            datasets: [{
              label: 'Steering Angle',
              data: parsedData['signal'],
              borderWidth: 1
            }]
          };
          setChartData(data);
        }
      } else {
        console.log('Error fetching data.');
      }
    };
    xhr.send();
  }, [selectedTrajectoryId]);

  useEffect(() => {
    if (parsed && parsed['time'] && parsed['signal']) {
      
      // Find the nearest recorded timestamp to markedTimestamp
      const nearestIndex = parsed['time'].reduce((prev, curr, index) => {
        return (Math.abs(curr - markedTimestamp) < Math.abs(parsed['time'][prev] - markedTimestamp) ? index : prev);
      }, 0);
  
      if (nearestIndex !== -1 && xValue != -1 && yValue != -1) {
        console.log("xValue: ", parsed['time'][nearestIndex]);
        console.log("xIndex: ", nearestIndex);
        setXValue(nearestIndex); // xValue is set to index in parsed['time']
        // setXValue(parsed['time'][nearestIndex]);
        setYValue(parsed['signal'][nearestIndex]);
        console.log("yIndex :", parsed['signal'][nearestIndex]);
      } else {
        setXValue(0);
        setYValue(0);
      }
    }
  }, [markedTimestamp, parsed]);

  const createOptions = () => {
    console.log('xValue:', xValue);
    console.log('yValue:', yValue);

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
          annotations: {
            point1: {
              type: 'point',
              xValue: xValue, // index in parsed['time']
              yValue: yValue, // actual value in parsed['signal']
              backgroundColor: 'red',
              radius: 10, // Reduced radius for better visibility
              borderColor: 'red',
              borderWidth: 2
            }
          }
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
      <h3 style={{ textAlign: "center", marginBottom: "20px" }}>Steering Angle Over Time</h3>
      <Line
        data={chartData}
        options={createOptions()}
      />
    </div>
  );
}
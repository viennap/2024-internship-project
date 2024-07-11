import React, { useState, useEffect } from 'react';
import { Line} from "react-chartjs-2";
import { CategoryScale } from 'chart.js';
import "./styles.css";
import { Chart } from 'chart.js';
import { annotationPlugin } from 'chartjs-plugin-annotation';
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

export default function VehicleSteer({selectedTrajectoryId, markedTimestamp}) {
  const [chartData, setChartData] = useState(defaultChartData);
  const [isDataLoaded, setIsDataLoaded] = useState(false);
  const [yValue, setYValue] = useState(0); 
  
  useEffect(() => {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `https://ransom.isis.vanderbilt.edu/ViennaFolder/endpoints_python/get_vehicle_signal?signal_name=steer&trajectory_id=${selectedTrajectoryId}`);
    xhr.onload = function () {
      if (xhr.status === 200) {
        const parsed = JSON.parse(xhr.responseText);
        if (parsed && parsed['time'] && parsed['signal']) {
          const data = {
            labels: parsed['time'],
            datasets: [{
              label: 'Steering Angle',
              data: parsed['signal'],
              borderWidth: 1
            }]
          };
          setChartData(data);
          setIsDataLoaded(true);
          
          console.log(markedTimestamp); 
          
          // Find the nearest recorded timestamp to markedTimestamp
          const nearestIndex = parsed['time'].reduce((prev, curr, index) => {
            return (Math.abs(curr - markedTimestamp) < Math.abs(parsed['time'][prev] - markedTimestamp) ? index : prev);
          }, 0);

          if (nearestIndex !== -1) {
            console.log(parsed['signal'][nearestIndex]);
            setYValue(parsed['signal'][nearestIndex]);
          } else {
            console.log('Timestamp not found in the data');
          }
        }
      } else {
        console.log('Error fetching data.');
      }
    };
    xhr.send();
  }, [selectedTrajectoryId, markedTimestamp]);

  return (
    <div className="chart-container">
      <h3 style={{ textAlign: "center", marginBottom: "-20px" }}>Steering Angle Over Time</h3>
      <Line
        data={chartData}
        options={{
          plugins: {
            title: {
              display: true
            },
            legend: {
              display: true,
              position: 'top'
            },
            annotation: isDataLoaded
              ? {
                  annotations: {
                    point1: {
                      type: 'point',
                      xValue: markedTimestamp,
                      yValue: yValue,
                      backgroundColor: 'red',
                      radius: 50
                    }
                  }
                }
              : undefined
          }
        }}
      />
    </div>
  );
}





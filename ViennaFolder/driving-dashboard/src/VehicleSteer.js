import React, { useEffect } from 'react';
import "./styles.css";

import { Line } from "react-chartjs-2";
import {CategoryScale} from 'chart.js'; 
import Chart from "chart.js/auto";
Chart.register(CategoryScale);

let data = null;

export default function VehicleSteer() {
    useEffect(() => {
        const createPlot = () => {
            const ctx = document.getElementById('myChart');
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'https://ransom.isis.vanderbilt.edu/ViennaFolder/endpoints_python/get_vehicle_signal?signal_name=steer&trajectory_id=2021-01-04-22-36-11_2T3Y1RFV8KC014025');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const parsed = JSON.parse(xhr.responseText);
                    console.log(parsed);
                    
                    data = {
                        labels: parsed['time'],
                        datasets: [{
                            label: 'Steering Angle',
                            data: parsed['signal'],
                            borderWidth: 1
                        }]
                    };
                } 
                else {
                    console.log('Error fetching data.')
                }
            };
            xhr.send();
        }
        createPlot();
    }, [])

    if (!data) {
        return <div>Help...</div>;
    }

  return (
    <div className="VehicleSteer">
      <Line data={data} />
    </div>
  );
}

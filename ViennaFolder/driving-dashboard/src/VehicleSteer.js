import React, { useEffect } from 'react';
import { Line } from 'react-chartjs-2';

export default function VehicleSteer() {
    useEffect(() => {
        const createPlot = () => {
            const ctx = document.getElementById('myChart');
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'https://ransom.isis.vanderbilt.edu/ViennaFolder/endpoints_python/get_vehicle_signal?signal_name=steer&trajectory_id=2021-01-04-22-36-11_2T3Y1RFV8KC014025');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const parsed = JSON.parse(xhr.responseText);
                    
                    /*
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: parsed['time'],
                            datasets: [{
                                label: 'Steering Angle',
                                data: parsed['signal'],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });         */          
                } 
                else {
                    console.log('Error fetching data.')
                }
            };
            xhr.send();
        }
        createPlot();
    }, [])
    
    return (
        <div>
          <div id = 'map' style={{ position: 'absolute', top: 0, bottom: 0, width: '100%' }}/>
        </div>
    );
}
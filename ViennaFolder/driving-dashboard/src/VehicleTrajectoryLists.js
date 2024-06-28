import React, { useEffect, useState, useRef } from 'react';
import mapboxgl from 'mapbox-gl';
import VehicleSpeed from './VehicleSpeed';
import VehicleSteer from './VehicleSteer';
import './styles.css';

mapboxgl.accessToken = 'pk.eyJ1Ijoidmllbm5hcCIsImEiOiJjbHg5NjR4cWgwbjB4MmtwajRlZ2RucXU3In0.eJuij93s8bNLip5GyM85dA';

export default function VehicleTrajectory({trajectoryListSetter, selectedTrajectoryIdSetter}) {
    
    const [startTime, setStartTime] = useState('1577936331'); // January 1, 2020
    const [endTime, setEndTime] = useState('1704166731'); // January 1, 2024
    
    const [bottomLeftLat, setBottomLeftLat] = useState('30');
    const [bottomLeftLong, setBottomLeftLong] = useState('-90');
    const [topRightLat, setTopRightLat] = useState('40');
    const [topRightLong, setTopRightLong] = useState('-80');

    const mapContainer = useRef(null);
    const map = useRef(null);

    const fetchTrajectoryList = () => {
        const xhr = new XMLHttpRequest();

        let url = `https://ransom.isis.vanderbilt.edu/ViennaFolder/endpoints_python/get_trajectory_lists?start_time=${startTime}&end_time=${endTime}&bottom_left_lat=${bottomLeftLat}&bottom_left_long=${bottomLeftLong}&top_right_lat=${topRightLat}&top_right_long=${topRightLong}`;
       
        xhr.open('GET', url);
        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                let trajectories = response['trajectories'];
                let idList = Object.keys(trajectories);
                setTrajectoryList(idList);
                if (idList.length !== 0) {
                    setSelectedTrajectoryId(idList[0]); 
                    plotTrajectories(trajectories, idList);     
                }
                else {
                    setSelectedTrajectoryId('No valid trajectories.');
                }       
            } else {
                console.log('Error fetching trajectory list.');
            }
        };
        xhr.send();
    };

    // Currently plotting one after another instead of all at the same time.
    const plotTrajectories = (trajectories, idList) => {
        let promises = [];
        // console.log(idList);

        idList.forEach((id) => {
            promises.push(fetch(`https://ransom.isis.vanderbilt.edu/ViennaFolder/endpoints_python/get_vehicle_trajectory?trajectory_id=${id}`));
        });
        // console.log(promises.length);

        Promise.all(promises).then(function(...args) {
            let argsArray = args[0];
            
            let jsonPromises = [];
            argsArray.forEach((response) => {
                if (response.ok) {
                    let trajectoryResponse = response.json();
                    jsonPromises.push(trajectoryResponse);
                }
            });
            Promise.all(jsonPromises).then(function (...jsonArgs) {
                jsonArgs[0].forEach((obj) => {
                   trajectories[obj["id"]]["latitude"] = obj["latitude"];
                   trajectories[obj["id"]]["longitude"] = obj["longitude"];
                })
            }).then(function () {
                trajectoryListSetter(trajectories);
            });
        }).catch(function (error) {
            console.log("Doh!");
            console.log(error);
        });
    };
    
    const handleSelectChange = (event) => {
        if (trajectoryList.includes(event.target.value)) {
            selectedTrajectoryIdSetter(event.target.value);
            console.log("Selected trajectory: " + selectedTrajectoryId);
        }
    };

    return (
        <div>
            <h2>Select a Trajectory ID:</h2>
            <select value={selectedTrajectoryId} onChange={handleSelectChange}>
                {trajectoryList.map((id) => (
                    <option key={id} value={id}>{id}</option>
                ))}
            </select>
            
            <div> 
                <label>
                    Start Time:
                    <input type="text" onChange={(e) => setStartTime(e.target.value)} />
                </label>

                <label>
                    End Time:
                    <input type="text" onChange={(e) => setEndTime(e.target.value)} />
                </label>

                <label>
                    Bottom Left Longitude:
                    <input type="text" onChange={(e) => setBottomLeftLong(e.target.value)} />
                </label>
                
                <label>
                    Bottom Left Latitude:
                    <input type="text" onChange={(e) => setBottomLeftLat(e.target.value)} />
                </label>

                <label>
                    Top Right Longitude:
                    <input type="text" onChange={(e) => setTopRightLong(e.target.value)} />
                </label>

                <label>
                    Top Right Latitude:
                    <input type="text" onChange={(e) => setTopRightLat(e.target.value)} />
                </label>
                
            </div>

            <div>
                <button onClick={fetchTrajectoryList}>Fetch Trajectories</button>
            </div>
            
            {/* <VehicleSpeed selectedTrajectoryId = {selectedTrajectoryId} />
            <VehicleSteer selectedTrajectoryId = {selectedTrajectoryId} /> */}
        </div>
    );
}

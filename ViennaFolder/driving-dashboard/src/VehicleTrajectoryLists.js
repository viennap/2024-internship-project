import React, { useEffect, useState, useRef } from 'react';
import mapboxgl from 'mapbox-gl';
import Dashboard from './Dashboard.js';
import './styles.css';

mapboxgl.accessToken = 'pk.eyJ1Ijoidmllbm5hcCIsImEiOiJjbHg5NjR4cWgwbjB4MmtwajRlZ2RucXU3In0.eJuij93s8bNLip5GyM85dA';

export default function VehicleTrajectory({trajectoryListSetter, selectedTrajectoryIdSetter, trajectoryList, selectedTrajectoryId}) {
    
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
                if (idList.length !== 0) {
                    selectedTrajectoryIdSetter(idList[0]); 
                    plotTrajectories(trajectories, idList);     
                }
                else {
                    selectedTrajectoryIdSetter('No valid trajectories.');
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
                console.log(trajectories);
                trajectoryListSetter(trajectories);
            });
        }).catch(function (error) {
            console.log("Doh!");
            console.log(error);
        });
    };
    
    const handleSelectChange = (event) => {
        selectedTrajectoryIdSetter(event.target.value);
        console.log("Selected trajectory: " + selectedTrajectoryId);
    };

    const createSelectOption = () => {
        var options = "";
        for (const [id, trajectory] of Object.entries(trajectoryList)) {
            options += "<option>" + id + "</option>";
        }

        if (options.length !== 0) {
            document.getElementById("trajectory-options").innerHTML = options; 
        }
    } 

    return (
        <div>
            <h2>Select a Trajectory ID:</h2>
            <select id = "trajectory-options" value={selectedTrajectoryId} onChange={handleSelectChange} />
                {createSelectOption()}
            
            <div> 
                <label>
                    Start Time:
                    <input type="text" value={startTime} onChange={(e) => setStartTime(e.target.value)} />
                </label>

                <label>
                    End Time:
                    <input type="text" value={endTime} onChange={(e) => setEndTime(e.target.value)} />
                </label>

                <label>
                    Bottom Left Longitude:
                    <input type="text" value={bottomLeftLong} onChange={(e) => setBottomLeftLong(e.target.value)} />
                </label>
                
                <label>
                    Bottom Left Latitude:
                    <input type="text" value={bottomLeftLat} onChange={(e) => setBottomLeftLat(e.target.value)} />
                </label>

                <label>
                    Top Right Longitude:
                    <input type="text" value={topRightLong} onChange={(e) => setTopRightLong(e.target.value)} />
                </label>

                <label>
                    Top Right Latitude:
                    <input type="text" value={topRightLat} onChange={(e) => setTopRightLat(e.target.value)} />
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

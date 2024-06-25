import React, { useEffect, useState } from 'react';
import mapboxgl from 'mapbox-gl';

mapboxgl.accessToken = 'pk.eyJ1Ijoidmllbm5hcCIsImEiOiJjbHg5NjR4cWgwbjB4MmtwajRlZ2RucXU3In0.eJuij93s8bNLip5GyM85dA';

export default function VehicleTrajectory() {
    const [trajectoryList, setTrajectoryList] = useState([]);
    const [selectedTrajectoryId, setSelectedTrajectoryId] = useState('');
    const [map, setMap] = useState(null); // initialize map once

    useEffect(() => {
        const fetchTrajectoryList = () => {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'https://ransom.isis.vanderbilt.edu/ViennaFolder/endpoints_python/get_trajectory_lists?start_time=1577937156&end_time=1704167556&bottom_left_lat=25&bottom_left_long=-130&top_right_lat=50&top_right_long=-70');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    let trajectories = response['trajectories'];
                    let idList = Object.keys(trajectories);
                    if (idList.length === 0) {
                        // Need to properly handle if no valid trajectories are generated.
                        setTrajectoryList("No valid trajectories.");
                    }
                    else {
                        setTrajectoryList(idList);
                        setSelectedTrajectoryId(idList[0]); // default = first trajectory ID in drop-down
                    }                     
                } else {
                    console.log('Error fetching trajectory list.');
                }
            };
            xhr.send();
        };
        fetchTrajectoryList();

        const initializeMap = () => {
            const newMap = new mapboxgl.Map({
                container: 'map', 
                center: [86.767960, 36.174465], // defalt at Nashville
                zoom: 5
            });

            setMap(newMap);
        };

        initializeMap();

    }, []);

    useEffect(() => {
        if (selectedTrajectoryId && map) {
            createPlot(selectedTrajectoryId);
        }
    }, [selectedTrajectoryId, map]);

    const createPlot = (id) => {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `https://ransom.isis.vanderbilt.edu/ViennaFolder/endpoints_python/get_vehicle_trajectory?trajectory_id=${id}`);
        xhr.onload = function () {
            let GeoJSON = {};

            if (xhr.status === 200) {
                const parsed = JSON.parse(xhr.responseText);

                map.setCenter([parsed['longitude'].at(-1), parsed['latitude'].at(-1)]);
                map.setZoom(20);

                GeoJSON["features"] = [];
                GeoJSON["properties"] = {};
                GeoJSON["type"] = "FeatureCollection";

                let route = {};
                route["type"] = "Feature";
                route["properties"] = {};
                route["geometry"] = {};

                route["geometry"]["type"] = "MultiLineString";
                route["geometry"]["coordinates"] = [[]];

                for (let i = 0; i < parsed['latitude'].length; i++) {
                    let pair = [parsed['longitude'].at(i), parsed['latitude'].at(i)];
                    route["geometry"]["coordinates"][0].push(pair);
                }

                GeoJSON["features"].push(route);

                map.on('load', async () => {
                    map.addSource("my-route", {
                        "type": "geojson",
                        "data": GeoJSON
                    });

                    map.addLayer({
                        id: 'my-route-layer',
                        source: 'my-route',
                        type: 'line',
                        layout: {
                            'line-join': 'round',
                            'line-cap': 'round'
                        },
                        paint: {
                            'line-color': '#FF0000',
                            'line-width': 5,
                            'line-opacity': 0.8,
                        }
                    });
                });
            } else {
                console.log('Error fetching data.');
            }
        };
        xhr.send();
    };

    const handleSelectChange = (event) => {
        // Making sure that the list actually includes what's in the dropdown. 
        if (trajectoryList.includes(event.target.value)) {
            setSelectedTrajectoryId(event.target.value);
            console.log(event.target.value);
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
            <div id='map' style={{ height: '500px' }}></div>
        </div>
    );
}

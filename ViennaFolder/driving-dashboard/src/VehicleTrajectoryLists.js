import React, { useEffect, useState, useRef } from 'react';
import mapboxgl from 'mapbox-gl';

import Dashboard from './Dashboard';
import TrajectorySelector from './TrajectorySelector';

mapboxgl.accessToken = 'pk.eyJ1Ijoidmllbm5hcCIsImEiOiJjbHg5NjR4cWgwbjB4MmtwajRlZ2RucXU3In0.eJuij93s8bNLip5GyM85dA';

export default function VehicleTrajectory() {
    // const [selectedTrajectoryId, setSelectedTrajectoryId] = useState('');
    // const [trajectoryList, setTrajectoryList] = useState([]);
    
    // const [startTime, setStartTime] = useState('1577936331'); // January 1, 2020
    // const [endTime, setEndTime] = useState('1704166731'); // January 1, 2024
    
    // const [bottomLeftLat, setBottomLeftLat] = useState('30');
    // const [bottomLeftLong, setBottomLeftLong] = useState('-90');
    // const [topRightLat, setTopRightLat] = useState('40');
    // const [topRightLong, setTopRightLong] = useState('-80');

    const mapContainer = useRef(null);
    const map = useRef(null);

    useEffect(() => {
        if (map.current) return; // initialize map only once
        map.current = new mapboxgl.Map({
          container: mapContainer.current,
          style: 'mapbox://styles/mapbox/streets-v12',
          center: [-86.767960, 36.174465],
          zoom: 5
        });
    });

    useEffect(() => {
        const initializeMap = () => {
            map.current.on('load', async () => {
                let GeoJSON = {};

                GeoJSON["features"] = [];
                GeoJSON["properties"] = {};
                GeoJSON["type"] = "FeatureCollection";

                map.current.addSource("my-route", {
                    "type": "geojson",
                    "data": GeoJSON
                });

                map.current.addLayer({
                    id: 'my-route-layer',
                    source: 'my-route',
                    type: 'line',
                    layout: {
                        'line-join': 'round',
                        'line-cap': 'round'
                    },
                    paint: {
                        'line-color': ['to-string', ['get', 'myColorProperty']],
                        'line-width': 5,
                        'line-opacity': 0.8,
                    }
                });
                
                fetchTrajectoryList(); 
            });
        };

        initializeMap();        
    }, []);

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
                    plotTrajectories(idList);     
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

    useEffect(() => {
        if (selectedTrajectoryId && map) {
            createPlot(selectedTrajectoryId);
        }
    }, [selectedTrajectoryId, map]);

    const createPlot = (id) => {
        const xhr = new XMLHttpRequest();
        if (id !== 'No valid trajectories.') {
            xhr.open('GET', `https://ransom.isis.vanderbilt.edu/ViennaFolder/endpoints_python/get_vehicle_trajectory?trajectory_id=${id}`);
        xhr.onload = function () {
            let GeoJSON = {};

            if (xhr.status === 200) {
                const parsed = JSON.parse(xhr.responseText);

                map.current.setCenter([parsed['longitude'].at(-1), parsed['latitude'].at(-1)]);
                map.current.setZoom(15);

                GeoJSON["features"] = [];
                GeoJSON["properties"] = {"myColorProperty" : "red"};
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

                clearLayers(); 

                map.current.getSource('my-route').setData(GeoJSON);

            } else {
                console.log('Error fetching data.');
            }
        };
        xhr.send();
        }
        else {
            clearLayers(); 
            console.log("No valid trajectories to plot.");
        }
    };

    
    // Currently plotting one after another instead of all at the same time.
    const plotTrajectories = (idList) => {
        clearLayers(); 
        let promises = [];
        // console.log(idList);

        idList.forEach((id) => {
            promises.push(fetch(`https://ransom.isis.vanderbilt.edu/ViennaFolder/endpoints_python/get_vehicle_trajectory?trajectory_id=${id}`));
        });
        // console.log(promises.length);
        Promise.all(promises).then(function(...args) {
            let argsArray = args[0];
            let GeoJSON = {};
            GeoJSON["features"] = [];
            GeoJSON["properties"] = {};
            GeoJSON["type"] = "FeatureCollection";

            let route = {};
            route["type"] = "Feature";
            route["properties"] = {};
            route["geometry"] = {};
            route["geometry"]["type"] = "MultiLineString";
            route["geometry"]["coordinates"] = [];

            let jsonPromises = [];
            argsArray.forEach((response) => {
                if (response.ok) {
                    let trajectoryResponse = response.json();
                    jsonPromises.push(trajectoryResponse);
                }
            });
            Promise.all(jsonPromises).then(function (...jsonArgs) {
                jsonArgs[0].forEach((obj) => {
                    let currentTrajectory = [];
                    for (let i = 0; i < obj['latitude'].length; i++) {
                        let pair = [obj['longitude'].at(i), obj['latitude'].at(i)];
                        currentTrajectory.push(pair);
                    }

                    route["geometry"]["coordinates"].push(currentTrajectory);

                    let color = '#'+(Math.random() * 0xFFFFFF << 0).toString(16).padStart(6, '0');
                    
                    route["properties"]["myColorProperty"] = color;
                })
            }).then(function () {
                GeoJSON["features"].push(route);
                map.current.getSource('my-route').setData(GeoJSON);
            });
        }).catch(function (error) {
            console.log("Doh!");
            console.log(error);
        });
    };

    const clearLayers = () => {
        const layers = map.current.getStyle().layers;
        layers.forEach((layer) => {
            if (layer.id.startsWith('route-')) {
                map.current.removeLayer(layer.id);
            }
        });
    };

    const handleSelectChange = (event) => {
        if (trajectoryList.includes(event.target.value)) {
            setSelectedTrajectoryId(event.target.value);
            console.log("Selected trajectory: " + selectedTrajectoryId);
            createPlot(selectedTrajectoryId);
        }
    };

    return (
        <div>
            <Dashboard 
                startTime={startTime} setStartTime={setStartTime}
                endTime={endTime} setEndTime={setEndTime}
                bottomLeftLat={bottomLeftLat} setBottomLeftLat={setBottomLeftLat}
                bottomLeftLong={bottomLeftLong} setBottomLeftLong={setBottomLeftLong}
                topRightLat={topRightLat} setTopRightLat={setTopRightLat}
                topRightLong={topRightLong} setTopRightLong={setTopRightLong}
                fetchTrajectoryList={fetchTrajectoryList}
            />

            <TrajectorySelector 
                selectedTrajectoryId={selectedTrajectoryId}
                trajectoryList={trajectoryList}
                handleSelectChange={handleSelectChange}
            />

            <div ref={mapContainer} className="map-container" style={{ height: '1000px' }}></div>
        </div>
    );
}

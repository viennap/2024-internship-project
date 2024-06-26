import React, { useEffect, useState } from 'react';
import mapboxgl from 'mapbox-gl';

mapboxgl.accessToken = 'pk.eyJ1Ijoidmllbm5hcCIsImEiOiJjbHg5NjR4cWgwbjB4MmtwajRlZ2RucXU3In0.eJuij93s8bNLip5GyM85dA';

export default function VehicleTrajectory() {
    const [trajectoryList, setTrajectoryList] = useState([]);
    const [selectedTrajectoryId, setSelectedTrajectoryId] = useState('');
    const [map, setMap] = useState(null); // initialize map once
    const [startTime, setStartTime] = useState('1624912116');
    const [endTime, setEndTime] = useState('1624915962');
    const [bottomLeftLat, setBottomLeftLat] = useState('30');
    const [bottomLeftLong, setBottomLeftLong] = useState('-90');
    const [topRightLat, setTopRightLat] = useState('40');
    const [topRightLong, setTopRightLong] = useState('-80');

    useEffect(() => {
        const initializeMap = () => {
            const map = new mapboxgl.Map({
                container: 'map', 
                center: [-86.767960, 36.174465], // defalt at Nashville
                zoom: 5
            });
        
            setMap(map);

            map.on('load', async () => {

                let GeoJSON = {};

                GeoJSON["features"] = [];
                GeoJSON["properties"] = {};
                GeoJSON["type"] = "FeatureCollection";

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
        };

        initializeMap();

        fetchTrajectoryList(); 
        
        // For each trajectory in the list, create a plot... add to the same Ma
    }, []);

    const fetchTrajectoryList = () => {
        const xhr = new XMLHttpRequest();
        let url = 'https://ransom.isis.vanderbilt.edu/ViennaFolder/endpoints_python/get_trajectory_lists?start_time=' 
                    + startTime + '&end_time=' + endTime + '1704167556&bottom_left_lat=' + bottomLeftLat + '&bottom_left_long=' + bottomLeftLong 
                    + '&top_right_lat=' + topRightLat + '&top_right_long=' + topRightLong;
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

                map.setCenter([parsed['longitude'].at(-1), parsed['latitude'].at(-1)]);
                map.setZoom(15);

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

                map.getSource('my-route').setData(GeoJSON);

            } else {
                console.log('Error fetching data.');
            }
        };
        xhr.send();
        }
        else {
            console.log("No valid trajectories to plot.");
        }
    };

    // Currently plotting one after another instead of all at the same time.
    const plotTrajectories = (idList) => {
        let allTrajectories = []
        let promises = [];
        idList.forEach((id) => {
            promises.push(fetch(`https://ransom.isis.vanderbilt.edu/ViennaFolder/endpoints_python/get_vehicle_trajectory?trajectory_id=${id}`));
        });
        Promise.all(promises).then(function() {
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
            arguments.forEach((response) => {
                if (response.ok) {
                    let json = response.json();
                    let currentTrajectory = [];
                    for (let i = 0; i < json['latitude'].length; i++) {
                        let pair = [json['longitude'].at(i), json['latitude'].at(i)];
                        currentTrajectory.push(pair);
                    }
                    route["geometry"]["coordinates"].push(currentTrajectory);
                }
            });
            map.getSource('my-route').setData(GeoJSON);
        }).catch(function () {
            console.log("Doh!");
        });
        /*idList.forEach((id) => {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `https://ransom.isis.vanderbilt.edu/ViennaFolder/endpoints_python/get_vehicle_trajectory?trajectory_id=${id}`);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const parsed = JSON.parse(xhr.responseText);
                    
                    let GeoJSON = {};

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

                    allTrajectories.push(GeoJSON);
                } else {
                    console.log('Error fetching trajectory data.');
                }
            };
            xhr.send();
        });*/
    };

    const handleSelectChange = (event) => {
        if (trajectoryList.includes(event.target.value)) {
            setSelectedTrajectoryId(event.target.value);
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
                    <input type="text" value={startTime} onChange={(e) => setStartTime(e.target.value)} />
                </label>
                <label>
                    End Time:
                    <input type="text" value={endTime} onChange={(e) => setEndTime(e.target.value)} />
                </label>

                <label>
                    Bottom Left Longitude
                    <input type="text" value={bottomLeftLong} onChange={(e) => setBottomLeftLong(e.target.value)} />
                </label>
                <label>
                    Bottom Left Latitude
                    <input type="text" value={bottomLeftLat} onChange={(e) => setBottomLeftLat(e.target.value)} />
                </label>
                <label>
                    Top Right Longitude
                    <input type="text" value={topRightLong} onChange={(e) => setTopRightLong(e.target.value)} />
                </label>
                <label>
                    Top Right Latitude
                    <input type="text" value={topRightLat} onChange={(e) => setTopRightLat(e.target.value)} />
                </label>

                <button onClick={fetchTrajectoryList}>Fetch Trajectories</button>
            </div>
            
            <div id='map' style={{ height: '500px' }}></div>
        </div>
    );
}

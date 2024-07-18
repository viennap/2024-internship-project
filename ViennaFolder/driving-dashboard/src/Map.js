import React, { useEffect, useState, useRef } from 'react';
import mapboxgl from 'mapbox-gl';
import * as turf from '@turf/turf';

import { AdapterDayjs } from '@mui/x-date-pickers/AdapterDayjs';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';
import { TimeField } from '@mui/x-date-pickers/TimeField';
import dayjs from 'dayjs';

import { Typography, Select, TextField, Button, Box } from '@mui/material';

mapboxgl.accessToken = 'pk.eyJ1Ijoidmllbm5hcCIsImEiOiJjbHg5NjR4cWgwbjB4MmtwajRlZ2RucXU3In0.eJuij93s8bNLip5GyM85dA';

export default function Map({ trajectoryList, selectedTrajectoryId, markedTimestamp, markedTimestampSetter, selectedTrajectoryIdSetter }) {
    const mapContainer = useRef(null);
    const map = useRef(null);
    const [mapReady, setMapReady] = useState(false);
    const markerRef = useRef(null);

    const [sliderValue, setSliderValue] = useState(0);

    useEffect(() => {
        if (map.current) return; // initialize map only once
        let newMap = new mapboxgl.Map({
            container: mapContainer.current,
            style: 'mapbox://styles/mapbox/streets-v12',
            center: [-86.767960, 36.174465],
            zoom: 6
        });
        newMap.doubleClickZoom.disable();

        newMap.on('load', async () => {
            let GeoJSON = {};

            GeoJSON["features"] = [];
            GeoJSON["properties"] = {};
            GeoJSON["type"] = "FeatureCollection";

            newMap.addSource('my-route', {
                "type": "geojson",
                "data": GeoJSON
            });

            newMap.addLayer({
                id: 'my-route-layer',
                source: 'my-route',
                type: 'line',
                layout: {
                    'line-join': 'round',
                    'line-cap': 'round'
                },
                paint: {
                    'line-color': ['case',
                        ['all', ['to-boolean', ['get', 'isSelected']], ['to-boolean', ['get', 'oneIsSelected']]], '#FF0000',
                        ['get', 'oneIsSelected'], '#000000',
                        ['get', 'myColorProperty']
                    ],
                    'line-width': ['case',
                        ['get', 'isSelected'], 12.0,
                        5.0
                    ],
                    'line-opacity': ['case',
                        ['all', ['to-boolean', ['get', 'isSelected']], ['to-boolean', ['get', 'oneIsSelected']]], 0.8,
                        ['get', 'oneIsSelected'], 0,
                        0.8
                    ],
                }
            });
            setMapReady(true);
        });
        map.current = newMap;
    }, []);

    useEffect(() => {
        if (mapReady) {
            let GeoJSON = {};
            GeoJSON["features"] = [];
            GeoJSON["properties"] = {};
            GeoJSON["type"] = "FeatureCollection";

            for (const [id, trajectory] of Object.entries(trajectoryList)) {
                let route = {};
                route["type"] = "Feature";
                route["properties"] = {};
                route["geometry"] = {};
                route["geometry"]["type"] = "MultiLineString";
                route["geometry"]["coordinates"] = [];

                let currentTrajectory = [];

                for (let i = 0; i < trajectory['latitude'].length; i++) {
                    let pair = [trajectory['longitude'][i], trajectory['latitude'][i]];
                    currentTrajectory.push(pair);
                }

                route["geometry"]["coordinates"].push(currentTrajectory);

                let color = '#' + (Math.random() * 0xFFFFFF << 0).toString(16).padStart(6, '0');

                if (selectedTrajectoryId === "All Trajectories") {
                    // Clear the existing marker, if any, from the map. 
                    if (markerRef.current != null) {
                        markerRef.current.remove();
                    }

                } else if (id === selectedTrajectoryId) {
                    let midPoint = Math.floor(currentTrajectory.length / 2);
                    map.current.setCenter(currentTrajectory[midPoint]);
                    // Extra: Make sure the most out-lying trajectory are within the map
                }

                route["properties"]["trajectoryId"] = id;
                route["properties"]["isSelected"] = (id === selectedTrajectoryId);
                route["properties"]["oneIsSelected"] = (selectedTrajectoryId !== "All Trajectories");
                route["properties"]["myColorProperty"] = color;

                GeoJSON["features"].push(route);
            }
            map.current.getSource('my-route').setData(GeoJSON);
        }
    }, [mapReady, trajectoryList, selectedTrajectoryId]);

    useEffect(() => {
        if (mapReady) {
            const handleTrajectoryClick = (event) => {
                if (selectedTrajectoryId !== 'All Trajectories') return;

                const trajectoryFeatures = map.current.queryRenderedFeatures(event.point, {
                    layers: ['my-route-layer']
                });

                if (!trajectoryFeatures.length) {
                    return;
                }

                const trajectoryFeature = trajectoryFeatures[0];
                selectedTrajectoryIdSetter(trajectoryFeature.properties.trajectoryId);
            };

            const handleDblClick = (event) => {
                if (selectedTrajectoryId === 'All Trajectories') return;

                const trajectoryFeatures = map.current.queryRenderedFeatures(event.point, {
                    layers: ['my-route-layer'],
                });

                if (trajectoryFeatures.length) {
                    const trajectoryFeature = trajectoryFeatures[0];
                    const point = turf.point([event.lngLat.lng, event.lngLat.lat]).geometry.coordinates;

                    if (trajectoryFeature.geometry && trajectoryFeature.geometry.coordinates.length) {
                        const id = trajectoryFeature.properties.trajectoryId;
                        console.log("Current ID: " + id);
                        console.log("Selected ID: " + selectedTrajectoryId);

                        const coords = [];

                        for (let i = 0; i < trajectoryList[id].longitude.length; i++) {
                            let pair = [
                                trajectoryList[id].longitude[i],
                                trajectoryList[id].latitude[i]
                            ];
                            coords.push(pair);
                        }

                        const line = turf.lineString(coords);
                        const snapped = turf.nearestPointOnLine(line, point);
                        const trajectoryPoint = snapped.geometry.coordinates;

                        if (markerRef.current) {
                            markerRef.current.remove();
                        }

                        markerRef.current = new mapboxgl.Marker({
                            color: '#FFFFFF',
                        }).setLngLat(trajectoryPoint).addTo(map.current);

                        const index = snapped.properties.index;
                        const markedTimestamp = trajectoryList[id].time[index];

                        // Update timestamp to the dropped marker timestamp
                        markedTimestampSetter(markedTimestamp);
                        setSliderValue(markedTimestamp);
                        console.log(markedTimestamp);
                    }
                }
            };

            map.current.on('click', handleTrajectoryClick);
            map.current.on('dblclick', handleDblClick);

            return () => {
                map.current.off('click', handleTrajectoryClick);
                map.current.off('dblclick', handleDblClick);
            };
        }
    }, [mapReady, selectedTrajectoryId, trajectoryList, markedTimestampSetter, selectedTrajectoryIdSetter]);

    const handleSliderChange = (event) => {
        const newTimestamp = Number(event.target.value);
        setSliderValue(newTimestamp);
        markedTimestampSetter(newTimestamp);
        
        if (selectedTrajectoryId && trajectoryList[selectedTrajectoryId]) {
            const index = trajectoryList[selectedTrajectoryId].time.reduce((prev, curr, index) => {
                return (Math.abs(curr - newTimestamp) < Math.abs(trajectoryList[selectedTrajectoryId].time[prev] - newTimestamp) ? index : prev);
            }, 0);
            console.log(index); 

            if (index !== -1) {
                const lng = trajectoryList[selectedTrajectoryId].longitude[index];
                const lat = trajectoryList[selectedTrajectoryId].latitude[index];

                if (markerRef.current) {
                    markerRef.current.setLngLat([lng, lat]);
                } else {
                    markerRef.current = new mapboxgl.Marker({
                        color: '#FFFFFF',
                    }).setLngLat([lng, lat]).addTo(map.current);
                }
            }
        }
    };
    
    const handleTimestampManipulation = (label) => {
        if (selectedTrajectoryId && trajectoryList[selectedTrajectoryId]) {
            const timeArray = trajectoryList[selectedTrajectoryId].time;
    
            let currentTimestamp = new Date(sliderValue);
            let newTimestamp;
    
            if (label === "earlier") {
                newTimestamp = new Date(currentTimestamp.getTime() - 60); 
                console.log("Earlier clicked");
            } else if (label === "later") {
                newTimestamp = new Date(currentTimestamp.getTime() + 60); 
                console.log("Later clicked");
            }
    
            // Find the closest timestamp in timeArray to newTimestamp
            let nearestTimestamp = timeArray.reduce((prev, curr) => {
                return (Math.abs(new Date(curr) - newTimestamp) < Math.abs(new Date(prev) - newTimestamp) ? curr : prev);
            });
            
            setSliderValue(nearestTimestamp);
            markedTimestampSetter(nearestTimestamp);
    
            let newIndex = timeArray.findIndex(time => time === nearestTimestamp);
    
            const lng = trajectoryList[selectedTrajectoryId].longitude[newIndex];
            const lat = trajectoryList[selectedTrajectoryId].latitude[newIndex];
    
            if (markerRef.current) {
                markerRef.current.setLngLat([lng, lat]);
            } else {
                markerRef.current = new mapboxgl.Marker({
                    color: '#FFFFFF',
                }).setLngLat([lng, lat]).addTo(map.current);
            }
        }
    };

    const handleTimeInputChange = (newTime) => {
        const newTimestamp = dayjs(newTime).unix();
        setSliderValue(newTimestamp);
        markedTimestampSetter(newTimestamp);

        if (selectedTrajectoryId && trajectoryList[selectedTrajectoryId]) {         
            if (newTimestamp < trajectoryList[selectedTrajectoryId].time.at(0) 
                    || newTimestamp > trajectoryList[selectedTrajectoryId].time.at(-1)) {
                console.log("Please entire a time that is within the range.");    
            }
            else {
                console.log("Valid time entered.");
            }

            const index = trajectoryList[selectedTrajectoryId].time.reduce((prev, curr, index) => {
                return (Math.abs(curr - newTimestamp) < Math.abs(trajectoryList[selectedTrajectoryId].time[prev] - newTimestamp) ? index : prev);
            }, 0);
            
            if (index !== -1) {
                const lng = trajectoryList[selectedTrajectoryId].longitude[index];
                const lat = trajectoryList[selectedTrajectoryId].latitude[index];

                if (markerRef.current) {
                    markerRef.current.setLngLat([lng, lat]);
                } else {
                    markerRef.current = new mapboxgl.Marker({
                        color: '#FFFFFF',
                    }).setLngLat([lng, lat]).addTo(map.current);
                }
            }
        }
    };

    return (
        <div>
            <div
                ref={mapContainer}
                className="map-container"
                style={{ height: '500px' }}
            />
    
            <div>
                {selectedTrajectoryId !== "All Trajectories" && trajectoryList[selectedTrajectoryId] && (
                    <Box display="flex" alignItems="center">
                        <Typography>{dayjs.unix(Math.min(...trajectoryList[selectedTrajectoryId].time)).format('hh:mm:ss A')}</Typography>

                        <Button onClick={() => handleTimestampManipulation("earlier")} label="earlier"> ⬅️ </Button>
                        <input
                            type="range"
                            min={Math.min(...trajectoryList[selectedTrajectoryId].time)}
                            max={Math.max(...trajectoryList[selectedTrajectoryId].time)}
                            value={sliderValue}
                            onChange={handleSliderChange}
                            className="slider"
                        />
                        <Button onClick={() => handleTimestampManipulation("later")} label="later"> ➡️ </Button>
                        
                        <Typography>{dayjs.unix(Math.max(...trajectoryList[selectedTrajectoryId].time)).format('hh:mm:ss A')}</Typography>
                    </Box>
                )}

                <div> </div>
                {selectedTrajectoryId !== "All Trajectories" && trajectoryList[selectedTrajectoryId] && (
                    <LocalizationProvider dateAdapter={AdapterDayjs}>
                        <TimeField
                            label="Current Timestamp"
                            value={dayjs.unix(sliderValue)}
                            format="hh:mm:ss A"
                            onChange={handleTimeInputChange}
                            renderInput={(params) => <TextField {...params} />}
                        />
                            
                    </LocalizationProvider>
                )}
            </div>
        </div>
    );
}

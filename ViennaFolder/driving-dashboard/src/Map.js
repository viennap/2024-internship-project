import React, { useEffect, useState, useRef } from 'react';
import mapboxgl from 'mapbox-gl';
import * as turf from '@turf/turf';

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
                    map.current.setZoom(8);
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

    return (
        <div>
            <div
                ref={mapContainer}
                className="map-container"
                style={{ height: '500px' }}
            />
            {selectedTrajectoryId !== "All Trajectories" && trajectoryList[selectedTrajectoryId] && (
                <input
                    type="range"
                    min={Math.min(...trajectoryList[selectedTrajectoryId].time)}
                    max={Math.max(...trajectoryList[selectedTrajectoryId].time)}
                    value={sliderValue}
                    onChange={handleSliderChange}
                    className="slider"
                />
            )}
        </div>
    );
}

import React, { useEffect, useState, useRef } from 'react';
import mapboxgl from 'mapbox-gl';
import * as turf from '@turf/turf';
import { geometry, lineStringç } from "@turf/helpers";

mapboxgl.accessToken = 'pk.eyJ1Ijoidmllbm5hcCIsImEiOiJjbHg5NjR4cWgwbjB4MmtwajRlZ2RucXU3In0.eJuij93s8bNLip5GyM85dA';

export default function Map({trajectoryList, selectedTrajectoryId, markedTimestampSetter, selectedTrajectoryIdSetter}){
    const mapContainer = useRef(null);
    const map = useRef(null);
    const [mapReady, setMapReady] = useState(false);
    const markerRef = useRef(null);

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
    });

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
                    let pair = [trajectory['longitude'].at(i), trajectory['latitude'].at(i)];
                    currentTrajectory.push(pair);
                }

                route["geometry"]["coordinates"].push(currentTrajectory);

                let color = '#'+(Math.random() * 0xFFFFFF << 0).toString(16).padStart(6, '0');

                if (selectedTrajectoryId === "All Trajectories") {
                    // Clear the existing marker, if any, from the map. 
                    if (markerRef.current != null) {
                        markerRef.current.remove(); 
                    }
                    
                }
                else if (id === selectedTrajectoryId) { 
                    let midPoint = currentTrajectory.length/2; 
                    map.current.setCenter(currentTrajectory[midPoint]);
                    map.current.setZoom(8);
                    // Extra: Make sure the most out-lying trajectory are within the map
                }
                
                route["properties"]["trajectoryId"] = id;
                route["properties"]["isSelected"] = (id === selectedTrajectoryId);
                route["properties"]["oneIsSelected"] = (selectedTrajectoryId !==  "All Trajectories");
                route["properties"]["myColorProperty"] = color;
                
                GeoJSON["features"].push(route);
            }
            // console.log(GeoJSON);
            map.current.getSource('my-route').setData(GeoJSON);
        }
        // add a new layer for the selected trajectory id on top ??
    }, [mapReady, trajectoryList, selectedTrajectoryId]);

    const handleTrajectoryClick = (event) => {
        if (!mapReady || selectedTrajectoryId !== 'All Trajectories') return;
    
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
        if (!mapReady || selectedTrajectoryId === 'All Trajectories') return;
    
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
                console.log(markedTimestamp);
            }
        }
    };
    
    useEffect(() => {
        if (mapReady) {
            return () => {
                map.current.off('click', handleTrajectoryClick);
                map.current.off('dblclick', handleDblClick);
            };
        }
    }, [mapReady]);
    
    return (
        <div 
            ref={mapContainer} 
            className="map-container" 
            style={{ height: '500px' }}
            onClick={() => {
                if (mapReady && selectedTrajectoryId === 'All Trajectories') {
                    map.current.on('click', handleTrajectoryClick);
                }
                
            }}
            onDoubleClick={() => {
                if (mapReady && selectedTrajectoryId !== 'All Trajectories') {
                    map.current.on('dblclick', handleDblClick);
                }
                
            }}
        >
            
        </div>
    );
}
import React, { useEffect, useState, useRef, turf } from 'react';
import mapboxgl from 'mapbox-gl';

mapboxgl.accessToken = 'pk.eyJ1Ijoidmllbm5hcCIsImEiOiJjbHg5NjR4cWgwbjB4MmtwajRlZ2RucXU3In0.eJuij93s8bNLip5GyM85dA';

export default function Map({trajectoryList, selectedTrajectoryId}){
    const mapContainer = useRef(null);
    const map = useRef(null);
    const [mapReady, setMapReady] = useState(false);

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
                    // Do nothing. 
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
            console.log(GeoJSON);
            map.current.getSource('my-route').setData(GeoJSON);
        }
        // add a new layer for the selected trajectory id on top ??
    }, [mapReady, trajectoryList, selectedTrajectoryId]);

    // map.current.on('click', (event) => {
    //     const features = map.queryRenderedFeatures(event.point, {
    //         layers: ['my-route-layer']
    //     });
        
    //     if (!features.length) {
    //         return; 
    //     }

    //     const feature = features[0]; 
    //     console.log(feature); 
    // });

    return (
        <div ref={mapContainer} className="map-container" style= {{ height: '500px' }}></div>
    );
}
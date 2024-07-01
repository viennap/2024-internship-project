import React, { useEffect, useState, useRef } from 'react';
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
          zoom: 5
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
                    'line-color': ['to-string', ['get', 'myColorProperty']],
                    'line-width': 5,
                    'line-opacity': 0.8,
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
                route["animate"] = true;
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

                if (selectedTrajectoryId === "NONE") {
                    // Do nothing. 
                }
                else if (id === selectedTrajectoryId) {
                    color = '#FFFF00';
                }
                else {
                    color = '#000000';
                }

                console.log(route); 

                route["properties"]["myColorProperty"] = color;

                GeoJSON["features"].push(route);
            }
            map.current.getSource('my-route').setData(GeoJSON);
        }

    }, [mapReady, trajectoryList, selectedTrajectoryId]);

    return (
        <div ref={mapContainer} className="map-container" style= {{ height: '500px' }}></div>
    );
}
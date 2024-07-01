import React, { useEffect, useState, useRef } from 'react';
import mapboxgl from 'mapbox-gl';

mapboxgl.accessToken = 'pk.eyJ1Ijoidmllbm5hcCIsImEiOiJjbHg5NjR4cWgwbjB4MmtwajRlZ2RucXU3In0.eJuij93s8bNLip5GyM85dA';

export default function Map({trajectoryList, selectedTrajectoryId}){
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

        map.current.on('load', async () => {
            let GeoJSON = {};

            GeoJSON["features"] = [];
            GeoJSON["properties"] = {};
            GeoJSON["type"] = "FeatureCollection";

            map.current.addSource('my-route', {
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
        });
    });

    useEffect(() => {
        let GeoJSON = {};
        GeoJSON["features"] = [];
        GeoJSON["properties"] = {};
        GeoJSON["type"] = "FeatureCollection";

        console.log(GeoJSON);

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
            
            route["properties"]["myColorProperty"] = color;

            GeoJSON["features"].push(route);
        }


        map.current.getSource('my-route').setData(GeoJSON);

    }, [trajectoryList, selectedTrajectoryId]);

    return (
        <div ref={mapContainer} className="map-container" style= {{ height: '500px' }}></div>
    );
}
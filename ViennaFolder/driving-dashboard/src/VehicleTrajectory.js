import React, { useEffect, useState } from 'react';
import mapboxgl from 'mapbox-gl';

mapboxgl.accessToken = 'pk.eyJ1Ijoidmllbm5hcCIsImEiOiJjbHg5NjR4cWgwbjB4MmtwajRlZ2RucXU3In0.eJuij93s8bNLip5GyM85dA';

export default function VehicleTrajectory() {
    const [map, setMap] = useState(null); // initialize map once
    const [trajectoryId, setTrajectoryId] = useState('');

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

    }, [])
    
    const createPlot = () => {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'https://ransom.isis.vanderbilt.edu/ViennaFolder/endpoints_python/get_vehicle_trajectory?trajectory_id=2021-04-03-21-51-37_2T3Y1RFV8KC014025');
        xhr.onload = function () {
        
        let GeoJSON = {};

        if (xhr.status === 200) {
            const parsed = JSON.parse(xhr.responseText);
            console.log(parsed); 
            // map.setCenter([parsed['longitude'].at(-1), parsed['latitude'].at(-1)]);
            // map.setZoom(15);

            // GeoJSON["features"] = [];
            // GeoJSON["properties"] = {};
            // GeoJSON["type"] = "FeatureCollection";

            // let route = {};
            // route["type"] = "Feature";
            // route["properties"] = {};
            // route["geometry"] = {};

            // route["geometry"]["type"] = "MultiLineString";
            // route["geometry"]["coordinates"] = [[]];

            // for (let i = 0; i < parsed['latitude'].length; i++) {
            //     let pair = [parsed['longitude'].at(i), parsed['latitude'].at(i)];
            //     route["geometry"]["coordinates"][0].push(pair); 
            // }

            // GeoJSON["features"].push(route); 

            // map.getSource('my-route').setData(GeoJSON);                  
        } 
        else {
            console.log('Error fetching data.')
        }
    };
        xhr.send();
    }
    
    return (
        <div>
            {createPlot()}
          <div id = 'map' style={{ position: 'absolute', left: 20, right: 20, top: 20, bottom: 10, width: '49%' }}/>
        </div>
    );
}
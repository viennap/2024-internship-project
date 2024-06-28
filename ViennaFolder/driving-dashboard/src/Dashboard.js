import React, { useEffect, useState, useRef } from 'react';
import mapboxgl from 'mapbox-gl';
import VehicleTrajectoryLists from './VehicleTrajectoryLists.js';

export default function Dashboard() {
    const [trajectoryList, setTrajectoryList] = useState({});
    const [selectedTrajectoryId, setSelectedTrajectoryId] = useState('');

    return (
        <div>
            <div>
                <VehicleTrajectoryLists 
                    trajectoryListSetter = {setTrajectoryList} 
                    selectedTrajectoryIdSetter = {setSelectedTrajectoryId}
                    />
            </div>

            {/* Generating charts */}

            {/* <div>
                <div>
                    <VehicleTrajectoryLists 
                        trajectoryList = {trajectoryList}
                        selectedTrajectoryId = {selectedTrajectoryId}
                        map = {map}
                        mapContainer = {mapContainer}
                    />
                </div>
            </div> */}

        </div>
        
    );
}

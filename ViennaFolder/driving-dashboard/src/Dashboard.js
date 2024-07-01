import React, { useState, useCallback } from 'react';
import mapboxgl from 'mapbox-gl';
import VehicleTrajectoryLists from './VehicleTrajectoryLists.js';
import Map from './Map.js';

export default function Dashboard() {
    const [trajectoryList, setTrajectoryList] = useState({});
    const [selectedTrajectoryId, setSelectedTrajectoryId] = useState('');   

    return (
        <div>
            <div>
                <VehicleTrajectoryLists 
                    trajectoryListSetter = {setTrajectoryList} 
                    selectedTrajectoryIdSetter = {setSelectedTrajectoryId} 
                    trajectoryList = {trajectoryList}
                    selectedTrajectoryId = {selectedTrajectoryId} />
            </div>

            <div>
                <Map trajectoryList = {trajectoryList} selectedTrajectoryId = {selectedTrajectoryId}/>
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

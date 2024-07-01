import React, { useState } from 'react';
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

        </div>
        
    );
}

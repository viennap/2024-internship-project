import React, { useState } from 'react';
import VehicleTrajectoryLists from './VehicleTrajectoryLists.js';
import Map from './Map.js';

import VehicleSpeed from './VehicleSpeed';
import VehicleSteer from './VehicleSteer';

import './dashboard.css';

import { Stack, Container, Grid, Typography, FormControl, Select, TextField, Button, MenuItem, Box } from '@mui/material';

export default function Dashboard() {
    const [trajectoryList, setTrajectoryList] = useState({});
    const [selectedTrajectoryId, setSelectedTrajectoryId] = useState('');   
    const [markedTimestamp, setMarkedTimestamp] = useState(0); 

    const handleTrajectoryClick = (trajectoryId) => {
        setSelectedTrajectoryId(trajectoryId);
    };

    const handleMarkerDrop = (markedTimestamp) => {
        setMarkedTimestamp(markedTimestamp);
    };

    return (
        <Grid padding = {2}>
            <Grid item xs={12} sm = {12} mb = {2}>
                <VehicleTrajectoryLists 
                    trajectoryListSetter={setTrajectoryList} 
                    selectedTrajectoryIdSetter={setSelectedTrajectoryId} 
                    trajectoryList={trajectoryList}
                    selectedTrajectoryId={selectedTrajectoryId} 
                />
            </Grid>
                
            <Grid container spacing={2}>
                <Grid item xs={12} sm={6}> 
                    <Map 
                        trajectoryList={trajectoryList} 
                        selectedTrajectoryId={selectedTrajectoryId}
                        onTrajectoryClick={handleTrajectoryClick}
                        onMarkerDrop={handleMarkerDrop}
                        />
                </Grid>    
                
                <Grid item xs={12} sm={6}>
                     <Stack spacing={2}>
                       <VehicleSpeed 
                            selectedTrajectoryId={selectedTrajectoryId}
                            markedTimestamp={markedTimestamp}
                        />
                        <VehicleSteer 
                            selectedTrajectoryId={selectedTrajectoryId} 
                            markedTimestamp={markedTimestamp}
                        />
                    </Stack>
                </Grid>
            </Grid>
        </Grid>
    );
}

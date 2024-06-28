import React, { useState } from 'react';

export default function Dashboard({
    setStartTime, 
    setEndTime, 
    setBottomLeftLat, 
    setBottomLeftLong, 
    setTopRightLat, 
    setTopRightLong, 
    fetchTrajectoryList, 
    trajectoryList, 
    selectedTrajectoryId }) {

    const [selectedTrajectoryId, setSelectedTrajectoryId] = useState('');
    const [trajectoryList, setTrajectoryList] = useState([]);
    
    const [startTime, setStartTime] = useState('1577936331'); // January 1, 2020
    const [endTime, setEndTime] = useState('1704166731'); // January 1, 2024
    
    const [bottomLeftLat, setBottomLeftLat] = useState('30');
    const [bottomLeftLong, setBottomLeftLong] = useState('-90');
    const [topRightLat, setTopRightLat] = useState('40');
    const [topRightLong, setTopRightLong] = useState('-80');

    return (
        <div>
            <label>
                Start Time:
                <input type="text" onChange={(e) => setStartTime(e.target.value)} />
            </label>

            <label>
                End Time:
                <input type="text" onChange={(e) => setEndTime(e.target.value)} />
            </label>

            <label>
                Bottom Left Longitude:
                <input type="text" onChange={(e) => setBottomLeftLong(e.target.value)} />
            </label>
            
            <label>
                Bottom Left Latitude:
                <input type="text" onChange={(e) => setBottomLeftLat(e.target.value)} />
            </label>

            <label>
                Top Right Longitude:
                <input type="text" onChange={(e) => setTopRightLong(e.target.value)} />
            </label>

            <label>
                Top Right Latitude:
                <input type="text" onChange={(e) => setTopRightLat(e.target.value)} />
            </label>
            
            <button onClick={fetchTrajectoryList}>Fetch Trajectories</button>
        </div>
    );
}

import React, { useState, useRef } from 'react';
import mapboxgl from 'mapbox-gl';
import './styles.css';

import { Container, Grid, Typography, FormControl, Select, TextField, Button, MenuItem, Box } from '@mui/material';

mapboxgl.accessToken = 'pk.eyJ1Ijoidmllbm5hcCIsImEiOiJjbHg5NjR4cWgwbjB4MmtwajRlZ2RucXU3In0.eJuij93s8bNLip5GyM85dA';

export default function VehicleTrajectory({trajectoryListSetter, selectedTrajectoryIdSetter, trajectoryList, selectedTrajectoryId}) {
    
    const [startTime, setStartTime] = useState('1577936331'); // January 1, 2020
    const [endTime, setEndTime] = useState('1704166731'); // January 1, 2024
    
    const [bottomLeftLat, setBottomLeftLat] = useState('30');
    const [bottomLeftLong, setBottomLeftLong] = useState('-90');
    const [topRightLat, setTopRightLat] = useState('40');
    const [topRightLong, setTopRightLong] = useState('-80');

    const mapContainer = useRef(null);
    const map = useRef(null);

    const fetchTrajectoryList = () => {
        const xhr = new XMLHttpRequest();

        let url = `https://ransom.isis.vanderbilt.edu/ViennaFolder/endpoints_python/get_trajectory_lists?start_time=${startTime}&end_time=${endTime}&bottom_left_lat=${bottomLeftLat}&bottom_left_long=${bottomLeftLong}&top_right_lat=${topRightLat}&top_right_long=${topRightLong}`;
       
        xhr.open('GET', url);
        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                let trajectories = response['trajectories'];
                let idList = Object.keys(trajectories);
                if (idList.length !== 0) {
                    selectedTrajectoryIdSetter("All Trajectories");   
                    // selectedTrajectoryIdSetter(idList[0]); 
                    plotTrajectories(trajectories, idList);     
                }
                else {
                    selectedTrajectoryIdSetter('No valid trajectories.');
                }       
            } else {
                console.log('Error fetching trajectory list.');
            }
        };
        xhr.send();
    };

    const plotTrajectories = (trajectories, idList) => {
        let promises = [];

        idList.forEach((id) => {
            promises.push(fetch(`https://ransom.isis.vanderbilt.edu/ViennaFolder/endpoints_python/get_vehicle_trajectory?trajectory_id=${id}`));
        });

        Promise.all(promises).then(function(...args) {
            let argsArray = args[0];
            
            let jsonPromises = [];
            argsArray.forEach((response) => {
                if (response.ok) {
                    let trajectoryResponse = response.json();
                    jsonPromises.push(trajectoryResponse);
                }
            });
            Promise.all(jsonPromises).then(function (...jsonArgs) {
                let set = false; 
                jsonArgs[0].forEach((obj) => {
                   trajectories[obj["id"]]["latitude"] = obj["latitude"];
                   trajectories[obj["id"]]["longitude"] = obj["longitude"];
                })
            }).then(function () {
                console.log(trajectories);
                trajectoryListSetter(trajectories);
            });
        }).catch(function (error) {
            console.log("Doh!");
            console.log(error);
        });
    };
    
    const handleSelectChange = (event) => {
        selectedTrajectoryIdSetter(event.target.value);
        console.log("Selected trajectory: " + selectedTrajectoryId);
        if (event.target.value === "All Trajectories") {
            fetchTrajectoryList(); 
        }
    };
    
    const createSelectOptions = () => {
      // if trajectory list is NOT of length 0, then also add "display all trajectories" option at top
        let options = [];
        if (Object.entries(trajectoryList).length !== 0) {
          options.push(
            <MenuItem key={"All Trajectories"} value={"All Trajectories"}>
               All Trajectories
            </MenuItem>
          );
        }
        for (const [id, trajectory] of Object.entries(trajectoryList)) {
          options.push(
              <MenuItem key={id} value={id}>
                  {id}
              </MenuItem>
          );
        }
        return options;
    };


    return (
        <Box sx={{ maxWidth: '100%', p: 2, backgroundColor: '#ECECEC'}}>
          <Grid container spacing={2} alignItems="center">
            <Grid item xs={12}>
              <FormControl fullWidth size="small">
                <Typography variant="caption" gutterBottom>
                  Select Trajectory
                </Typography>
                <Select
                  id="trajectory-select"
                  value={selectedTrajectoryId}
                  onChange={handleSelectChange}
                  size="small"
                >
                  {createSelectOptions()}
                </Select>
              </FormControl>
            </Grid>
      
            <Grid item xs={6} sm={2}>
              <TextField
                fullWidth
                label="Start Time"
                color="secondary"
                variant="outlined"
                size="small"
                value={startTime}
                onChange={(event) => setStartTime(event.target.value)}
              />
            </Grid>
      
            <Grid item xs={6} sm={2}>
              <TextField
                fullWidth
                label="End Time"
                color="secondary"
                variant="outlined"
                size="small"
                value={endTime}
                onChange={(event) => setEndTime(event.target.value)}
              />
            </Grid>
      
            <Grid item xs={6} sm={2}>
              <TextField
                fullWidth
                label="Bottom Left Long"
                color="secondary"
                variant="outlined"
                size="small"
                value={bottomLeftLong}
                onChange={(event) => setBottomLeftLong(event.target.value)}
              />
            </Grid>
      
            <Grid item xs={6} sm={2}>
              <TextField
                fullWidth
                label="Bottom Left Lat"
                color="secondary"
                variant="outlined"
                size="small"
                value={bottomLeftLat}
                onChange={(event) => setBottomLeftLat(event.target.value)}
              />
            </Grid>
      
            <Grid item xs={6} sm={2}>
              <TextField
                fullWidth
                label="Top Right Long"
                color="secondary"
                variant="outlined"
                size="small"
                value={topRightLong}
                onChange={(event) => setTopRightLong(event.target.value)}
              />
            </Grid>
      
            <Grid item xs={6} sm={2}>
              <TextField
                fullWidth
                label="Top Right Lat"
                color="secondary"
                variant="outlined"
                size="small"
                value={topRightLat}
                onChange={(event) => setTopRightLat(event.target.value)}
              />
            </Grid>
      
            <Grid item xs={12} sm={3}>
              <Button variant="contained" color="primary" onClick={fetchTrajectoryList} fullWidth size="small">
                Fetch Trajectories
              </Button>
            </Grid>
          </Grid>
        </Box>
      );
}

import React from 'react';
import ReactDOM from 'react-dom';
import 'mapbox-gl/dist/mapbox-gl.css';
import VehicleTrajectory from './VehicleTrajectory';
import VehicleSteer from './VehicleSteer'

ReactDOM.render(
  <React.StrictMode>
    <VehicleTrajectory />
    {/* <VehicleSteer /> */}
  </React.StrictMode>,
  document.getElementById('root')
);
import React from 'react';
import ReactDOM from 'react-dom';
import 'mapbox-gl/dist/mapbox-gl.css';
import VehicleTrajectory from './VehicleTrajectory';

ReactDOM.render(
  <React.StrictMode>
    <VehicleTrajectory />
  </React.StrictMode>,
  document.getElementById('root')
);
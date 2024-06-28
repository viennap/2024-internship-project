import React from 'react';
import ReactDOM from 'react-dom';
import './styles.css';
import VehicleSteer from './VehicleSteer';
import VehicleSpeed from './VehicleSpeed';
import VehicleTrajectory from './VehicleTrajectory';
import VehicleTrajectoryLists from './VehicleTrajectoryLists';
import Dashboard from './Dashboard';

export default function App() {
    return (
        // <div> <VehicleTrajectoryLists/> </div>
        
        // Handling changing trajectory ids from the App dashboard so the signals 
        // can be updated correspondingly

        // Refactor VehicleTrajectory to initialize map first
        // VehicleTrajectory.js, VehicleSpeed.js, and VehicleSteer.js should take in trajectory id as input
        <Dashboard />
        // <div className="container">
        //     <div className="left">
                
        //     </div>
        //     <div className="right">
        //         <div className="item">
        //             <VehicleSteer />
        //         </div>
        //         <div className="item">
        //             <VehicleSpeed />
        //         </div>
        //     </div>
        // </div>
    );
}

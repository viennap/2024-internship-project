import React from 'react';
import ReactDOM from 'react-dom';
import './styles.css';
import VehicleSteer from './VehicleSteer';
import VehicleSpeed from './VehicleSpeed';
import VehicleTrajectory from './VehicleTrajectory';
import VehicleTrajectoryLists from './VehicleTrajectoryLists';

export default function App() {
    return (
        <div> <VehicleTrajectoryLists/> </div>
        
        // <div className="container">
        //     <div className="left">
        //         <VehicleTrajectory />
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

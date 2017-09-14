import React, { Component } from 'react';
import {UberImage} from './uberimage';

  export class Ubernode extends React.Component {
    constructor(props) {
      super(props);

      this.state = {
        expanded: false
      }

      // this.expand = this.expand.bind(this);
    }

    // expand() {
    //   this.setState({expanded: true})
    // }

    render() {
      return(
      <div className="node" >
        <h4>{this.props.title} - {this.props.type}</h4>
        <p><em>Published: {this.props.date}</em></p>
        <p>{this.props.leader}</p>
        <UberImage imageArray={this.props.imageArray} imageTitle={this.props.title} />
        {/* <div className="shorty" dangerouslySetInnerHTML={{__html: this.props.body}}></div> */}
      </div>
      ) 
    }
  }
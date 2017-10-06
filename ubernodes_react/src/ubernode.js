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

    filterTease(input, limit) {
      let dots = input.length > limit ? ' ...' : ''; 
      return input.slice(0, limit) + dots;
    }

    render() {
      return(
      <div className="node" >
        <UberImage imageArray={this.props.imageArray} imageTitle={this.props.title} />
        <h4>{this.props.title} - {this.props.type}</h4>
        <p className="uber-date">Published: {this.props.date}</p>
        <p>{this.filterTease(this.props.leader, 200)}</p>
        {/* <div className="shorty" dangerouslySetInnerHTML={{__html: this.props.body}}></div> */}
      </div>
      ) 
    }
  }
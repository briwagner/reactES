import React, { Component } from 'react';
import {UberImage} from './uberimage';

  export class Ubernode extends React.Component {
    constructor(props) {
      super(props);

      this.state = {
        expanded: false
      }

      this.expand = this.expand.bind(this);
      this.nodeClasses = this.nodeClasses.bind(this);
    }

    // 'Read more' action on click.
    expand() {
      if (this.state.expanded) {
        this.setState({expanded: false})
      } else {
        this.setState({expanded: true})
      }
    }

    // Limit string length for display.
    filterTease(input, limit) {
      let dots = input.length > limit ? ' ...' : ''; 
      return input.slice(0, limit) + dots;
    }

    // Set classes on node container, to hide/show.
    nodeClasses() {
      let classes = "node";
      if (this.state.expanded) {
        classes += " active";
      } else {
        classes += " inactive";
      }
      return classes;
    }

    render() {
      return(
      <div className={this.nodeClasses()} onClick={this.expand}>
        <UberImage imageArray={this.props.imageArray} imageTitle={this.props.title} />
        <h4>{this.props.title} - {this.props.type}</h4>
        <p className="uber-date">Published: {this.props.date}</p>
        <p>{this.filterTease(this.props.leader, 200)}</p>
        <div className="shorty"><div dangerouslySetInnerHTML={{__html: this.props.body}}></div></div>
      </div>
      ) 
    }
  }
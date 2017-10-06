import React, { Component } from 'react';

  export class UberImage extends React.Component {
    render() {
      let firstImage = (this.props.imageArray !== undefined && this.props.imageArray.length > 0) ?
      "https://www.nasa.gov/" + this.props.imageArray[0].lrThumbnail :
        null;
      return <img src={firstImage} alt={this.props.imageTitle} className="uber-image"/>;
    }
  }
import React, { Component } from 'react';
import logo from './logo.svg';
import './App.css';
import {Ubernodes} from './ubernodes';
import {Ubernode} from './ubernode';

class App extends Component {
  constructor(props) {
    super(props);

    this.state = {
      nodeCount: 1,
      nodes: []
    };
  }

  componentWillMount() {
    this.fetchNodes().then(x => {
      this.setState({
        nodeCount: x.total,
        nodes: x.hits
      });
    });
  }

  /* JSON from ES returns inside of json.hits.hits._source. Properties include:
    -title
    -nid
    -type
    -body
    -name
    -uri
    -promoDateTime

    json.hits.total gives count.
  */
  fetchNodes() {
    let query = {
      "from" : 0,
      "size" : 25,
      "query" : {
        "term" : {
          "ubernodeType" : "feature"
        }
      }
    };
    return fetch('http://localhost:9200/ubernodes/ubernode/_search', 
      {method: 'POST', body: JSON.stringify(query)}
    ).then(function(resp) {
        return resp.json();
      })
      .then(function(json) {
        return json.hits;
      });
  }

  formatTime(dateString) {
    let date = new Date(dateString);
    return date.getMonth() + "/" + date.getDate() + "/" + date.getFullYear();
  }

  render() {
    return (
      <div>
        <h1>Ubernodes</h1>
        <h3>Found {this.state.nodeCount} </h3>
        <div className="ubernodes">
          {this.state.nodes.map((item, i) => {
            return (
              <div key={i}>
                <Ubernode title={item._source.title}
                          type={item._source.ubernodeType}
                          date={this.formatTime(item._source.promoDateTime)}
                          leader={item._source.prLeaderSentence}
                          imageArray={item._source.images}
                          body={item._source.body}
                />
              </div>
            )
          })}
        </div>
      </div>
    );
  }
}


export default App;

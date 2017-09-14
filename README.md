# reactES
Import data to local Elasticsearch to support React frontend demo

This includes three components:
  - a php library to fetch and process data from a publicly available API
  - a local Elasticsearch cluster (setup is not included here)
  - React client querying data on local ES
  
Setup:
  - composer install
  - cd ubernodes_react; npm install
  - launch a local Elasticsearch instance on port 9200

Execute runner.php file to fetch data from the api and seed the local Elasticsearch. From the ubernodes_react directory, 'npm start' to launch a server for the React app.

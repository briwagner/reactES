# PHP, ElasticSearch, React
Import data to local Elasticsearch to support React frontend demo

This includes two components:
  - a PHP library to fetch and process data from a publicly available API
  - React client querying data on local ES
  
### Setup:
  - composer install
  - cd ubernodes_react; npm install; npm run-script build

### Run

From the command line, run 'php runner.php' to fetch data from the api and seed the local Elasticsearch.
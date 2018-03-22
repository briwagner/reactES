# PHP, ElasticSearch, React
Import data to local Elasticsearch to support React frontend demo

This includes three components:
  - a PHP library to fetch and process data from a publicly available API
  - a local Elasticsearch cluster (setup is not included here)
  - React client querying data on local ES
  
Setup:
  - composer install
  - cd ubernodes_react; npm install; npm run-script build

Run
From the command line, run 'php runner.php' to fetch data from the api and seed the local Elasticsearch.
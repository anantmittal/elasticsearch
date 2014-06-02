# Welcome to the elasticsearch wiki!

## Insall Elastic Search in Ubuntu 

`cd ~`
`sudo apt-get update`
`sudo apt-get install openjdk-7-jre-headless -y`
`wget https://download.elasticsearch.org/elasticsearch/elasticsearch/elasticsearch-1.1.1.deb`
`sudo dpkg -i elasticsearch-1.1.1.deb`

## Start the service by executing 
`sudo service elasticsearch start`

### Now,
`cd /usr/share/elasticsearch`

## Install JDBC river plugin
`./bin/plugin -url http://bit.ly/10FJhEd -install river-jdbc`

## Download MySQL JDBC driver from http://dev.mysql.com/downloads/connector/j/

### Now,
`unzip mysql-connector-java-5.1.30.zip`
`cp mysql-connector-java-5.1.30-bin.jar /usr/share/elasticsearch/plugins/river-jdbc/` 
`./bin/elasticsearch -f`

## Import table from mysql

`curl -XPUT 'localhost:9200/_river/jdbc/_meta' -d '{"type":"jdbc","jdbc":{"driver":"com.mysql.jdbc.Driver","url":"jdbc:mysql://localhost:3306/database_name","user":"username","password":"password","sql":"select * from table_name;"},"index":{"index" :"jdbc","type" :"jdbc"}}'`

## To select table from elastic search, run this command in terminal
`curl -XGET 'http://localhost:9200/jdbc/_search?q=*'`

## To install a web front end for elasticsearch cluster

`sudo elasticsearch/bin/plugin -install mobz/elasticsearch-head`
open `http://localhost:9200/_plugin/head/`

### Clone this repository and run the index.html in browser. 

This code has been written using PHP api provided by Elastic Search. 
http://www.elasticsearch.org/guide/en/elasticsearch/client/php-api/current/index.html


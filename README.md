# _**My Salon**_

#### _An application for shoe stores, 04/07/2017_

#### _By Xing Li_

## Description

_a program to list out local shoe stores and the brands of shoes they carry._

## Setup

* _clone the repository_
* _change directory to project folder_
* _open terminal and run composer install_
* _root in web folder_
* _try it out_

## Specs

* _add a brand save in database in brands table_
* _able to display brands that were added in database_
* _be able to add a store to a brand_
* _be able to display all brands_
* _be able to add a brand to a store_
* _be able to display how many brands that a store sells_
* _be able to display how many stores sell a brand_
* _be able to delete a brand ant its all relatives_


## MySQL commands

1. _SHOW DATABASES;_
2. _CREATE DATABASE shoes;_
3. _USE shoes;_
4. _CREATE TABLE brand (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, brand VARCHAR(255));_
5. _CREATE TABLE stores (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, store VARCHAR(255));_
6. _CREATE TABLE stores_brands (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, store_id INT, brand_id INT);_

[click here](https://github.com/msuli1120/shoes) to check out the project.

### License
*This application is licensed under Xing Li's name*
copyright (c) 2017 **_Xing Li_**
# shoes

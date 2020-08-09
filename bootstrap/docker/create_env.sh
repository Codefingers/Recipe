#!/bin/bash
set -e

cp .env.example .env
sed "s/DB_HOST=/DB_HOST=${MYSQLHOST}/" .env
sed "s/DB_DATABASE=/DB_DATABASE=${MYSQLDB}/" .env
sed "s/DB_USERNAME=/DB_USERNAME=${MYSQLUSER}/" .env
sed "s/DB_PASSWORD=/DB_PASSWORD=${MYSQLPASS}/" .env

#!/bin/bash
set -e

cp .env.example .env

sed -i "s/DB_HOST=/DB_HOST=${1}/" .env
sed -i "s/DB_DATABASE=/DB_DATABASE=${2}/" .env
sed -i "s/DB_USERNAME=/DB_USERNAME=${3}/" .env
sed -i "s/DB_PASSWORD=/DB_PASSWORD=${4}/" .env

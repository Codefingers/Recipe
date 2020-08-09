#!/bin/bash
set -e

cp .env.example .env

echo ${1}
echo $1

sed "s/DB_HOST=/DB_HOST=${1}/" .env
sed "s/DB_DATABASE=/DB_DATABASE=${2}/" .env
sed "s/DB_USERNAME=/DB_USERNAME=${3}/" .env
sed "s/DB_PASSWORD=/DB_PASSWORD=${4}/" .env

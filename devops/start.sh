#!/bin/bash

export APP_USER_ID=$(id -u)
docker-compose up -d --build
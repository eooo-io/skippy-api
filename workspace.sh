#!/bin/bash

# Check if the container is running
CONTAINER_NAME="skippyapi-workspace"

if [ "$(docker ps -q -f name=$CONTAINER_NAME)" ]; then
    echo "Entering the $CONTAINER_NAME interactive shell..."
    docker exec -it $CONTAINER_NAME bash
else
    echo "Error: The $CONTAINER_NAME container is not running."
    echo "Start the container with: docker-compose up -d"
fi

#!/bin/bash

# Health check script for Koyeb
PORT=${PORT:-8080}

# Check Laravel server
if ! curl -s http://localhost:$PORT/health > /dev/null 2>&1; then
    exit 1
fi

# Optional: Check chatbot server on port 3001
# if ! curl -s http://localhost:3001/health > /dev/null 2>&1; then
#     exit 1
# fi

exit 0

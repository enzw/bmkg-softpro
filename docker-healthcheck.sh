#!/bin/bash
# Health check script for Docker

PORT=${PORT:-8080}
TIMEOUT=${HEALTH_CHECK_TIMEOUT:-10}

echo "🏥 Health check: Checking port $PORT..."

# Check if Laravel server is responding
if curl -sf --connect-timeout 5 "http://localhost:${PORT}/health" > /dev/null 2>&1; then
    echo "✅ Health check passed on port $PORT"
    exit 0
else
    echo "❌ Health check failed on port $PORT"
    exit 1
fi

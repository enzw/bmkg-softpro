#!/bin/bash

# Chatbot startup wrapper script
# This script loads environment variables from Laravel .env file
# and then starts the Node.js chatbot server

set -e

# Get directory of this script
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# Load Laravel .env file if it exists
if [ -f "$SCRIPT_DIR/.env" ]; then
    echo "📄 Loading environment variables from .env..."
    # Export all variables from .env (skip comments and empty lines)
    set -a
    source <(grep -v '^#' "$SCRIPT_DIR/.env" | grep . )
    set +a
fi

# Verify GOOGLE_AI_KEY is set
if [ -z "$GOOGLE_AI_KEY" ]; then
    echo "⚠️  WARNING: GOOGLE_AI_KEY not set in environment"
    echo "   Chatbot server will start but /chat endpoint will return 503"
fi

# Show configuration
echo "🤖 Starting Chatbot Server"
echo "   Port: ${CHATBOT_SERVER_PORT:-3001}"
echo "   Node Environment: ${NODE_ENV:-development}"
echo "   API Key Configured: $([ -n "$GOOGLE_AI_KEY" ] && echo "✅ YES" || echo "❌ NO")"
echo ""

# Start the Node.js server
exec node "$SCRIPT_DIR/chatbot-server.js"

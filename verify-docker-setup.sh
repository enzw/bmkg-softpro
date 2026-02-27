#!/bin/bash

# Docker Setup Verification Script
# Run this after docker-compose up to verify everything is working

set -e

echo "======================================"
echo "🔍 BMKG Docker Setup Verification"
echo "======================================"
echo ""

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[0;33m'
NC='\033[0m' # No Color

check_service() {
    local name=$1
    local port=$2
    local path=${3:-/health}
    
    echo -n "Checking $name (port $port)... "
    
    if timeout 5 curl -f http://localhost:$port$path >/dev/null 2>&1; then
        echo -e "${GREEN}✅ OK${NC}"
        return 0
    else
        echo -e "${RED}❌ FAILED${NC}"
        return 1
    fi
}

check_docker_running() {
    echo -n "Checking Docker daemon... "
    if docker ps >/dev/null 2>&1; then
        echo -e "${GREEN}✅ Running${NC}"
        return 0
    else
        echo -e "${RED}❌ Not running${NC}"
        return 1
    fi
}

check_containers() {
    echo ""
    echo "Checking Docker containers..."
    
    docker-compose ps --all
    
    # Count running containers
    running=$(docker-compose ps | grep -c "Up " || echo 0)
    total=$(docker-compose config --services | wc -l)
    
    echo ""
    if [ $running -eq $total ]; then
        echo -e "${GREEN}✅ All containers running ($running/$total)${NC}"
        return 0
    else
        echo -e "${YELLOW}⚠️  Some containers not running ($running/$total)${NC}"
        return 1
    fi
}

# Main checks
echo "📋 System Checks"
echo "================================"
check_docker_running

echo ""
echo "🐳 Container Status"
echo "================================"
check_containers

echo ""
echo "🌐 Service Health Checks"
echo "================================"

services_ok=0
total_services=0

# Check each service
for service in "PostgreSQL:5432:/" "Laravel:8000:/health" "Chatbot:3001:/" "RAG Service:5000:/health"; do
    IFS=: read -r name port path <<< "$service"
    total_services=$((total_services + 1))
    
    if check_service "$name" "$port" "$path"; then
        services_ok=$((services_ok + 1))
    fi
done

echo ""
echo "📊 Service Summary"
echo "================================"
echo "Services Healthy: $services_ok/$total_services"

if [ $services_ok -eq $total_services ]; then
    echo -e "${GREEN}✅ All services are healthy!${NC}"
else
    echo -e "${YELLOW}⚠️  Some services are not responding${NC}"
    echo ""
    echo "Possible fixes:"
    echo "1. Services may still be starting (wait 1-2 minutes)"
    echo "2. Check logs: docker-compose logs -f app"
    echo "3. Restart services: docker-compose restart"
fi

echo ""
echo "🗄️  Database Check"
echo "================================"
echo -n "Checking PostgreSQL connection... "

if docker-compose exec -T postgres psql -U postgres -d bmkg_softpro -c "\d" >/dev/null 2>&1; then
    echo -e "${GREEN}✅ Connected${NC}"
    
    echo -n "Checking RAG tables exist... "
    if docker-compose exec -T postgres psql -U postgres -d bmkg_softpro -c "SELECT COUNT(*) FROM documents" >/dev/null 2>&1; then
        echo -e "${GREEN}✅ Tables exist${NC}"
    else
        echo -e "${YELLOW}⚠️  Tables might not be created yet${NC}"
        echo "   Run: docker-compose exec app python initialize_rag.py"
    fi
else
    echo -e "${RED}❌ Cannot connect to PostgreSQL${NC}"
fi

echo ""
echo "📝 Useful Commands"
echo "================================"
echo "View all logs:        docker-compose logs -f"
echo "View app logs:        docker-compose logs -f app"
echo "View RAG logs:        docker-compose logs app | grep rag"
echo "Enter container:      docker-compose exec app bash"
echo "Check supervisor:     docker-compose exec app supervisorctl status"
echo "Restart services:     docker-compose restart"
echo "Stop everything:      docker-compose down"
echo "View container info:  docker-compose ps"
echo ""

# Test RAG if available
if timeout 5 curl -f http://localhost:5000/health >/dev/null 2>&1; then
    echo "🤖 Test RAG Service"
    echo "================================"
    echo "Testing RAG endpoint..."
    
    response=$(curl -s -X POST http://localhost:5000/api/rag/chat \
        -H "Content-Type: application/json" \
        -d '{"query": "Test query"}' || echo "")
    
    if [ ! -z "$response" ]; then
        echo -e "${GREEN}✅ RAG Service is responding${NC}"
        echo "Response preview: ${response:0:100}..."
    else
        echo -e "${YELLOW}⚠️  RAG Service responded but no content${NC}"
    fi
    echo ""
fi

echo "✅ Verification Complete!"
echo ""
echo "📖 Next steps:"
echo "- Local Testing: See DOCKER_QUICKSTART.md"
echo "- Full Deployment: See DOCKER_DEPLOYMENT.md"
echo "- Troubleshooting: See DOCKER_DEPLOYMENT.md#troubleshooting"

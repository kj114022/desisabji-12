#!/bin/bash

# DesiSabji Full Stack Development Setup Script
# This script sets up both Laravel backend and Angular frontend

echo "🚀 DesiSabji Development Environment Setup"
echo "========================================="

# Get the directory where this script is located
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
PROJECT_DIR="$( cd "$SCRIPT_DIR/.." && pwd )"

echo ""
echo "📁 Project Location: $PROJECT_DIR"

# Step 1: Setup Laravel Backend
echo ""
echo "1️⃣  Setting up Laravel Backend..."
echo "================================"

cd "$SCRIPT_DIR"

# Check if .env exists
if [ ! -f ".env" ]; then
    echo "⚠️  .env file not found. Copying from .env.example..."
    cp .env.example .env
    php artisan key:generate
fi

# Install composer dependencies
echo "📦 Installing Composer dependencies..."
if command -v composer &> /dev/null; then
    composer install --no-interaction
else
    echo "⚠️  Composer not found. Please install Composer first."
fi

echo "✅ Laravel Backend setup complete!"
echo ""
echo "To start Laravel backend, run:"
echo "  cd $SCRIPT_DIR"
echo "  php artisan serve"
echo ""

# Step 2: Setup Angular Frontend
FRONTEND_DIR="$PROJECT_DIR/desisabji-frontend"

echo ""
echo "2️⃣  Setting up Angular Frontend..."
echo "================================"

if [ -d "$FRONTEND_DIR" ]; then
    echo "📁 Angular project already exists at $FRONTEND_DIR"
    cd "$FRONTEND_DIR"
    
    if [ ! -d "node_modules" ]; then
        echo "📦 Installing npm dependencies..."
        npm install
    fi
    
    echo "✅ Angular Frontend setup complete!"
else
    echo "❌ Angular project not found at $FRONTEND_DIR"
    echo ""
    echo "To create the Angular frontend, run:"
    echo "  cd $PROJECT_DIR"
    echo "  ng new desisabji-frontend --routing --style=scss"
    echo ""
fi

# Summary
echo ""
echo "🎉 Setup Complete!"
echo "=================="
echo ""
echo "📝 Next Steps:"
echo ""
echo "1. Start Laravel Backend (Terminal 1):"
echo "   cd $SCRIPT_DIR"
echo "   php artisan serve"
echo ""
echo "2. Start Angular Frontend (Terminal 2):"
echo "   cd $FRONTEND_DIR"
echo "   ng serve"
echo ""
echo "3. Open your browser:"
echo "   Frontend: http://localhost:4200"
echo "   Backend API: http://localhost:8000/api"
echo ""
echo "📖 For more details, see:"
echo "   - $SCRIPT_DIR/PRODUCTION_READINESS_SUMMARY.md"
echo "   - $SCRIPT_DIR/ANGULAR_SETUP_GUIDE.md"
echo ""

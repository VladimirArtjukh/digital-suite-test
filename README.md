# Task Manager

A simple Task Manager app with Laravel 12 (backend) and Vue.js 3 (frontend).

## Prerequisites
- Docker Desktop
- Node.js and npm

## Setup
1. Clone the repository:
   ```bash
   git clone https://github.com/VladimirArtjukh/digital-suite-test
   cd digital-suite-test
   
2. Install dependencies and setup:
   ```bash
    make install
   
3. Start the application
   ```bash
    make up

## Environment Variables
Copy .env.example to .env and adjust if needed. Default Sail settings work out of the box.

## Features
User registration and login with Sanctum tokens.
CRUD operations for tasks (title, description, status).
Basic Tailwind-styled Vue frontend.
All logic files (Controllers, Models, Services etc.) are in the Modules folder


## Running Tests
   ```bash
   make test


@echo off
REM ============================================================================
REM BMKG SoftPro - Project Runner Batch File
REM Starts all necessary services: Laravel, Vite, and Flask RAG Server
REM ============================================================================

setlocal enabledelayedexpansion
chcp 65001 > nul

REM Colors for console output
for /F %%A in ('copy /Z "%~f0" nul') do set "BS=%%A"

echo.
echo ============================================================================
echo  BMKG SoftPro - Project Startup
echo ============================================================================
echo.

REM Get the project directory
set "PROJECT_DIR=%~dp0"
cd /d "%PROJECT_DIR%"

REM Check if Node.js is installed
echo [*] Checking dependencies...
where node >nul 2>nul
if errorlevel 1 (
    echo [!] Node.js is not installed or not in PATH
    echo [!] Please install Node.js from https://nodejs.org/
    pause
    exit /b 1
)

REM Check if PHP is installed
where php >nul 2>nul
if errorlevel 1 (
    echo [!] PHP is not installed or not in PATH
    echo [!] Please install PHP or use Laragon/XAMPP
    pause
    exit /b 1
)

REM Check if Python is installed (optional, for RAG service)
where python >nul 2>nul
set PYTHON_INSTALLED=0
if not errorlevel 1 set PYTHON_INSTALLED=1

echo [✓] Node.js found
echo [✓] PHP found
if %PYTHON_INSTALLED% equ 1 echo [✓] Python found

REM Install/update PHP dependencies
if not exist "vendor\" (
    echo.
    echo [*] Installing PHP dependencies...
    call composer install
) else (
    echo [✓] PHP dependencies already installed
)

REM Install/update Node dependencies
if not exist "node_modules\" (
    echo.
    echo [*] Installing Node.js dependencies...
    call npm install
) else (
    echo [✓] Node.js dependencies already installed
)

REM Install Python dependencies if Python is available
if %PYTHON_INSTALLED% equ 1 (
    if not exist "python\venv\" (
        echo.
        echo [*] Setting up Python virtual environment...
        cd python
        python -m venv venv
        call venv\Scripts\activate.bat
        pip install -r requirements.txt
        deactivate
        cd ..
    ) else (
        echo [✓] Python virtual environment already set up
    )
)

REM Create .env file if it doesn't exist
if not exist ".env" (
    echo.
    echo [*] Creating .env file from .env.example...
    if exist ".env.example" (
        copy .env.example .env
    ) else (
        echo [!] Warning: .env.example not found, using defaults
    )
)

REM Clear Laravel cache
echo.
echo [*] Clearing Laravel cache...
php artisan config:clear >nul 2>nul
php artisan cache:clear >nul 2>nul
php artisan view:clear >nul 2>nul

REM Run migrations if database exists
echo [*] Running database migrations...
php artisan migrate --force >nul 2>nul

REM ============================================================================
REM Start all services
REM ============================================================================
echo.
echo ============================================================================
echo  Starting Services...
echo ============================================================================
echo.
echo [*] Launching Laravel development server...
echo [*] Launching Vite development server...
if %PYTHON_INSTALLED% equ 1 echo [*] Launching Flask RAG server...
echo.
echo Press Ctrl+C to stop all services
echo.

REM Create a temporary script to run all services
set "TEMP_START_SCRIPT=%TEMP%\start_services_%RANDOM%.bat"

(
    echo @echo off
    echo setlocal enabledelayedexpansion
    echo color 0A
    echo title BMKG SoftPro Services
    echo cd /d "%PROJECT_DIR%"
    echo echo.
    echo echo ============================================================================
    echo echo Services are running. Press Ctrl+C to stop all services.
    echo echo ============================================================================
    echo echo.
    echo echo Laravel:    http://localhost:8000
    echo echo Vite Dev:   http://localhost:5173
    if %PYTHON_INSTALLED% equ 1 echo echo Flask RAG:  http://localhost:5000
    echo echo.
    echo echo [1] Laravel Server
    echo echo [2] Vite Dev Server
    echo if %PYTHON_INSTALLED% equ 1 echo [3] Flask RAG Server
    echo echo.
    echo.
    echo start "Laravel Server" /B php artisan serve
    echo start "Vite Dev Server" /B npm run dev
    if %PYTHON_INSTALLED% equ 1 (
        echo start "Flask RAG Server" /B cmd /c "cd python && venv\Scripts\activate.bat && python app.py"
    )
    echo.
    echo :wait
    echo timeout /t 1 /nobreak >nul
    echo goto wait
) > "%TEMP_START_SCRIPT%"

REM Run the temporary script
call "%TEMP_START_SCRIPT%"

REM Cleanup
del "%TEMP_START_SCRIPT%"

endlocal
pause
exit /b 0

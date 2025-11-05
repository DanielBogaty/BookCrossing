@echo off
echo Starting PHP Development Server from public/ directory...
echo.
echo Server will be available at: http://localhost:8000
echo.
echo Press Ctrl+C to stop the server
echo.
cd public
php -S localhost:8000 router.php
cd ..


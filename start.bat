@echo off
REM Set root folder name
set ROOT=flyhub_erp

REM Top-level
mkdir "%ROOT%"
mkdir "%ROOT%\public"
mkdir "%ROOT%\src"
mkdir "%ROOT%\includes"
mkdir "%ROOT%\scripts"
mkdir "%ROOT%\logs"
mkdir "%ROOT%\tests"
mkdir "%ROOT%\vendor"

REM public/assets subfolders
mkdir "%ROOT%\public\assets"
mkdir "%ROOT%\public\assets\css"
mkdir "%ROOT%\public\assets\js"
mkdir "%ROOT%\public\assets\images"

REM src subfolders
mkdir "%ROOT%\src\config"
mkdir "%ROOT%\src\controllers"
mkdir "%ROOT%\src\models"
mkdir "%ROOT%\src\views"
mkdir "%ROOT%\src\middleware"

REM includes
REM (db.php, header.php, footer.php, utils.php will go here)

REM scripts
REM (e.g. migrate.php)

REM logs
REM (error.log will be created at runtime)

echo Folder structure created under "%ROOT%"
pause
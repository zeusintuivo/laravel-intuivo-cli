@ECHO OFF
rem for /f "delims=" %%a in ('git rev-parse --abbrev-ref HEAD') do @set THISBRANCH=%%a
php "pull"

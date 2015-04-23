@ECHO OFF
for /f "delims=" %%a in ('git rev-parse --abbrev-ref HEAD') do @set pullcurrentbranch=%%a
git fetch
git pull origin %pullcurrentbranch%
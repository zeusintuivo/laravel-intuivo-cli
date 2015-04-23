@ECHO OFF
for /f "delims=" %%a in ('git rev-parse --abbrev-ref HEAD') do @set updatemecurrentbranch=%%a
echo "From branch %updatemecurrentbranch%"
call master
call pull
echo "Back into branch %updatemecurrentbranch%"
call branch %updatemecurrentbranch%
call merge master
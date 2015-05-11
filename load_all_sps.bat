@ECHO OFF
for /f "delims=" %%a in ('php "mysql_connectors"') do @set THISCONNECTOR=%%a
rem  %THISCONNECTOR%

cd database/sp

echo Recursively import SQL files

find . -name '*.sql' | gawk '{ print "source",$0 }' | mysql  %THISCONNECTOR% --batch
cd ..
cd ..

@ECHO OFF

rem @author Jesus Alcaraz <jesus@gammapartners.com>

rem batch shell script


set TABLE=%1

for /f "delims=" %%a in ('php "mysql_connectors"') do @set THISCONNECTOR=%%a
rem  %THISCONNECTOR%


set THISSQL="SHOW TABLES;"


rem display what this does
color 06
echo mysql  %THISCONNECTOR% -e '%THISSQL%'

color 02
rem echo "${green}  "

mysql %THISCONNECTOR% -e %THISSQL%




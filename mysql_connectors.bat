@ECHO OFF
rem this
for /f "delims=" %%a in ('php "mysql_connectors"') do @set THISCONNECTOR=%%a
echo %THISCONNECTOR%
rem php "mysql_connectors"
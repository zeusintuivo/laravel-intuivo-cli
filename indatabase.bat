@ECHO OFF
for /f "delims=" %%a in ('php "mysql_connectors"') do @set THISCONNECTOR=%%a
echo %THISCONNECTOR%
rem php "indatabase" 
call mysql %THISCONNECTOR%


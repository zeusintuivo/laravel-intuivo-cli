@ECHO OFF

rem @author Jesus Alcaraz <jesus@gammpartners.com>

rem batch shell script check input argument

if "%1"=="" (
  	echo .
    echo Missing 1st argument 
    echo .
	echo Sample Usage:    - expects one arguments 
	echo .
	echo     select table 
 	echo .
 	call showtables
 	goto END
) 


set TABLE=%1;

for /f "delims=" %%a in ('php "mysql_connectors"') do @set THISCONNECTOR=%%a
rem  %THISCONNECTOR%


set THISSQL="select * from %TABLE%"


rem display what this does
color 06
echo mysql  %THISCONNECTOR% -e %THISSQL%

color 02
rem echo "${green}  "

mysql %THISCONNECTOR% -e %THISSQL%

:END




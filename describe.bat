@ECHO OFF

rem @author Jesus Alcaraz <jesus@gammapartners.com>

rem batch shell script check input argument

IF [%1]==[](
  	echo " "
    echo "Missing 1st argument "
    echo " "
	echo "Sample Usage:    - expects one arguments "
	echo " "
	echo "    select table "
 	echo "    ";
 	call showtables;
 	exit
) 


set TABLE=%1;

for /f "delims=" %%a in ('php "mysql_connectors"') do @set THISCONNECTOR=%%a
rem  %THISCONNECTOR%


THISSQL="SELECT column_name FROM information_schema.columns WHERE table_name='%TABLE%';";



rem display what this does
color 06
echo "mysql  %THISCONNECTOR% -e '%THISSQL%'";

color 02
echo "${green}  "; 

mysql %THISCONNECTOR% -e "%THISSQL%"






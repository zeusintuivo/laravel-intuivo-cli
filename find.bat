@ECHO OFF

rem bash shell script check input argument
IF [%1] == []  (
  	echo " "
    echo "Missing 1st argument "
    echo " "
	echo "Sample Usage:    - expects one arguments "
	echo " "
	echo "    find seek_this "
	echo "    "
	goto exit:
) 



%SEEKING%="%1";


echo "Seek ignoring vendor, bower_components, node_modules, app/Stub and storage/logs with ack "
echo "Finding %SEEKING% " 
echo " "
ag  --ignore-dir=vendor --ignore-dir=bower_components  --ignore-dir=node_modules --ignore-dir=storage/logs --ignore-dir=app/Stubs "%SEEKING%" 
echo " "

exit:
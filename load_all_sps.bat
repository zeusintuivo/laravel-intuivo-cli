@ECHO OFF

cd database/sp
find . -name '*.sql' | gawk '{ print "source",$0 }' | mysql -uroot -ptoor het2 --batch
cd ..
cd ..

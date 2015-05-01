@ECHO OFF

rem add
git add .
git status

rem commit
git commit -am "%*"


rem pull
php "pull"  

rem push 
php "push"

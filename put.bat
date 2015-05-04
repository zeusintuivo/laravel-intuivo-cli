@ECHO OFF

rem add
@echo . 
@echo === Add . ===
@echo . 
git add .
@echo . 
@echo === Status === 
@echo . 
git status
@echo . 

rem commit
@set OPTIONS="%*"
@echo .
@echo === commit -am %OPTIONS% ===
@echo . 
git commit -am "%*"
@echo .

rem pull
@echo . 
@echo === Pull === 
@echo . 
php "pull"  
@echo .

rem push 
@echo . 
@echo === Push === 
@echo . 
php "push"
@echo . 

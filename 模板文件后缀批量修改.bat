@echo off
set num = 0
For /r  . %%i in (*.htm) do (
set /a num += 1
echo 修改：%%i
ren "%%i" *.html) 
echo 共%num%个文件被处理成功
pause>nul
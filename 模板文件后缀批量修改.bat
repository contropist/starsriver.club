@echo off
set num = 0
For /r  . %%i in (*.htm) do (
set /a num += 1
echo �޸ģ�%%i
ren "%%i" *.html) 
echo ��%num%���ļ�������ɹ�
pause>nul
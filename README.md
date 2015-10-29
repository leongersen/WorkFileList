Compile the handler using any compiler, for example [TCC](http://bellard.org/tcc/).    
	
	tcc -o tool.exe ../handler.c

Run `plusplus.reg` to register the `plusplus:` url handler. Modify it with a text editor to change the `tool.exe` location.

Toss `driver.php` on a local server. Call it with a file path a the `?op=` query parameter.

    http://localhost/WorkFileList/driver.php?op=C:\wamp\www\noUiSlider

Click/drag to select files to list. Hit `a` to **a**ccept. Click a file to launch it in Notepad++.

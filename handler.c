#include <Windows.h>

int main(int argc, char *argv[]) {

	if ( argc > 1 ) {

		argv[1][8] = ' '; // Replace the : by a space. http://stackoverflow.com/questions/28176732/createprocessw-not-honoring-commandline

		STARTUPINFO si = {sizeof(STARTUPINFO), 0};
		si.cb = sizeof(si);
		PROCESS_INFORMATION pi = {0};

		if ( CreateProcess("C:\\Program Files (x86)\\Notepad++\\notepad++.exe", argv[1], 0, 0, 0, CREATE_NO_WINDOW, 0, 0, &si, &pi) ) {
			CloseHandle(pi.hThread);
			CloseHandle(pi.hProcess);
		}
	}

    return 0;
}

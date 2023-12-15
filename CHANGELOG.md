## WBCE Forum
This module integrates a simple forum in your [WBCE][2] website.

#### Brief changelog
Details on [GitHub] [3]

##### 0.6.8
- Add preinit and additional class.
- Remove some typos and wrong values in some (headers-)comments.
- Module_home is now WBCE repository (broken link in WB[-org]).
- Frontend-template css support.
- Backend-theme css support.
- Set some db-fields up to 180 chars (was limited to 30).
- Use twig-instance (reference) from WBCE.

##### 0.6.7
- fixes for WBCE 1.6.1 and PHP 8.2.

##### 0.6.6
- fixes for PHP8.1 (Bernd)

##### 0.6.4
- fix issues with rights to post / edit / delete (2nd try)

##### 0.6.3
- fix issues with rights to post / edit / delete

##### 0.6.2
- remove forgotten debug output/exit

##### 0.6.1
- fixed double-escaped POST data in content.php
 
##### 0.6
- added backticks to SQL statements
- some layout tweaks
- removed paranthesis from include* and require*
- removed closing ?> from end-of-file
- reformatted some files using CSFixer
- added missing $admin->print_footer();

##### 0.5.11
- fixes for MySQL-Strict (Bernd)

##### 0.5.10
- Bugfixes inside installer.

##### 0.5.9
- Bugfixes and codechanges for the user handling
- Changes/additions inside the language-files
- Rework forum-search

##### 0.5.8
- Codeadditions for the backend
- Set all files to utf-8
- Update headers

##### 0.5.7
- Codechanges/Bugfixes for WB 2.8.3 SP6 and PHP7
- Add missing files to the Project


[1]: http://websitebaker.org/ 	"WebsiteBaker"
[2]: http://www.wbce.org/	"WBCE"
[3]: https://github.com/AMASP-workbanch/wb-forum/commits/ "Commits"
  
# pannel

A compact and minimal writeboard to track projects featuring a very lightweight implementation of markdown and in-place AJAX editing.

[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)

Posts can be edited in place with a subset of markdown. They can be flagged as important or categorized as proposed, stuck, waiting for feedback, canceled or hibernating. Forms are unobtrusive and changes load instantly without page reload. The interface is clean and non distracting.

## Installing
1. Copy the files to the folder you would like to run _pannel_ from or clone with:
   ```
   git clone https://github.com/reven/pannel.git .
   ```

2. Edit **apache**'s `.htaccess` file to suit your instalation directory and server configuration; or if you use **nginx**, you will need to edit your config to make `index.php` parse all the requests with something along the lines of:
   ```
   location /pannel/ {
   try_files $uri /index.php?$args;
   }
   ```

3. Create a database for pannel to use. There is a database descriptor in SQL format in the [database.md](doc/database.md) file. Then modify your connection settings in `config.php`

4. Create a user and log in.

## To-do
- Add user administration capability
- ~~Implement revision comparison and management~~
- ~~Make markdown more standardized~~
- ~~Tighten security of password handling~~

## General comments
- This is a very good example of how messy code can get when you switch back and forth between languages. Spanish and english comments and code are thorroughly mixed. **This is a work in progress**.
- The security is lax. You should at the very least password protect the directory you install it under.
- There is no backup function.

## Tip jar
If you find this useful in any way, feel free to leave a tip in my bitcoin address:
`1CurZru2A7bwndrwNUCGiihmSGSe1qQz4N`

## License
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

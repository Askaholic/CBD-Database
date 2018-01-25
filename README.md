# CBD-Database

### Guidelines

- Make your own fork, and merge changes with pull requests. (You can merge your own pull requests)
- Work on `dev`
- Only push to `master` as part of a production deployment

### Installing with WordPress

In WordPress, plugins are installed in the `wp-content/plugins/` folder. Plugins
that require more than one file can be put into a folder as long as there is
a php file inside with the same name.

An easy way to set this up is to create a symlink to the src folder in the
plugins directory.
- `sudo ln -s /path/to/CBD-Database/src/ /var/www/html/wp-content/plugins/danceparty `

# CBD-Database

Team:
Alexander Eckert
Cameron Showalter

### Guidelines

- Make your own fork, and merge changes with pull requests. (You can merge your own pull requests)
- Work on `dev`
- Only push to `master` as part of a production deployment

### Pulling latest changes
A lot of times you will want to pull the latest changest from the main repo. The easiest way to do
this is to add the main repo as a remote.
1. Add the remote: `git remote add ask https://www.github.com/Askaholic/CBD-Database.git`
2. Now pull the latest changes from the dev branch `git pull ask dev`

### Installing with WordPress

In WordPress, plugins are installed in the `wp-content/plugins/` folder. Plugins
that require more than one file can be put into a folder as long as there is
a php file inside with the same name.

An easy way to set this up is to create a symlink to the src folder in the
plugins directory.
- `sudo ln -s /path/to/CBD-Database/src/ /var/www/html/wp-content/plugins/danceparty `

#### Getting WordPress Permalinks working

To get the plugin's routing to work you will need to set up the Apache rewrite
module. This is pretty simple, but just requires a few steps.

1. Enable the rewrite module: `sudo a2enmod rewrite`
2. Allow `.htaccess` in Apache configs
    1. Open `/etc/apache2/apache2.conf`
    2. Find the section that looks like this:
    ```
    <Directory /var/www/>
        Options Indexes FollowSymLinks
        AllowOverride None
        Require all granted
    </Directory>
    ```
    3. Change `AllowOverride` to All

3. Restart apache `sudo service apache2 restart`
4. Create a blank `.htaccess` file. `sudo touch /var/www/html/.htaccess`
5. Give WordPress write access to the `.htaccess` file: `sudo chown wpadmin:www-data .htaccess`
6. Disable and Re-enable the DanceParty plugin through wordpress. If everything
  is set up correctly this will flush the rewrite rules to the `.htaccess` file
  you created.

Hopefully that should work now (make sure that you've restarted Apache!)
You can test if it works by going to `localhost/test`. If you get the test page
it works!

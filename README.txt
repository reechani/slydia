SLYDIA

Document last changed: 2012-01-23
------------------------

Classes:
    CLydia.php
        CCtrl4Database.php
        CCtrl4Index.php
        CCtrl4Login.php
        CCtrl4Page.php
    db.php

Other files:
    /
        .htaccess
        README.txt (THIS)
        index.php
        robots.txt
        sitemap.xml
    site/
       config.php
    style/
        blueprint/ (http://blueprintcss.org/)
    theme/
        default.tpl.php
        empty.tpl.php

-------------------------

HOW TO

Download or pull from git to your webserver.
This is what you will need to to after you have the files in place:

- Copy/move config.sample.php to config.php (copy is the best in case you need
    to reset)
- Copy/move robots.sample.txt to robots.txt

- Open the config.php
    - At the top you have the database-login info. Fill this out.
    - Change the timezone-value
    - Fill in the baseurl of your site
    - At the bottom you have site-info. Fill out what you feel necessary for
        your site.

- Open file .htaccess
- Create one if it does not exist
    - Replace RewriteBase, or remove it if your site is not in a subfolder
    - Should look something like this:
            RewriteEngine on
            RewriteBase /~user/folder/slydia/

            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d

            RewriteRule (.*) index.php/$1 [NC,L]

            <files config.php>
            order allow,deny
            deny from all
            </files>


- Open file robots.txt
    - Replace the site-portion of the adress to the sitemap, leave /sitemap.xml

- Open sitemap.xml
    - Replace the adresses in <loc></loc> with your site adress, but leave the
        end parts. (/admin, /index)


Further, you need a MySQL-database, and be able to connect to it from your
website directory. In mysql, create a database for you site, and remember to add
this to the config-file. Use that database to create the following tables, but
remember to add your chosen prefix! (Example, TBL_PREFIX = "sly1_" then
tablename sly1_Pages)
    
    Pages
        id:int (PK, auto increment)
        content:text
        title:varchar(255 or any other suitable lenght)
    
    Users
        id:int (PK, auto)
        username:char(32) (Unique)
        pass:varchar(128)


For those of you that are new to MySQL, this is the syntax to use:

CREATE DATABASE Lydia;

CREATE TABLE Pages(
    id INT PRIMARY KEY AUTO_INCREMENT,
    context TEXT,
    title VARCHAR(255)
);

CREATE TABLE Users(
    id INT PRIMARY KEY AUTO_INCREMENT,
    username CHAR(32) UNIQUE,
    pass VARCHAR(128)
);

Then add a user to the Users-table. This will be your admin-login.
You can add as many admin-logins as you wish.

-------------------------------------
HOW TO: CREATE PAGES

To create pages, this is what you do:

1. Go to /site/src/
2. Create a new .php-file, and name it after the class you are going to create
    example: CCtrl4News.php
3. Class should initially look something like this:
     // class begins here
        class CCtrl4News implements IController {
            /**
             * Implementing interface IController. All controllers must have an
             * index action.
             */
            public function Index() {
                global $ly;
            }

        }
    // class ends here
    
    The index-function is the function that runs when you call the class.
    Anything you want to show on that page, you add into the index-function.
    The default template has several regions you can put content into, and these
    regions are saved into $ly->template->regions->REGIONNAME
    Example, for main content, put content as a string into
    $ly->template->regions->main
    
    These regions are available:
        - top
        - promoted
        - main
        - sidebar
        - triptych1
        - triptych2
        - triptych3
			
	(The header and footer are both fixed and set in config.php)

    The title is set through $ly->template->title.

4. To activate the class, go to config.php and enter the class and controller as
    into the $ly->cfg['controllers']-array. Just follow the way the already
    enabled controllers have been added. Once saved the site can now access your
    controller-class and show you the content of it.

5. To add a link to your controller into the main navigation of the site, go
    again to config.php. In $ly->cfg['site']['header'] you have the main-menu
    items. Follow the way the already existing items are added. For example, to
    add "News"-link add this to the array:
        array("name" => "News", "url" => "news", "class" => "", "admin" => false)
    Remember that the links are shown in the order they are displayed in the
    array. All pages added to the database trough Page will come after these
    links.


The alternative way to add pages, which does not give you as much flexibilty as
through controllers, is to login as admin (the user you hopefully created) and
go to /page ("Page"-link is shown in the menu when logged in). Here you can add
fixed pages and later edit them. HTML is allowed, PHP however is not supported.

1. Login
2. Go to "Page"
3a. Click "New page"
    Form is shown, fill it out.
    Save.
3b. Click a page-id
    Form is shown, with content of the page
    Edit as you please.
    Save.

The textarea of the form is resizeable, so for usabilty, resize it as you feel 
best to edit with comfort.

----------------------------------------------

HOW TO: ADD STYLESHEETS AND JAVASCRIPT

Stylesheets and any JS-files are all added globaly. This means that all files
you add to your config are loaded on all pages. You can add any number you wish.

They are loaded trough config.php from the variable $ly->cfg['site']. There you
have the stylesheet-array and the js-array. Add the full link to the files, and
if it's local file remember to add the baseurl of the site.

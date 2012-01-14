LYDIA

Document last changed: 2012-01-10
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

- Copy/move config.sample.php to config.php (copy is the best in case you need to reset)
- Copy/move robots.sample.txt to robots.txt

- Open the config.php
    - At the top you have the database-login info. Fill this out.
    - Change the timezone-value
    - Fill in the baseurl of your site
    - At the bottom you have site-info. Fill out what you feel necessary for your site.

- Open file .htaccess
    - Replace RewriteBase, or remove it if your site is not in a subfolder

- Open file robots.txt
    - Replace the site-portion of the adress to the sitemap, leave /sitemap.xml


Further, you need a MySQL-database, and be able to connect to it from your website directory.
In mysql, create a database for you site, and remember to add this to the config-file.
Use that database to create the following tables, but remember to add 
your chosen prefix! (Example, TBL_PREFIX = "sly1_" then tablename 
sly1_Pages)
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

To create pages, this is what you do:

1. Go to /site/src/
2. Create a new .php-file, and name it after the class you are going to create
    example: CCtrl4News.php
3. Class should initially look something like this:
     // class begins here
        class CCtrl4News implements IController {
            /**
             * Implementing interface IController. All controllers must have an index action.
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
    Example, for main content, put content as a string into $ly->template->regions->main
    
    These regions are available:
        LIST OF REGIONS IN DEFAULT
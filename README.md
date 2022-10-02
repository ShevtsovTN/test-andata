# Test:

## Test Job (PHP/CSS/JS/SQL)
### It is necessary to develop a one-page site with a commenting system.
1. Make up a template with a header, footer.
   * The header should contain the company logo and title (For example, "Horns and Hooves", any logo).
   * The content block should contain: a block with an article (fish-text, static text) and a block with comments.
   * The footer should contain a block with clickable icons of social networks.
2. Comments must be stored in the database.
   * Comments should be added without reloading the page.
   * Comment fields: Username, E-mail, Date added, Title, Comment text.
   * There should be validation of the entered data (frontend and backend).
   * Sorting would be a plus.
   __
3. Requirements for the task.
   * The code must be written in native PHP 7+ and OOP.
   * The code must be formatted according to PSR standards and commented out.
   * Any JS/CSS framework can be used. The template must be original.
   * During development, implement the MVC pattern.


## For running app:
* Run `composer install`
* Create and run mySQL server and add mySQL server parameters to `configs/app`
* Run `database/init.sql` for your db.
* Use `php -S 127.0.0.1:8000 server.php` for running PHP server.
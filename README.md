Yii 2 Election System projects
==============================



DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      public/             contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 5.4.0.
and have sqlBase DBMS like pgsql or mysql


INSTALLATION
------------


First of all you need to install the yii2basic via Composer
then copy the content of this project into your yii2basic project.

**** NOTE that we change the name of web directory to public directory ****
~~~


CONFIGURATION
-------------

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

**NOTES:**
- Yii won't create the database for you, this has to be done manually before you can access it.
- Check and edit the other files in the `config/` directory to customize your application as required.
- Refer to the README in the `root` directory for information specific to basic application tests.

Requirement Packages
--------------------

"yiisoft/yii2": "~2.0.5",
"yiisoft/yii2-bootstrap": "~2.0.0",
"yiisoft/yii2-swiftmailer": "~2.0.0",
"yiisoft/yii2-apidoc": "~2.1.0",
"kartik-v/yii2-widget-rating": "*",
"kavenegar/php": "^1.2"

you can add this to your composer.json file and use this command:
composer update


Create Tables
-------------

Int the models folder you can see five model that extend activerecord class.
it means you need to create five table for this project in your sqlBase DBMS.

Tables:
1-users
    => columns:
        id | firstname |  lastname | username(Unique) | password | authKey | Phone_Number(Unique) | Admin

2-elections
    => columns:
        id | name(Unique) | active

3-candidates
    => columns:
        id | first_name |  last_name | election_id | rate
    => Foreign-Key:
        local column :: election_id
        referenced column :: (elections table) id

4-votes
    => columns:
        id | user_id |  election_id | election_name | vote_at  Admin

5-verification
    => columns:
        id | verify_code |  user_id | candid_id | election_id

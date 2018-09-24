# Introdduction

This document describes how to set up the SimpleRisk development environment for the new Laravel version.

# Requirements

- [docker](https://www.docker.com/)
- [node](https://nodejs.org/en/) with npm installed
- [php](https://secure.php.net/) >=7.1
- internet access during the installation


# 1. Get SimpleRisk

Get the SimpleRisk source code and all required scripts from github. Switch to the Laravel development branch

```bash
git clone git@github.com:vulnis/code.git
cd code
git checkout laravel
```
# 2. Initialize SimpleRisk

We use docker to set up a development environment that has a database and everything required to use the 'old' version of SimpleRisk. We will also set up a second database for the laravel version. Although these database are as good as identical, the second database will be seeded with example data for _Operational and Dynamic Asset Risk Assessment_, whereas the original database is focused primarily on ICT Infrastructure.

```bash
docker-compose up
```
The containers are now created and running. The database container has a database named _simplerisk_ (containing the 'old' database structure), and a user named _simplerisk_ with the password _simplerisk_. 


# 3. Prepare the database

We will not be using the 'old' database, but create another database. We do this by entering into the container and executing the CREATE DATABASE command.

 :exclamation: Be careful, the containers database is not secured. The simplerisk user has __ALL__ privileges on the database. If you want to fine grain the access control on the database, consult the mariadb [documentation](https://mariadb.com/kb/en/library/documentation/).

```bash
docker exec -ti code_db_1 mysql -u simplerisk -p -D simplerisk
```
You will be prompted for the password, enter `simplerisk` and press enter. The prompt should now show that you are inside MariaDB
```
MariaDB [simplerisk]>
```
Now it it time to create the database
```sql
CREATE DATABASE simplerisk_dev;
```
The container should respond with __Query OK, 1 row affected (0.001 sec)__. We can now exit the container. Typing `exit` will close the MariaDB prompt and kicks us out of the container. The terminal can now be closed.

# 4. Setup the environment

The environment needs to be __configured__ for Laravel to be able to run. The process is described in detail on the [Laravel](https://laravel.com/docs/5.7#configuration) website.

First, copy the `./simplerisk.env.example file` to  `./simplerisk/.env`

```
cd simplerisk
cp .env.example .env
```

Edit `.env`. If you followed the documentation literally, everything is already correct.


Let composer install dependencies. This can take some time, so be patient.

```
composer update
```

When composer is finished; generate the application key. It will be automatically stored in the `.env` file.

```
php artisan key:generate
```

# 4. Populate the database

Migrations and Seeds are in place to generate the required database model plus some sample data. Run the following commands:

```
php artisan migrate
php artisan db:seed
```

# 5. Get and install frontend assets

Our html/jquery/bootstrap frontend is heavily integrated into laravel through [blade templates](https://laravel.com/docs/5.7/blade). Because you may require the Risk management software to run isolated (_not connected to the internet_) our frontend is independent of any [CDN's](https://en.wikipedia.org/wiki/Content_delivery_network) or other online resources. All resources are served from the local server. All frontend assets are compiled to a `.js` and a `.css` file. This build is done using node. The node resources that are installed are __ONLY__ used for build and are no longer required when the application runs (dev-dependencies).


Install the build dependencies by running:

```
npm install
```

If all goes well, compile the assets running

```
npm run dev
```

If you are developing frontend components; you can also use `npm run watch` to have the build process 'listen' to the changes you make, forcing the `.js` and `.css` files to be rebuilt.

# 6. Run SimpleRisk

```
php artisan serve
```









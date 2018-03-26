# Device Assignement Software

## Installation
I'm assuming you have php and mysql and npm/yarn installed on your system.

1. Clone `device assigner` to your local machine `git clone https://github.com/toddmcbrearty/networkdoctor.git`
2. Now cd into the directory networkdoctor `cd networkdoctor`
3. First we'll install all the requirements

   a. run `composer install` this will install Laravel and it's dependencies

   b. run `yarn/npm install` this will install all the front-end components

   c.run `artisan migrate --seed` if you make a mistake you can run `artisan migrate:refresh --seed` to start over.
   This will load all the default as well as some dummy data.

4. run `yarn/npm run dev` this will build the components

5. run `artisan serve` this starts the test server

Now you'll be able to access the site at http://localhost:8000/home

## Login Information
After migration and seeding is done a default admin user will have been created.

Admin Email: test@test.com

Admin Password: welcome
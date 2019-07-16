# Wordpress Website for Tech Incubator
## How to run the application
1. Make sure you are in the root directory of the application
2. Run docker-compose up
3. Make sure everything is up and running (the output will show no errors and still run in the terminal)
4. Go to the [phpmyadmin](http://localhost:8181)
    - If the wordpress database is not included, then create one
    - Import the wordpress-2.sql file 
5. Make sure tables are populated in the wordpress database now
6. Check the [wordpress site](http://localhost:3000)


Go to the wp-admin and sign in
Go to the all in one plugin and upload the file I shared with you on google drive (this will add stuff to db)
to run docker better, do the following: 
    docker create network dbNet
    docker-compose up
    docker run --name=dataramdevelopment\testinc1 -v /Users/daviddataram/Docker\ Applications/test/wordpress/:/var/www/html/ --network dbNet -p 3000:80  wordpress:php7.1-apache 

davedataram@gmail.com
Dave32594!
# PullFlightData
Pulls flight data for arriving, departing, and enroute traffic for specified airline and specified ICAO

# Installation:

You need to create a flightxml API account (check the thread on how to do so) so you can link it to the php.
It uses cron job to run it for a week to collect all the flights,
and stores it all in a database table.

So you need an OS capable of creating cron jobs, and access to some database.

- First of all, you need to open up the puller.php in Program/
Then go to line #12 and change the variable value to the ICAO which you want to pull data from, do the same with the airline (use the three letter code for your airline, ie. Scandinavian Airlines = SAS)

- Second of all, you need to create a database, do so by running:
```SQL
CREATE DATABASE FligthAwarePuller;
```

- Now we need to create a table in that database so we can send the data to it:
```SQL
CREATE TABLE `flights` (
  `code` varchar(3) DEFAULT NULL,
  `flightnum` varchar(6) DEFAULT NULL,
  `depicao` varchar(4) DEFAULT NULL,
  `arricao` varchar(4) DEFAULT NULL,
  `route` varchar(255) DEFAULT NULL,
  `tailnum` varchar(6) DEFAULT NULL,
  `flightlevel` varchar(7) DEFAULT NULL,
  `distance` varchar(7) DEFAULT NULL,
  `deptime` varchar(7) DEFAULT NULL,
  `arrtime` varchar(7) DEFAULT NULL,
  `flighttime` varchar(7) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `flighttype` varchar(8) DEFAULT NULL,
  `daysofweek` int(11) DEFAULT NULL,
  `enabled` bit(1) DEFAULT NULL
);
```
That's it!
- If you don't host your database locally from where you are running the script, change the mysql credentials on line #59.

- Now, on to creating a cron job.
I used a raspberry pi to do this, and it just requires a computer with php installed (it doesn't have to be a server, just as long as it can run 24/7),
and mysql installed.
After you have cloned the git repo, cd into the Program/ directory and type (using a Linux terminal in this case)
```sh
crontab -e
```
Now you can type in this to run the script every 15 minutes (once the clock strikes either 00, 15, 30, 45):
```sh
*/15 * * * * /usr/bin/php -f /var/www/root/Program/puller.php >> /var/log/apache2/crontab.log
```
Change the directories to match your "homemade server" :).
It will create a log file in said directory in case there is any issues ;).

That's it! Sit back, relax, and watch!

# Purpose:

When creating a virtual airline, you want to have the most recent schedules.
This can be done by purchasing services. However, as a project, I wanted to do it for free using flightaware's API.
I have come quite far, it outputs the data into a csv file, however, I want to be able to get the filed flight level.
The only hinder here being that a lot of planes don't file altitude (see the json file, only one of all those aircrafts filed an altitude).
After that, it's done! (An artists work is never done, it's just 'abandoned' :p)

# Notice:

I included a json output of running the API with AirportBoards? and airline:SAS so you can try the code yourself.
To use this just run the puller.php in TestFiles/ :)
Oh and btw, you're free to use the api keys and id you found here, they're from a free account which anyone can create.
However, I do encourage you to create your own account and not rely on hoping that no-ones wastes all the queries in one go :).

# TODO: 

- Come with suggestions!

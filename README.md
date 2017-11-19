# PullFlightData
Pulls flight data for arriving and departing SAS aircraft from input ICAO, and then pulls other info from flightaware api.

# Purpose:

When creating a virtual airline, you want to have the most recent schedules.
This can be done by purchasing services. However, as a project, I wanted to do it for free using flightaware's API.
I have come quite far, it outputs the data into a csv file, however, I want to be able to get the filed flight level.
The only hinder here being that a lot of planes don't file altitude (see the json file, only one of all those aircrafts filed an altitude).
After that, it's done! (An artists work is never done, it's just 'abandoned' :p)

The code isn't the prettiest, I wanted to make sure that it works enought that I can upload it to my github.
Documentation as well as better "formating" is coming soon :p.

# Notice:

I included a json output of running the API with AirportBoards? and airline:SAS so you can try the code yourself.
Just be sure to modify your php file to point to this json instead.
Oh and btw, you're free to use the api keys and id you found here, they're from a free account which anyone can create.
However, I do encourage you to create your own account and not rely on hoping that no-ones wastes all the queries in one go :).

# TODO: 

- Include flight level (currently it is set to 0 because so few planes seem to file altitude).
- Add instructions on how to test using AiportBoards.json.
- Add. Inline. Comments.
- Add flight time.
- Add route (believe it or not, some actually file this).
- Add so that you can run it for a week for it to collect info without overwriting data already stored in the csv file.

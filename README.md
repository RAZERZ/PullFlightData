# PullFlightData
Pulls flight data for arriving and departing SAS aircraft from input ICAO, and then pulls other info from flightaware api.

# Purpose:

When creating a virtual airline, you want to have the most recent schedules.
This can be done by purchasing services. However, as a project, I wanted to do it for free using flightaware's API.
I have come quite far, I now only need to output the data into a csv file since most virtual airlines use phpvms which uses this filetype for input.

The code isn't the prettiest, I wanted to make sure that it works enought that I can upload it to my github.
Documentation as well as better "formating" is coming soon :p.

# Notice:

I included a json output of running the API with AirportBoards? and airline:SAS so you can try the code yourself.
Just be sure to modify your php file to point to this json instead.

TODO: Put the output in a csv file, add so you can enter airline of choice instead of only SAS.

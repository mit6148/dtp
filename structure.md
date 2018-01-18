## List of tables in database

* Users (Sub, Kerberos, Email, Name, Events signed up for)
	* Each entry should only be accessible by the user to whom entry belongs
* Events (Course, Assignment, Location, Start Unix time, Stop Unix time)
	* Can be accessed by any user (even when not authenticated) given that event is public (which all events will be in the MVP)
* Sessions (Unique ID, Sub, Expire time)

## To access events a user has signed up for:
* PHP script gets event user signed up for from Users table
* PHP script gets details for each event user signed up for from Events table
* PHP script returns array of JSON objects containing details of events

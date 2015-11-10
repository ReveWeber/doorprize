# doorprize
A simple door prize entry and winner-drawing program.

You'll need to rebrand, of course, but also to create a database "doorprize" with table "entries". 
Columns in "entries" are EntryID (int, auto-increment, primary key), Name (varchar(80), not null), 
Email (varchar(60), not null), CellPhone (varchar(20), null allowed), and Organization (varchar(60), null allowed).

Put the database credentials into includes/db.php (username and password; database if you decided to use an existing one). 

index.php is the entry page and admin.php has options to draw 3 winners and to export all entrants to a CSV file.

# chess-scripts
A collection of chess-scripts &amp; tests.
Note: Most of them need a PHP server to fetch the data from chess-results.com (can't do this with javascript cause of CORS restrictions)


#u-swiss52.php
You give a chess-results.com link with the starting list and it shows you links with data for each player. 
It can also:
-Fetch the age group of each player
-Show an initial pairing (estimation -since there are always last minutes changes in all tournaments)



#scan_countries.php?country=FID
Scans a country for tournaments. It marks weekends
eg 
scan_countries.php?country=FID
shows FIDE tournaments, their location and their date.



#tournament-random-pairing.php
Copy/paste a link of names and it will create the pairs for the first round.
Then you will choose(tick checkbox) the winners and then it will show pairing for only the ticked winners.
If $swiss_pairing it will make the pairing in (fake) swiss like manner (cut list in half and then make pairs with the others)
$random_pair_for_all_rounds=true then it makes random pairs



# oxoproject

###Requirements
- Docker ( https://www.docker.com/get-started )
- git ( https://git-scm.com/ )

###Setup
- Clone the repository using `git clone https://github.com/paulmold/oxoproject.git`
- In the project directory run `docker compose up`
- To run the import script enter into terminal:
  - `docker exec -it php-apache /bin/bash`
  - `php import.php -f="data.html"`
- To view the poroject open http://localhost:8000/

###Notes
Docker will handle the apache server and the mysql server.
The database will construct itself using the structure found in *db/structure.sql*

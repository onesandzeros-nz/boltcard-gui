## Overview


CREATE USER cardapp WITH PASSWORD 'database_password';
GRANT ALL ON SCHEMA public TO cardapp;
ALTER DATABASE card_db OWNER TO cardapp;

sudo apt-get install php8.0-pgsql


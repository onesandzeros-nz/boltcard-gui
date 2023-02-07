## Overview

```
CREATE USER cardapp WITH PASSWORD 'database_password';
GRANT ALL ON SCHEMA public TO cardapp;
ALTER DATABASE card_db OWNER TO cardapp;

sudo apt-get install php8.0-pgsql

```


## Docker Commands

### console
```
docker exec -it boltcard_main /bin/sh
```

### Create Bolt Card
```
docker exec boltcard_main createboltcard/createboltcard -enable -tx_max=100000 -day_max=100000 -allow_neg_bal -name=card_blank
```

### Wipe Bolt Card
``` 
docker exec boltcard_main wipeboltcard/wipeboltcard -name card_blank
```

### Access PSQL DB
```
docker exec boltcard_db psql -U cardapp card_db

```
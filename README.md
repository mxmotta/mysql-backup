# MySQL Backup

## Description

MySQL-backup is a simple tool for making backups of MySQL databases using Docker. This project makes it easy to create automatic backups and configure them using Docker and PHP files.

## Features

- Automatic backup of MySQL databases
- Configuration via Docker Compose
- Crunz support for scheduling tasks

## Installation

1. Clone the repository:

```bash
git clone https://github.com/mxmotta/mysql-backup.git
cd mysql-backup
```

2. Copy the .env-example file to .env and set your environment variables:

```bash
cp .env-example .env
```

3. Configure the database details and backup directory in the .env file.

## Usage

### Example to run manually:

```bash
php index.php
```

### Example of cron configuration:

1. Open crontab:

```bash
crontab -e
```

2. Add the line to schedule the daily backup at 2:00 AM:

```bash
0 2 * * * /usr/bin/php /path/to/o/mysql-backup/index.php
```

The backups will be stored in the directory specified in the .env file.

## Running with docker:

1. Pull the image:

```bash
docker pull mxmotta/mysql-backup
```

2. Run the container passing the environment variables:

```bash
 docker run --rm --name mysql-backup \
    -v ./backup:/application/backup \
    -e DB_HOST=mysql
    -e DB_PORT=3306 \
    -e DB_NAME=app,new_schema \
    -e DB_USER=root \
    -e DB_PASSWORD=password \
    -e BACKUP_DIR=backup \
    -e BACKUP_MAXFILES=5 \
    -e TIMEZONE=America/Bahia \
    -e BACKUP_DESTINY=local # (local or aws) \
    -e BACKUP_TIME=00:00 \
    -e AWS_VERSION=latest \
    -e AWS_REGION=us-central \
    -e AWS_BUCKET= \
    -e AWS_KEY= \
    -e AWS_SECRET= \
    -e AWS_ACL=bucket-owner-full-control \
    -e AWS_ENDPOINT= \
    mxmotta/mysql-backup:latest
```

## Contribution
Contributions are welcome! Feel free to open issues and pull requests.

## License
This project is licensed under the MIT license - see the LICENSE file for more details.
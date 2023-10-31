# Assessment Task Setup

This README provides two methods for setting up the assessment task: Docker and Manual.

## Environment Setup

1. Ensure you have an `.env` file present. If it's not already present, copy `.env.example` to create an `.env` file.

2. For Docker Setup: Replace the database credentials in your `.env` file with the following values:

```env
DB_HOST=assessment-db
DB_PORT=3306
DB_USER=root
DB_PASS=assessment@root
DB_NAME=assessment
```

3. For Manual Setup: Replace the database credentials in your `.env` file with the following values:

```env
DB_HOST=localhost
DB_PORT=3306
DB_USER=username
DB_PASS=password
DB_NAME=assessment
```

## Application Settings & Database Setup
You can set the `EMAIL_FROM_NAME`, `EMAIL_FROM_ADDRESS` and `SEND_ENTRY_EMAIL_TO` in the `.env` file.

- SEND_ENTRY_EMAIL_TO: The email address where the email of entry submission will be sent to.

Import `assesment.sql` in your database to create the tables.

## Method #1: Docker Setup

To set up the task using Docker, follow these steps:

1. Ensure you have the latest version of Docker installed and running on your system.

2. Make sure that ports `8100` and `8080` are available for the task.


To start Docker, run the following command to build images and start the containers:

```sh
docker-compose up -d
```


After running this command, you will have access to the following URLs:

- PHP Application: http://localhost:8100
- PhpMyAdmin: http://localhost:8080

For PhpMyAdmin access, use the following credentials:
- User: `root`
- Password: `assesment@root`

## Method #2: Manual Setup
For manual setup, ensure that you meet the following prerequisites:

- PHP Version: 8.1 with the GD Extension installed
- Node Version: 18.16.0
- Apache2 with the Rewrite Module enabled
- MySQL or MariaDB

Install php dependencies using the following command:
```sh
composer install
```

Install node dependencies using the following command:
```sh
npm install
```

Complite the JavaScript and Styles using following command:
```sh
npm run build
``` 

## Continuing Manual Setup: Apache2 Setup

1. Change the `APP_URL` value in the `.env` file according to `http://assesment-task.local`

2. Edit the Hosts File:

Open the hosts file with administrative privileges. On Linux and macOS, you can use a text editor like `nano` or `vim` with sudo:
```sh
sudo nano /etc/hosts
```
Add an entry to map the local domain "assesment-task.local" to your localhost (127.0.0.1):
```plaintext
127.0.0.1  assesment-task.local
```

3. Create a Virtual Host Configuration:

Create a new Apache virtual host configuration file. On most Linux systems, you can create a new file in the `/etc/apache2/sites-available/` directory with a `.conf` extension. You can name the file something like `assesment-task.local.conf`:
```sh
sudo nano /etc/apache2/sites-available/assesment-task.local.conf
```

Inside the configuration file, you can use the following template as a starting point:

```apache
<VirtualHost *:80>
    ServerAdmin webmaster@assesment-task.local
    ServerName assesment-task.local
    DocumentRoot /var/www/html/assessment

    ErrorLog ${APACHE_LOG_DIR}/assesment-task-error.log
    CustomLog ${APACHE_LOG_DIR}/assesment-task-access.log combined

    <Directory /var/www/html/assessment>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Save and exit the text editor.

4. Enable the Virtual Host:

Use the `a2ensite` command to enable the virtual host configuration:

```sh
sudo a2ensite assesment-task.local.conf
```

5. Reload Apache:

To apply the changes, reload Apache:

```sh
sudo systemctl reload apache2
```

Finally, you should now be able to access website on:

http://assesment-task.local

## SMTP Setup
You should be able to see `SMTP` settings in the `.env` file.

You can use any SMTP you want, for testing you can use the following SMTP:
```env
SMTP_HOST=smtp.gmail.com
SMTP_USER=hassanejazpvt@gmail.com
SMTP_PASS=uzxttcyywjnifgji
SMTP_ENCRYPTION=ssl
SMTP_PORT=465
```

## API Docs
Endpoint: `POST /api/forms`
Payload:
```json
{
	"name": "Survey 1",
	"title": "This is a dummy survey form",
	"fields": [
		{
			"field_name": "Name",
			"field_type": "input",
			"validations": [
				"required"
			]
		},
        {
			"field_name": "Email",
			"field_type": "input",
			"validations": [
				"required",
                "email",
                "minlength:5"
			]
		},
        {
			"field_name": "Phone Number",
			"field_type": "input",
			"validations": [
				"required",
                "number",
                "minlength:5",
                "maxlength:20"
			]
		},
        {
			"field_name": "Gender",
			"field_type": "select",
			"validations": [
				"required"
			],
            "options": [
                "Male",
                "Female"
            ]
		},
        {
			"field_name": "Married",
			"field_type": "checkbox"
		},
        {
			"field_name": "Relocate",
			"field_type": "radio",
            "options": [
                "Yes",
                "No"
            ]
		},
        {
			"field_name": "Bio",
			"field_type": "textarea",
			"validations": [
				"required"
			]
		}
	]
}
```
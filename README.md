#CloudFlare Dynamic DNS Updater


This is a basic PHP script, intended to run as a cron job, to update an DNS zone entry to a dynamic IP. It supports optional Pushover notifications.


##Usage

	Simply update the constants in the cron.php file with your CloudFlare credentials, domain information, and optional Pushover device key
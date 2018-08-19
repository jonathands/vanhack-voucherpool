#The App and API

This app has a single page meant to test the 3 API endpoints

* GET /vouchers - list all vouchers on the db
* POST /voucher/save - saves offer and customer , generate 8 digit voucher codes and returns a list of voucher
* POST /voucher/use - takes an email and a code , checks if it has a usage date , else it sets the date and return the discount

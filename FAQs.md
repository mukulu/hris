# HRIS Frequently Asked Questions
=================================
#### Installation Issues
1. What should I Do when facing Composer dependency issues?
	ANS:
	If composer is having issues, try to remove the vendo directory
2. What should i do to stay uptodate
	ANS: 
	Everytime you pull source codes, update composer dependencies with `composer.phar update`
	and clear cache with `app/console cache:clear --env=dev --no-warmup` to clear development cache

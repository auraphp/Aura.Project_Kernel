cd integration
composer self-update
if [ -d vendor ]
then
    composer update
else
    composer install
fi
cd ..
phpunit
status=$?
exit $status

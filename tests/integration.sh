composer self-update
if [ -d cli-project ]
then
    cd cli-project
    composer update
    cd ..
else
    composer create-project aura/cli-project --prefer-dist --stability=dev
fi

rm -rf cli-project/vendor/aura/project-kernel/*
cp -r autoload.php  cli-project/vendor/aura/project-kernel/
cp -r composer.json cli-project/vendor/aura/project-kernel/
cp -r config        cli-project/vendor/aura/project-kernel/
cp -r README.md     cli-project/vendor/aura/project-kernel/
cp -r src           cli-project/vendor/aura/project-kernel/
cp -r tests         cli-project/vendor/aura/project-kernel/
cd cli-project/vendor/aura/project-kernel/tests
phpunit
status=$?
cd ../../../..
exit $status

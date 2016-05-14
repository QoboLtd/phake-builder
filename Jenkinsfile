#!groovy

stage 'Checkout'
node {
	checkout scm
	sh "composer install"
	sh "./vendor/bin/phake dotenv:create"
}

stage 'Test'
node {
	sh "./vendor/bin/phpunit"
    sh "./vendor/bin/phpcs -n -p --extensions=php --standard=PSR2 src/ tests/"
}

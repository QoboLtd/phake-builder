#!groovy

node {
	// Checkout project and setup the project
	stage 'Checkout'
		checkout scm
		sh "composer install"
		sh "./vendor/bin/phake dotenv:create"

	// Run the tests
	stage 'Test'
		sh "./vendor/bin/phpunit"
		step([$class: 'JUnitResultArchiver', testResults: 'build/log/phpunit.xml'])
		sh "./vendor/bin/phpcs -n -p --extensions=php --standard=PSR2 src/ tests/"
}

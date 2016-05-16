#!groovy

node {
	// Checkout project and setup the project
	stage 'Checkout'
		checkout scm
		sh "composer install"
		sh "./vendor/bin/phake dotenv:create"

	// Run the tests
	stage 'Test'
		sh "./vendor/bin/phake build:all"
		step([$class: 'JUnitResultArchiver', testResults: 'build/log/phpunit.xml'])
}

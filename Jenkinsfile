#!groovy

node {
	// Checkout project and setup the project
	stage 'Checkout'
		checkout scm
		sh "composer install"
		sh "./vendor/bin/phake dotenv:create"

	// Run the tests
	stage 'Test'
		catchError {
			sh "./vendor/bin/phake build:all"
		}
		// Publish Code Coverage HTML report
		publishHTML(target: [allowMissing: true, alwaysLinkToLastBuild: false, keepAll: true, reportDir: 'build/coverage/', reportFiles: 'index.html,dashboard.html', reportName: 'Coverage Report'])
		// Publish Source Code HTML documentation
		publishHTML(target: [allowMissing: true, alwaysLinkToLastBuild: false, keepAll: true, reportDir: 'build/doc/source/', reportFiles: 'index.html', reportName: 'Source Code API'])

		// Archive PHPUnit report
		step([$class: 'JUnitResultArchiver', testResults: 'build/logs/junit.xml'])
		// Publish combined results for phpcs, phpmd, phpcpd, etc
		step([$class: 'AnalysisPublisher', canComputeNew: false, canRunOnFailed: true, defaultEncoding: '', healthy: '', unHealthy: ''])

}

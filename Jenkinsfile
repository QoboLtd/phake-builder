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
		// Archive PHPUnit report
		step([$class: 'JUnitResultArchiver', testResults: 'build/logs/junit.xml'])
		// Publish xUnit rport
		step([$class: 'XUnitBuilder', testTimeMargin: '3000', thresholdMode: 1, thresholds: [[$class: 'FailedThreshold', failureNewThreshold: '', failureThreshold: '', unstableNewThreshold: '', unstableThreshold: ''], [$class: 'SkippedThreshold', failureNewThreshold: '', failureThreshold: '', unstableNewThreshold: '', unstableThreshold: '']]])
		// Publish CheckStyle report
		step([$class: 'CheckStylePublisher', canComputeNew: false, defaultEncoding: '', healthy: '', pattern: 'build/logs/checkstyle.xml', unHealthy: ''])
		// Publish PMD report
		step([$class: 'PmdPublisher', canComputeNew: false, defaultEncoding: '', healthy: '', pattern: 'build/logs/pmd.xml', unHealthy: ''])
}

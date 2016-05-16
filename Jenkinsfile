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
		// Publish CheckStyle report
		step([$class: 'CheckStylePublisher', canComputeNew: false, canRunOnFailed: true, defaultEncoding: '', healthy: '', pattern: 'build/logs/checkstyle.xml', unHealthy: ''])
		// Publish PMD report
		step([$class: 'PmdPublisher', canComputeNew: false, canRunOnFailed: true, defaultEncoding: '', healthy: '', pattern: 'build/logs/phpmd.xml', unHealthy: ''])
		// Publish CPD report
		step([$class: 'DryPublisher', canComputeNew: false, canRunOnFailed: true, defaultEncoding: '', healthy: '', pattern: 'build/logs/phpcpd.xml', unHealthy: ''])
		// Publish xUnit rport
		step([$class: 'XUnitBuilder', testTimeMargin: '3000', thresholdMode: 1, thresholds: [[$class: 'FailedThreshold', failureNewThreshold: '', failureThreshold: '', unstableNewThreshold: '', unstableThreshold: ''], [$class: 'SkippedThreshold', failureNewThreshold: '', failureThreshold: '', unstableNewThreshold: '', unstableThreshold: '']], tools: [[$class: 'PHPUnitJunitHudsonTestType', deleteOutputFiles: true, failIfNotNew: false, pattern: 'build/logs/junit.xml', skipNoTestFiles: true, stopProcessingIfError: true]]])

}

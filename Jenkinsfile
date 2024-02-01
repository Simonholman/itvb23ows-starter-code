pipeline {
    agent any

    stages {
        stage('Clone source') {
            steps {
                checkout scm
            }
        }

        stage('sonarqube') {
            steps {
                script { scannerHome = tool 'sonarqube' }
                withSonarQubeEnv('sonarqube') {
                    sh "${scannerHome}/bin/sonar-scanner -Dsonar.projectKey=sonarqube"
                }
            }
        }

        stage('PHPUnit') {
            steps {
                script {
                    docker.image('composer:lts').inside {
                        sh 'composer install'
                        sh 'vendor/bin/phpunit src/.'
                    }
                }
            }
        }
    }
}
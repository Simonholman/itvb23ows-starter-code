pipeline {
    agent any

    stages {
        stage('Clone source') {
            steps {
                checkout scm
            }
        }

        stage('Build') {
            steps {
                sh "docker-compose -f docker-compose-jenkins.yml build"
                sh "docker-compose -f docker-compose-jenkins.yml up -d"
            }
        }

        stage('Test') {
            steps {
                script {
                    docker.image('inner-php-image:latest').inside {
                        sh './UnitTest.php'
                    }
                }
            }
        }
    }
}
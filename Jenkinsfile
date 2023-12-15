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
                sh "docker-compose -f docker-compose-jenkins.yml run php-app-jenkins sh -c 'curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer'"
            }
        }

        stage('Test') {
            steps {
                script {
                    docker.image('inner-php-image:latest').inside {
                        sh 'composer require --dev phpunit/phpunit'
                        sh 'composer install'
                        sh './UnitTest.php'
                    }
                }
            }
        }
    }
}
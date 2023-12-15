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
                script {
                    docker.image('inner-php-image:latest').inside {
                        sh 'curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer'
                        sh 'composer require --dev phpunit/phpunit'
                        sh 'composer install'
                    }
                }
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
pipeline {
    agent any

    stages {
        stage('Clone source') {
            steps {
                checkout scm
            }
        }

        stage('Install PHP') {
            steps {
                sh 'sudo apt update'
                sh 'sudo apt install -y php'
            }
        }

        stage('Install Composer') {
            steps {
                sh 'curl -sS https://getcomposer.org/installer | php'
                sh 'mv composer.phar /usr/local/bin/composer'
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
                sh 'composer install'
                sh 'vendor/bin/phpunit --display-warnings src'
            }
        }
    }
}
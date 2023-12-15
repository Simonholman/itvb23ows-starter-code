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
                sh "docker-compose build"
                sh "docker-compose up -d"
            }
        }
    }
}

    // stages {
    //     stage('Checkout') {
    //         steps {
    //             checkout scm
    //         }
    //     }

    //     stage('Build and Test PHP App') {
    //         steps {
    //             script {
    //                 docker.build('itvb23ows-starter-code-php-app:latest', '-f Dockerfile .')
    //                 docker.image('itvb23ows-starter-code-php-app:latest').run('--rm -v $PWD:/app phpunit')
    //             }
    //         }
    //     }

    //     // Voeg meer stappen toe voor deployment, afhankelijk van je behoeften
    // }
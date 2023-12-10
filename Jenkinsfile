pipeline {
    agent {
        docker { image 'php:5.6-fpm' } 
    }

    stages {
        // stage('Checkout') {
        //     steps {
        //         // Haal de broncode op uit de versiebeheer (bijv. Git)
        //         checkout scm
        //     }
        // }

        stage('Build') {
            steps {
                // Bouw de Docker-image voor de PHP-applicatie
                echo 'test'
                sh 'php --version'
                // script {
                //     docker.build('jouw-gebruikersnaam/php-app:latest')
                // }
            }
        }

        // stage('Test') {
        //     steps {
        //         // Voer PHP-tests uit in de Docker-container
        //         script {
        //             docker.image('jouw-gebruikersnaam/php-app:latest').run('--rm -v $PWD:/app phpunit')
        //         }
        //     }
        // }

        // stage('Deploy') {
        //     steps {
        //         // Deploy de applicatie (bijvoorbeeld naar een server)
        //         script {
        //             // Voeg hier je deploy-stappen toe
        //         }
        //     }
        // }
    }
}

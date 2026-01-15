pipeline {
    agent any

    triggers {
        githubPush()
    }

    stages {

        stage('Checkout Code') {
            steps {
                git 'https://github.com/anurajyellurkar/flight_booking_final16-.git'
            }
        }

        stage('Trivy Security Scan') {
            steps {
                sh 'trivy fs --exit-code 0 --severity HIGH,CRITICAL .'
            }
        }

        stage('Build Docker Containers') {
            steps {
                sh 'docker-compose build'
            }
        }

        stage('Deploy Application') {
            steps {
                sh 'docker-compose up -d'
            }
        }
    }
}

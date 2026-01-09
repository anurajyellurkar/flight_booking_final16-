pipeline {
    agent any

    environment {
        IMAGE_NAME = "flight-booking:latest"
        TRIVY = "/opt/homebrew/bin/trivy"
    }

    stages {

        stage('Checkout Code') {
            steps {
                echo "📥 Checking out source code"
                git branch: 'main',
                    url: 'https://github.com/anurajyellurkar/flight_booking_final16-.git'
            }
        }

        stage('Security Scan - Trivy (Offline Safe)') {
            steps {
                echo "🔐 Running Trivy filesystem scan (offline-safe)"
                sh '''
                export TRIVY_DISABLE_DOCKER_CREDENTIALS=true
                export TRIVY_OFFLINE_SCAN=true
                export TRIVY_NO_PROGRESS=true

                ${TRIVY} fs \
                  --skip-db-update \
                  --skip-java-db-update \
                  --ignore-unfixed \
                  --severity HIGH,CRITICAL \
                  --exit-code 0 \
                  .
                '''
            }
        }

        stage('Build Docker Image') {
            steps {
                echo "🐳 Building Docker image"
                sh 'docker build -t ${IMAGE_NAME} .'
            }
        }

        stage('Deploy Application (Docker Compose)') {
            steps {
                echo "🚀 Deploying application"
                sh '''
                docker compose down
                docker compose up -d --build
                '''
            }
        }
    }

    post {
        success {
            echo "✅ CI/CD Pipeline completed successfully"
        }
        failure {
            echo "❌ CI/CD Pipeline failed"
        }
        always {
            echo "📌 Pipeline execution finished"
        }
    }
}

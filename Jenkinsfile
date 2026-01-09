pipeline {
    agent any

    environment {
        IMAGE_NAME = "flight-booking:latest"
        TRIVY_PATH = "/opt/homebrew/bin/trivy"
        TRIVY_DISABLE_DOCKER_CREDENTIALS = "true"
        TRIVY_SKIP_DB_UPDATE = "true"
    }

    stages {

        stage('Checkout Code') {
            steps {
                echo "📥 Checking out source code from GitHub"
                git branch: 'main',
                    url: 'https://github.com/anurajyellurkar/flight_booking_final16-.git'
            }
        }

        stage('Security Scan - Trivy') {
            steps {
                echo "🔐 Running Trivy security scan"
                sh '''
                export TRIVY_DISABLE_DOCKER_CREDENTIALS=true
                export TRIVY_SKIP_DB_UPDATE=true
                ${TRIVY_PATH} fs --exit-code 0 --severity HIGH,CRITICAL .
                '''
            }
        }

        stage('Build Docker Image') {
            steps {
                echo "🐳 Building Docker image"
                sh '''
                docker build -t ${IMAGE_NAME} .
                '''
            }
        }

        stage('Deploy Application (Docker Compose)') {
            steps {
                echo "🚀 Deploying application using Docker Compose"
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

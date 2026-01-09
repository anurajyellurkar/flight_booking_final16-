pipeline {
    agent any

    environment {
        IMAGE_NAME = "flight-booking:latest"
        TRIVY_IMAGE = "aquasec/trivy:latest"
    }

    stages {

        stage('Checkout Code') {
            steps {
                echo "📥 Checking out source code"
                git branch: 'main',
                    url: 'https://github.com/anurajyellurkar/flight_booking_final16-.git'
            }
        }

        stage('Security Scan - Trivy (Dockerized)') {
            steps {
                echo "🔐 Running Trivy filesystem scan via Docker"
                sh '''
                docker run --rm \
                  -v "$PWD:/project" \
                  -v "$HOME/.cache/trivy:/root/.cache/" \
                  ${TRIVY_IMAGE} \
                  fs \
                  --no-progress \
                  --ignore-unfixed \
                  --severity HIGH,CRITICAL \
                  --exit-code 0 \
                  /project
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

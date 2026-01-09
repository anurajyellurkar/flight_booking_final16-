pipeline {
    agent any

    environment {
        IMAGE_NAME = "flight-booking:latest"
        TRIVY = "/opt/homebrew/bin/trivy"
        TRIVY_CACHE_DIR = "${WORKSPACE}/.trivycache"
    }

    stages {

        stage('Checkout Code') {
            steps {
                echo "📥 Checking out source code"
                git branch: 'main',
                    url: 'https://github.com/anurajyellurkar/flight_booking_final16-.git'
            }
        }

        stage('Trivy DB Bootstrap (First Run Only)') {
            steps {
                echo "⬇️ Downloading Trivy vulnerability DB (one-time)"
                sh '''
                mkdir -p ${TRIVY_CACHE_DIR}
                ${TRIVY} --cache-dir ${TRIVY_CACHE_DIR} image --download-db-only || true
                '''
            }
        }

        stage('Security Scan - Trivy (Stable Offline Mode)') {
            steps {
                echo "🔐 Running Trivy filesystem scan"
                sh '''
                export TRIVY_DISABLE_DOCKER_CREDENTIALS=true
                export TRIVY_NO_PROGRESS=true

                ${TRIVY} fs \
                  --cache-dir ${TRIVY_CACHE_DIR} \
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

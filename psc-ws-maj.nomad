job "psc-ws-maj" {
    datacenters = ["dc1"]
    type = "service"
    update {
        stagger = "30s"
        max_parallel = 1
    }
    group "apps-servers" {
        count = "1"
        ephemeral_disk {
            size = 200
        }
        network {
            port "http" {
                to = 80
            }
        }
        task "webapp" {
            driver = "docker"
            config {
                image = "prosanteconnect/psc-ws-maj:v0.0.7"
                ports = ["http"]
            }
            template {
                data = <<EOH
                    APP_NAME=Psc-ws-maj
                    APP_ENV=local
                    APP_KEY=base64:BbF5kVORtLViIeVqkMXSjot8vBqkJaObr+rj0T+xECE=
                    APP_DEBUG=true
                    APP_URL=https://localhost
                    PROXY_URL = https://psc-ws-maj.henix.asipsante.fr/
                    PROXY_SCHEMA = https

                    LOG_CHANNEL=stack
                    LOG_LEVEL=debug

                    DB_CONNECTION=mysql
                    DB_HOST={{ range service "psc-ws-maj-db-server" }}{{ .Address }}{{ end }}
                    DB_PORT={{ range service "psc-ws-maj-db-server" }}{{ .Port }}{{ end }}
                    DB_DATABASE=pscwsmaj
                    DB_USERNAME=root
                    DB_PASSWORD=root

                    BROADCAST_DRIVER=log
                    CACHE_DRIVER=file
                    QUEUE_CONNECTION=sync
                    SESSION_DRIVER=file
                    SESSION_LIFETIME=120

                    MEMCACHED_HOST=127.0.0.1

                    REDIS_HOST=127.0.0.1
                    REDIS_PASSWORD=null
                    REDIS_PORT=6379

                    MAIL_MAILER=smtp
                    MAIL_HOST=mailhog
                    MAIL_PORT=1025
                    MAIL_USERNAME=null
                    MAIL_PASSWORD=null
                    MAIL_ENCRYPTION=null
                    MAIL_FROM_ADDRESS=null
                    MAIL_FROM_NAME="${APP_NAME}"

                    MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
                    MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

                    PROSANTECONNECT_CLIENT_ID=ans-parrainage-bas
                    PROSANTECONNECT_CLIENT_SECRET=5e609e45-ee6c-4854-93a0-91a44aa953bb
                    PROSANTECONNECT_REDIRECT_URI="${APP_URL}:9000/auth/prosanteconnect/callback"
                EOH
                destination = "secrets/.env"
                change_mode = "restart"
                env = true
            }
            resources {
                cpu = 1024
                memory = 2048
            }
            service {
                name = "${NOMAD_JOB_NAME}-webapp"
                tags = ["urlprefix-/ "]
                port = "http"
                check {
                    type = "http"
                    port = "http"
                    path = "/"
                    interval = "10s"
                    timeout = "2s"
                }
            }
        }
    }
}

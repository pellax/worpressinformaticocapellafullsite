#!/bin/bash
# Script de gestión para Informático Capella WordPress

case "$1" in
  start)
    echo "🚀 Iniciando Informático Capella WordPress..."
    docker-compose up -d
    echo "✅ Sitio disponible en: http://localhost:8080"
    ;;
  stop)
    echo "⏹️  Deteniendo contenedores..."
    docker-compose down
    ;;
  restart)
    echo "🔄 Reiniciando contenedores..."
    docker-compose restart
    ;;
  logs)
    docker-compose logs -f
    ;;
  wp-logs)
    docker-compose logs -f wordpress
    ;;
  db-logs)
    docker-compose logs -f db
    ;;
  status)
    docker-compose ps
    ;;
  wp-cli)
    docker exec -it informaticocapella_wp wp "$@"
    ;;
  bash)
    docker exec -it informaticocapella_wp bash
    ;;
  db-shell)
    docker exec -it informaticocapella_db mysql -u capella_user -pcapella_secure_pass_2024 informaticocapella_db
    ;;
  backup-db)
    echo "📦 Creando backup de la base de datos..."
    docker exec informaticocapella_db mysqldump -u capella_user -pcapella_secure_pass_2024 informaticocapella_db > backup_$(date +%Y%m%d_%H%M%S).sql
    echo "✅ Backup creado: backup_$(date +%Y%m%d_%H%M%S).sql"
    ;;
  *)
    echo "Comandos disponibles:"
    echo "  ./manage.sh start       - Iniciar contenedores"
    echo "  ./manage.sh stop        - Detener contenedores"
    echo "  ./manage.sh restart     - Reiniciar contenedores"
    echo "  ./manage.sh status      - Ver estado"
    echo "  ./manage.sh logs        - Ver logs en tiempo real"
    echo "  ./manage.sh wp-logs     - Ver logs de WordPress"
    echo "  ./manage.sh db-logs     - Ver logs de la base de datos"
    echo "  ./manage.sh wp-cli      - Ejecutar WP-CLI"
    echo "  ./manage.sh bash        - Entrar al contenedor"
    echo "  ./manage.sh db-shell    - Conectar a la base de datos"
    echo "  ./manage.sh backup-db   - Crear backup de la BD"
    ;;
esac

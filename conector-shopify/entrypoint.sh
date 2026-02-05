#!/bin/bash
set -e

echo "🔧 Aplicando migraciones..."
python manage.py migrate --noinput

echo "📊 Creando datos de prueba..."
python manage.py shell <<EOF
from django.contrib.auth import get_user_model
User = get_user_model()
if not User.objects.filter(username='admin').exists():
    User.objects.create_superuser('admin', 'admin@test.com', 'admin123')
    print('✅ Usuario admin creado')
EOF

echo "🚀 Iniciando servidor..."
exec "$@"
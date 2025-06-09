#!/bin/sh

set -e

host="${DB_HOST:-mysql}"
port="${DB_PORT:-3306}"

echo "Aguardando MySQL ($host:$port) ficar disponível..."

until nc -z "$host" "$port"; do
  echo "MySQL não disponível ainda - aguardando..."
  sleep 2
done

echo "MySQL está pronto! Executando comandos iniciais..."

php bin/hyperf.php migrate --force
php bin/hyperf.php seed:salons-collaborators
php bin/hyperf.php server:watch

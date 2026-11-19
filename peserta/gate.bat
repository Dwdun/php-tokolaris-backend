@echo off
REM gate.bat - Windows launcher adaptif untuk Gate Tiket Bootcamp TokoLaris (Folder Peserta)
REM Penggunaan: gate.bat status | gate.bat lihat T04 | gate.bat mulai T04 | gate.bat uji T04 | gate.bat reset

set DOCKER_COMPOSE_CMD=docker compose
docker compose version >nul 2>&1
if %errorlevel% neq 0 (
    set DOCKER_COMPOSE_CMD=docker-compose
)

if exist docker-compose.yml (
    %DOCKER_COMPOSE_CMD% exec app bash /var/www/pemeriksa/gate.sh %*
) else (
    %DOCKER_COMPOSE_CMD% -f peserta\docker-compose.yml exec app bash /var/www/pemeriksa/gate.sh %*
)

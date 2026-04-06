# Лабораторные работы (ОНИТ)

## ЛР1 (ORM)
- Используется Doctrine ORM:
  - сущности в `src/Entity`
  - репозитории в `src/Repository`
  - работа с БД через сервисы `OrderService`, `FeedbackService`, `UserService`

## ЛР2 (контейнеризация)
- Multi-stage сборка: `docker/php/Dockerfile`
- Сервисы приложения и БД разнесены:
  - `app`
  - `database`
- Переменные подключения к БД и секреты передаются через окружение:
  - `APP_SECRET`, `POSTGRES_DB`, `POSTGRES_USER`, `POSTGRES_PASSWORD`, `POSTGRES_VERSION`
- Зависимости сервисов:
  - `app` использует `depends_on` с условием `service_healthy` для `database`
- Healthcheck основного приложения:
  - endpoint: `/health`
  - Docker healthcheck: `curl http://127.0.0.1:8000/health`

### Запуск ЛР2
1. Скопировать `.env.docker.example` в `.env` или экспортировать переменные окружения.
2. Выполнить:
   ```bash
   docker compose up -d --build
   ```
3. Проверить:
   - `http://localhost:8000/health`

## ЛР3 (CI/CD)
- Workflow: `.github/workflows/ci-cd.yml`
- Переменные из ЛР2 вынесены в GitHub Variables/Secrets:
  - Variables: `POSTGRES_VERSION`, `POSTGRES_DB`, `POSTGRES_USER`
  - Secrets: `APP_SECRET`, `POSTGRES_PASSWORD`
- Встроенный функциональный тест:
  - `scripts/functional-healthcheck-test.sh`
- CD часть:
  - на `main/master` собирается и пушится Docker image в GHCR

## ЛР4 (балансировка round-robin)
- Отдельный стек: `compose.lab4.yaml`
- 3 ноды веб-приложения:
  - `node1` (`Нода 1`)
  - `node2` (`Нода 2`)
  - `node3` (`Нода 3`)
- Nginx load balancer:
  - конфиг: `lab4/nginx-lb/default.conf`
  - метод балансировки: round-robin (по умолчанию для upstream)
  - URL: `http://localhost:8081`

### Запуск ЛР4
```bash
docker compose -f compose.lab4.yaml up -d --build
```

При повторных запросах к одному URL будет меняться ответ: `Нода 1`, `Нода 2`, `Нода 3`.

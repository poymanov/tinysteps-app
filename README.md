# Tinysteps

Приложение для подбора репетитора по английскому.
Доступен выбор преподавателя по цели обучения.
Доступна регистрация пользователей.

### Установка

Для запуска приложения требуется **Docker** и **Docker Compose**.

Для инициализации приложения выполнить команду:
```
make init
```

### Настройка

Для загрузки тестовых данных выполнить команду:
```
make api-fixtures-demo
```


### Запуск

```
make up
```

Приложение доступно по адресу - http://localhost:8080

Остановка приложения:

```
make down
```

Документация по API - http://localhost:8083

Почтовый клиент - http://localhost:8082

### Цель проекта

Код написан в образовательных целях. 

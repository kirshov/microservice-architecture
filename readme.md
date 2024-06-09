## Домашнее задание

Реализованы следующие API:


### Запустить minikube
minikube start

### Запустить туннель
minikube tunnel

### Установить prometheus (ДЛЯ ЭТОГО ДЗ НЕ ТРЕБУЕТСЯ)
helm repo add prometheus-community https://prometheus-community.github.io/helm-charts
helm repo update
helm upgrade --install prometheus prometheus-community/kube-prometheus-stack -f "./.helm/prometheus/values.yaml"

### Установить ingress
helm repo add ingress-nginx https://kubernetes.github.io/ingress-nginx/
helm repo update
helm upgrade --install nginx ingress-nginx/ingress-nginx -f "./.helm/nginx-ingress/values.yaml"

### Установить postgres
kubectl apply -f https://raw.githubusercontent.com/rancher/local-path-provisioner/master/deploy/local-path-storage.yaml

helm upgrade --install app-postgres oci://registry-1.docker.io/bitnamicharts/postgresql -f  "./.helm/postgres/values.yaml"

### Запустить приложение
helm upgrade --install app ./.helm/app

### Запустить сервис авторизации
helm upgrade --install app-auth ./.helm/app-auth

### Запустить сервис биллинга
helm upgrade --install app-billing ./.helm/app-billing

### Запустить сервис нотификаций
helm upgrade --install app-notify ./.helm/app-notify

### Сценарий взаимодействия

В данной работе используется только http взаимодействие.

При попытке сформировать запрос основной сервис запрашивает баланс в billing-service, если денег а счету достаточно, списывает их.
В случе успеха или неудачи основной сервис отправляет запрос в notify-service сообщение (текст взависимости от сценария).

![screen](screenshot/order-schema.png)

### В рамках ДЗ реализованы API:
#### Основной сервис APP:

**[POST]** /api/order/create - создание заказа

#### Сервис биллинга
**[POST]** /create - Создать счет

**[POST]** /incoming - Добавить средства на баланс

**[POST]** /pay - Списать с баланса

**[GET]** /get - Получить баланс

#### Сервис уведомлений:
**[POST]** /add - Добавить сообщение в очередь

**[GET]** /all - Получить всех уведомлений

**[GET]** /get - Получить сообщение по ID

**[GET]** /get-last - Получить последнее сообщение

### Запуск тестов newman
newman run ./postman/collection.json --folder "test billing"
![screen](screenshot/lesson-22.png)

### Удалить приложение
helm upgrade --uninstall app

### Удалить postgres
helm uninstall app-postgres
kubectl delete -n default persistentvolumeclaim data-app-postgres-postgresql-0
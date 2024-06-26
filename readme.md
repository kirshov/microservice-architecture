Установку упаковал в makefile:
## Установить проект
### Установить сервисы
namespace = kirshov-otus (указан в make файле)

**make init**
### Установить все приложения
**make install-all**

####
Для доступа к сервисам вне контейнера необходимо запустить **minikube tunnel**

### Запуск тестов newman
newman run ./postman/collection.json --folder "test idempotency"
![screen](screenshot/result-1.png)
![screen](screenshot/result-2.png)

## Удалить проект
**make uninstall-all**


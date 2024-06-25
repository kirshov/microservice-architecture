## Установить проект
### Установить сервисы
namespace = kirshov-otus (указан в make файле)

make init
### Установить все приложения
make install-all

### Запуск тестов newman
newman run ./postman/collection.json --folder "test saga"
![screen](screenshot/result-1.png)
![screen](screenshot/result-2.png)

## Удалить проект
uninstall-all


## Домашнее задание

[Ссылка на коллекцию Postman](https://api.postman.com/collections/25030056-95baae54-f656-4982-9b04-74b4f8ebb3f6?access_key=PMAT-01HR7T1X626SYX2ED4BTFV4E4F)

### Запустить minikube
minikube start

### Запустить тунель
minikube tunnel

### Установить ingress
helm repo add ingress-nginx https://kubernetes.github.io/ingress-nginx/
helm repo update
helm upgrade --install nginx ingress-nginx/ingress-nginx -f "./.helm/nginx-ingress/values.yaml"

### Установить postgres
helm upgrade --install app-postgres oci://registry-1.docker.io/bitnamicharts/postgresql -f  "./.helm/postgres/values.yaml"

### Запустить приложение
helm upgrade --install app ./.helm/app

### Удалить приложение
helm upgrade --uninstall app

### Удалить postgres
helm uninstall app-postgres --namespace=m

### Установить prometheus
helm repo add prometheus-community https://prometheus-community.github.io/helm-charts
helm repo update
helm upgrade --install prometheus prometheus-community/kube-prometheus-stack -f "./.helm/prometheus/values.yaml"

### Пробросить порт prometheus
kubectl port-forward service/prometheus-operated 9090

minikube delete --all

kubectl expose service prometheus-server --type=NodePort --target-port=9090 --name=prometheus-server-ext
minikube service service-1-server-ext

### Установка grafana
helm repo add grafana https://grafana.github.io/helm-charts
helm repo update
helm install grafana grafana/grafana

### Пробросить порт grafana
kubectl expose service grafana --type=NodePort --target-port=3000 --name=grafana-ext


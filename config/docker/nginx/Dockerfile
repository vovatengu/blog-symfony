FROM nginx:1.26-alpine

RUN mkdir -p /etc/nginx/ssl
COPY config/docker/ssl/cv.crt /etc/nginx/ssl/cv.crt
COPY config/docker/ssl/cv.key /etc/nginx/ssl/cv.key

COPY config/docker/nginx/vhost.conf /etc/nginx/conf.d/default.conf

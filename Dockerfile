FROM nginx

RUN echo "server_tokens off;" > /etc/nginx/conf.d/fix.conf

COPY . /usr/share/nginx/html
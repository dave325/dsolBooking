FROM mysql:5.7

RUN mkdir /backup
WORKDIR /backup

ADD ./wordpress-2.sql /backup/
RUN /bin/bash -c "/usr/bin/mysqld_safe --skip-grant-tables & " && \
    sleep 5 && \
    mysql -u wordpress -e "CREATE database wordpress;" && \
    mysql -u wordpress wordpress < /backup/wordpress-2.sql
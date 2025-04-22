ARG MYSQL_VERSION=${MYSQL_VERSION}

FROM mysql:$MYSQL_VERSION

# Prevent the MySQL warning "Insecure configuration for --pid-file".
RUN mkdir -p /var/run/mysqld && \
    chown mysql:mysql /var/run/mysqld&& \
    chmod 750 /var/run/mysqld

CMD ["mysqld", "--pid-file=/var/run/mysqld/mysqld.pid"]

FROM alpine:3.6
MAINTAINER Jesse Boyer

# Enable Community Repo /etc/apk/repositories
sed -e 's;^#http\(.*\)/v3.6/community;http\1/v3.6/community;g' \
      -i /etc/apk/repositories

RUN apk update && apk upgrade &&

7.1.9-r0

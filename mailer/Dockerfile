FROM debian:buster

RUN apt-get update -y && apt-get install -y python python-pip
COPY ./ /app
WORKDIR /app
RUN pip install flask
RUN pip install flask_restful

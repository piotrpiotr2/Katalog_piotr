FROM ubuntu:latest
LABEL authors="[piotr]"

ENTRYPOINT ["top", "-b"]
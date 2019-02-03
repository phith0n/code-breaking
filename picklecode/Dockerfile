FROM python:3.7

ARG PIP_MIRROR="https://pypi.tuna.tsinghua.edu.cn/simple"
ADD ./web/requirements.txt /requirements.txt

RUN set -ex \
    && pip install -i $PIP_MIRROR -r /requirements.txt

COPY ./web/ /usr/src/

WORKDIR /usr/src
EXPOSE 8000

CMD ["bash", "server.sh"]

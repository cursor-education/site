IMAGE_NAME = cursor-education/site
CONTAINER_NAME = cursor-education-site

HOME = /shared
PORT = 80
ENV = production

.PHONY: all

all: up clean build run

up:
	git pull --force

set-dev:
	$(eval PORT = 8080)
	$(eval ENV = dev)

clean: clean-image clean-container

clean-all: clean
	docker rm -f $$(docker ps -a -q) || true
	docker ps -a

clean-image:
	docker rmi -f ${IMAGE_NAME} 2>/dev/null || true
	docker images | grep ${IMAGE_NAME} || true

clean-container:
	docker rm -f ${CONTAINER_NAME} 2>/dev/null || true
	docker ps -a

build: stop clean-container
	docker build -t ${IMAGE_NAME} .

stop: clean-container

run: stop
	docker run --name=${CONTAINER_NAME} \
		-p ${PORT}:8080 \
		-v $$PWD:${HOME} \
		-e APP_ENV="${ENV}" \
		-ti -d \
		${IMAGE_NAME}

run-dev: set-dev run

ssh:
	docker exec -ti ${CONTAINER_NAME} bash

release: release-static release-minor

release-minor:
	docker exec -ti ${CONTAINER_NAME} /bin/sh -c 'SEMVER=minor bash environment/release-project.sh'

release-major:
	docker exec -ti ${CONTAINER_NAME} /bin/sh -c 'SEMVER=major bash environment/release-project.sh'

release-static:
	docker exec -ti ${CONTAINER_NAME} /bin/sh -c 'bash environment/release-static.sh'
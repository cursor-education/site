IMAGE_NAME = cursor-education/site
CONTAINER_NAME = cursor-education-site

APP_HOME = /shared
APP_PORT = 80
APP_ENV = production

.PHONY: all

all: clean build run

set-dev:
	$(eval APP_PORT = 8080)
	$(eval APP_ENV = dev)

clean: remove-image remove-container

remove-image:
	docker rmi -f ${IMAGE_NAME} 2>/dev/null || true
	docker images | grep ${IMAGE_NAME} || true

remove-container:
	docker rm -f ${CONTAINER_NAME} 2>/dev/null || true
	docker ps -a

build: stop remove-container
	docker build -t ${IMAGE_NAME} .

stop: remove-container

run: stop
	docker run --name=${CONTAINER_NAME} \
		-p ${APP_PORT}:8080 \
		-v $$PWD:${APP_HOME} \
		-e APP_ENV="${APP_ENV}" \
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
	docker exec -ti ${CONTAINER_NAME} /bin/sh -c 'SEMVER=major bash environment/release-static.sh'
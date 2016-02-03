IMAGE_NAME = cursor-education/site
CONTAINER_NAME = cursor-education-site

HOME = /shared
PORT = 80
ENV = production

.PHONY: all

help:
	@echo " all \t\t\t to run all on production (port 80)"
	@echo " all-dev \t\t to run all on development (port 8080)"
	@echo " clean \t\t\t to clean project-related containers & images"
	@echo " clean-all \t\t to clean all Docker containers & images"
	@echo " release-minor \t\t to increment minor (0.x) version of release & commit"
	@echo " release-major \t\t to increment major (x.0) version of release & commit"
	@echo " release-static \t to build static & commit"
	@echo " release \t\t to release static & minor version release"
	@echo " build-static \t\t to build static"

all: up clean build run
all-dev: clean build run-dev

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
		-ti -d ${IMAGE_NAME}

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

build-static:
	docker exec -ti ${CONTAINER_NAME} /bin/sh -c 'bash environment/build-static.sh'
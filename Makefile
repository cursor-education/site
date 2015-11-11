CONFIG = shared/site/src/config/default.yml

VERSION = $$(cat ${CONFIG} | grep version | grep -iEo '([0-9\.]+)')
COMMENT = $$(git log --oneline --pretty=%B -1 | sed -e 's/[\/]/-/g')

VERSION_NEW = ${VERSION}
VERSION_NEW=`echo ${VERSION} + 0.1 | bc`

up:
	vagrant up

ssh:
	vagrant ssh

release:
	cat ${CONFIG} | sed -e "s/version.*/version: '${VERSION_NEW}-${COMMENT}'/g" > ${CONFIG}

.PHONY: up ssh
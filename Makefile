CONFIG = shared/site/src/config/default.yml

VERSION = $$(cat ${CONFIG} | grep version | grep -iEo '([0-9\.]+)' | head -1)
COMMENT = $$(git log --oneline --pretty=%B -1 | perl -pe 's/[^\w.-]+/-/g')

VERSION_NEW = ${VERSION}
VERSION_NEW=`echo ${VERSION} + 0.1 | bc`

up:
	vagrant up

ssh:
	vagrant ssh

release:
	@echo ${VERSION}
	@echo ${VERSION_NEW}
	
	cat ${CONFIG} | sed -e "s/version.*/version: '${VERSION_NEW}-${COMMENT}'/g" > ${CONFIG}
	git add ${CONFIG}
	git commit -m "release ${VERSION_NEW}" ${CONFIG}

.PHONY: up ssh
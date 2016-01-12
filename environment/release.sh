# CONFIG = site/src/config/default.yml

# VERSION = $$(cat ${CONFIG} | grep version | grep -iEo '([0-9\.]+)' | head -1)
# COMMENT = $$(git log --oneline --pretty=%B -1 | perl -pe 's/[^\w.-]+//g')

# VERSION_MINOR_NEW = `echo ${VERSION} + 0.01 | bc`
# VERSION_MAJOR_NEW = `echo ${VERSION} + 1.0 | bc`



# release-major:
#   @echo release ${VERSION_MAJOR_NEW}
#   cat ${CONFIG} | sed -e "s/version.*/version: '${VERSION_MAJOR_NEW}-${COMMENT}'/g" > ${CONFIG}
#   git add ${CONFIG}
#   git commit -m "release ${VERSION_MINOR_NEW}" ${CONFIG}

# release-minor:
#   @echo release ${VERSION_MINOR_NEW}
#   cat ${CONFIG} | sed -e "s/version.*/version: '${VERSION_MINOR_NEW}-${COMMENT}'/g" > ${CONFIG}
#   git add ${CONFIG}
#   git commit -m "release ${VERSION_MINOR_NEW}" ${CONFIG}

# release-static:
#   git add site/web/assets/*
#   git commit -m "regenerated assets" site/web/assets/*



#!/bin/bash -e

CONFIG=site/src/config/default.yml

VERSION=$(cat ${CONFIG} | grep version | grep -iEo '([0-9\.]+)' | head -1)
VERSION_NEW_MINOR=`echo ${VERSION} + 0.01 | bc`
VERSION_NEW_MAJOR=`echo ${VERSION} + 1.0 | bc`

COMMENT=$(git log --oneline --pretty='%s' -1 | perl -pe 's/[^\w.-]+//g')
COMMENT=$(echo $COMMENT | perl -ne 'print lc')
COMMENT=${COMMENT::30}

if [[ $SEMVER = "minor" ]]; then
    VERSION_NEW=${VERSION_NEW_MINOR}
else
    VERSION_NEW=${VERSION_NEW_MAJOR}
fi

echo new version: ${VERSION_NEW}

cat ${CONFIG} | sed -e "s/version.*/version: '${VERSION_NEW}-${COMMENT}'/g" > ${CONFIG}

git add ${CONFIG}
git commit -m "release ${VERSION_NEW}" ${CONFIG}
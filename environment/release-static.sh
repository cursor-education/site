#!/bin/bash -e

cd /shared/site
grunt
cd /shared

git add site/web/assets/*
git commit -m "regenerated assets" site/web/assets/*
#!/usr/bin/env bash

cd "$(dirname "${0}")"

git submodule init
git submodule update

docker build -f vendor/Dockerfile -t amwd/ssdbserlangen .

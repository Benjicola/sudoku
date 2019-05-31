#!/usr/bin/env bash

if [ "${CIRCLE_BRANCH}" = "develop" ]
then
    echo "Development environment detected"

    echo "export APP_IMAGE_TAG=${CIRCLE_SHA1}" >> $BASH_ENV
    echo "export KUBECTL_CONFIG=${KUBECTL_CONFIG_SIT}" >> $BASH_ENV
fi

if [ ! -z "${CIRCLE_TAG}" ]
then
    echo "Tag detected, it can be pprod or prod."

    echo "export APP_IMAGE_TAG=${CIRCLE_TAG}" >> $BASH_ENV
    echo "export KUBECTL_CONFIG=${KUBECTL_CONFIG_PPROD}" >> $BASH_ENV
fi

#echo "export APP_IMAGE=sudoku-app" >> $BASH_ENV
#echo "export K8S_NAMESPACE=sudoku" >> $BASH_ENV
#echo "export K8S_SERVICE_NAME=sudoku-app-vp" >> $BASH_ENV

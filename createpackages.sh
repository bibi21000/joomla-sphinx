#!/bin/sh

VERSION=$(cat com_sphinxdoc/sphinxdoc.xml | grep "</version>" | sed -e "s#[^0123456789.]##g")
#VERSION=$(cat com_sphinxdoc/com_sphinxdoc.xml | grep "</version>" | sed -e "s#<version>##" | sed -e "s#</version>##" | sed -e "s# ##g" | sed -e "s#[^0123456789.]##g")
zip -r com_sphinxdoc-$VERSION.zip com_sphinxdoc
echo component  com_sphinxdoc-$VERSION.zip
#mv com_sphinxdoc-$VERSION.zip /home/sebastien/Téléchargements/

VERSION=$(cat plg_sphinxdocdocumentation/sphinxdocdocumentation.xml | grep "</version>" | sed -e "s#[^0123456789.]##g")
zip -r plg_sphinxdocdocumentation-$VERSION.zip plg_sphinxdocdocumentation
echo component  plg_sphinxdocdocumentation-$VERSION.zip

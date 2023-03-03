FROM solr:7.7.3
MAINTAINER Joinup

ARG search_api_solr_version="8.x-2.x-dev"
ENV SAPI_VERSION=${search_api_solr_version}
ARG conf_dir_name="solr-conf"
ENV CONF_DIR_NAME=${conf_dir_name}
ENV SOLR_VERSION=7.7.3

RUN wget -qO- "https://ftp.drupal.org/files/projects/search_api_solr-${SAPI_VERSION}.tar.gz" | tar xz -C /opt/docker-solr/scripts/
RUN mkdir -p /opt/docker-solr/configsets/drupal

# The following group will make the drupal configuration files available in /opt/docker-solr/configsets/drupal.
# The script 'solr-precreate' takes as parameters the name of the core and the directory of the configuration files. The
# directory passed should be the directory that includes the 'conf' directory and not the 'conf' directory's path
# itself.
RUN SOLR_MAJOR_VERSION=`echo "${SOLR_VERSION}" | sed -e "{ s/^\(.\).*/\1/ ; q }"` && \
    mv "/opt/docker-solr/scripts/search_api_solr/${CONF_DIR_NAME}/${SOLR_MAJOR_VERSION}.x" "/opt/docker-solr/configsets/drupal/conf" && \
    chown -R $SOLR_USER:$SOLR_GROUP /opt/docker-solr/configsets && \
    rm -rf /opt/docker-solr/scripts/search_api_solr && \
    sed -i '/solr.install.dir=/c\solr.install.dir=/opt/solr' /opt/docker-solr/configsets/drupal/conf/solrcore.properties

USER root
# Apply security updates and delete it again so it's not running in the background (auto-updates)
RUN apt update && apt install -y unattended-upgrades;
RUN dpkg-reconfigure unattended-upgrades;
RUN unattended-upgrade -d;
RUN apt-get autoremove unattended-upgrades -y --purge; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/*;
USER $SOLR_USER

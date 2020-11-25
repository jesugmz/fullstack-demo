ARG NODE_VERSION=12.19-slim

#
# base stage
#
FROM node:$NODE_VERSION AS base

WORKDIR /usr/local/frontend

#
# dev stage
#
FROM base AS dev

RUN apt-get update \
  && apt-get install -y --no-install-recommends --no-install-suggests \
    ca-certificates \
    curl \
    git \
  && rm -rf /tmp/* /var/lib/apt/lists/*

CMD ["yarn", "run", "start"]

FROM node:slim

LABEL author: Rene Bentes Pinto <github.com/renebentes> \
	name=joomlagov/builder version=1.0.1

WORKDIR /usr/src/joomlagov

COPY ./package*.json ./
COPY ./gulpfile.babel.js ./
COPY ./.babelrc ./

# Installing latest npm and gulp-cli and dependencies
RUN npm i -g npm@latest gulp-cli; \
	npm i

CMD [ "bash" ]

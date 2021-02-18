FROM node:slim

LABEL author: Rene Bentes Pinto <github.com/renebentes> \
	name=joomlagov/builder version=1.0.3

WORKDIR /joomlagov

COPY ./package*.json gulpfile.babel.js .babelrc ./

# Installing latest npm and gulp-cli and dependencies
RUN npm i -g npm@latest gulp-cli; \
	npm i

CMD [ "bash" ]

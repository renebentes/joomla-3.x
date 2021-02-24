FROM node:slim

LABEL author: Rene Bentes Pinto <github.com/renebentes> \
	name=joomlagov/builder version=1.1.0

WORKDIR /joomlagov

COPY ./package*.json gulpfile.babel.js .babelrc ./

RUN npm i -g npm@latest gulp-cli --silent; \
	npm i --silent

CMD [ "bash" ]

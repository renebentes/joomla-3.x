FROM node:slim

LABEL author="Rene Bentes Pinto <github.com/renebentes>"
LABEL name=joomlagov/gulp
LABEL version=2.0.0

WORKDIR /joomlagov

COPY ./package*.json ./gulpfile.babel.js ./.babelrc ./

RUN npm i -g npm@latest gulp-cli --silent; \
	npm i --silent

CMD [ "bash" ]

FROM node:6

COPY package.json /tmp/package.json

RUN npm config set registry http://registry.npmjs.org/
RUN cd /tmp && npm install
RUN mkdir -p /app && cp -a /tmp/node_modules /app/

WORKDIR /app

CMD npm run dev

EXPOSE 80

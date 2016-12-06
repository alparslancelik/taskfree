# CS2102 Final Project

This is the CS2102 Final Project.

## Tech Stack

### Frontend
1. VueJS
2. ExpressJS with Webpack middleware for development

### Backend (RESTful API)
1. HHVM + Slim + Postgres

## Instructions (Development)

1. Install Docker Platform.
   Follow the instructions [here](https://www.docker.com/products/docker)
2. Run Docker shell, for linux and macOS it's just your normal shell.
2. Run `docker-compose up`


## How To

### Add a new dependencies

1. Update the package.json or composer.json
2. Rebuild the stack using `docker-compose build`.

### Remove everything

Run the following to remove all images and stopped containers

```
docker rm $(docker ps -a -q)
docker rmi $(docker images -q)
```

# License

The MIT License

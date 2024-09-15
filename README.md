# Getting started

## Prerequisites

1. ### Git
    #### Linux installation
    ```sh
    sudo apt update
    sudo apt install git
   ```
    #### For different OS see:
    https://git-scm.com/book/en/v2/Getting-Started-Installing-Git

2. ### Docker
    #### Linux installation
    ```sh
    sudo apt update
    sudo apt install apt-transport-https ca-certificates curl software-properties-common
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
    sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"
    sudo apt update
    sudo apt install docker-ce
   ```
    #### For different OS see:
    https://docs.docker.com/engine/install/


3. ### Composer 
   #### Linux installation
    ```sh
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
   ```
   #### For different OS see:
    https://getcomposer.org/

4. ### Make
   #### Linux installation
    ```sh
    sudo apt update
    sudo apt install make
   ```
4. ### php-xml
   #### Linux installation
    ```sh
    sudo apt install php-xml
   ```
## Installation

1. ### Clone the repo
     ```sh
       git clone https://github.com/Lvll3gliic/social-publishing-platform.git
   ```
2. ### Access project folder

     ```sh
    cd social-publishing-platform
   ```
3. ### Install dependencies
    ```sh
    composer install
   ```
4. ### Copy the environment file
      ```sh
    cp .env.example .env
   ```
5. ### Start Laravel Sail
      ```sh
    make up 
   ```
6. ### Generate application key
   ```sh
    make key-generate 
   ```
7. ### Run database migrations
    ```sh
    make migrate 
   ```
8. ### Seed database with example data
    ```sh
    make seed 
   ```
9. ### Run development server for frontend
   ```sh
     make fe-start
   ```
10. ### Access project in browser
    ```sh
    localhost
    ```

## Additional information
### Make commands (helper commands)
#### Start Laravel Sail services / start project
```sh
make up 
   ```
#### Stop Laravel Sail services / stop project"
```sh
make down 
   ```
#### Run database migrations
```sh
make migrate 
   ```
#### Seed the database
```sh
make seed 
   ```
#### Opens an interactive shell in the Laravel Sail container
```sh
make shell 
   ```
#### Opens an interactive shell in the Laravel Sail container as root
```sh
make root-shell 
   ```
#### Clears cache
```sh
make clear-cache 
   ```
#### Runs tests
```sh
make test 
   ```
#### generate application key
```sh
make key-generate 
   ```
#### start development server (install dependencies)
```sh
make fe-start 
   ```
#### Get all commands in CMD with explanation
```sh
make help 
   ```


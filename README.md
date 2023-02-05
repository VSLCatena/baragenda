<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


## About Baragenda

Baragenda is a web application for managing VSL Catena events & personell. 

## Contributing

### Prerequisites:
- Docker in [rootless mode](https://docs.docker.com/engine/security/rootless/)
- git
- `uidmap`
- Run this script:
```bash
###
### Setup Laravel
###
sudo -k # Invalidate root timestamp file
curl -s "https://laravel.build/baragenda?with=mysql" | bash
cd baragenda/
rm -r resources/views/welcome.blade.php resources/css/ # Remove junk

###
### Setup git
###
git init # Initialize .git
git remote add origin https://github.com/vslcatena/baragenda # Add remote
git fetch --all # Update all remote info
git switch -f rebuild # Switch to dev branch

###
### Setup folder permissions
###
NONROOTUID=100999 # if the Docker daemon running as a non-root user (Rootless mode) then translate UID:
    # out: 1000   => in: 0=root
    # out: 100999 => in: 1000
mkdir public/build node_modules resources/sass #required folders for dependencies
sudo chmod -R 0775 storage/ vendor/ bootstrap/cache public/build resources/js resources/sass node_modules vite.config.js 
sudo chown -R $USER:$NONROOTUID storage vendor bootstrap/cache public/build resources/js resources/sass node_modules composer.json  composer.lock package-lock.json package.json yarn.lock vite.config.js

###
### Prepare config file
###
cp .env.example .env
nano .env # Fill in .env with required info.
sail up -d # Start container

###
### Install dependencies
###
sail composer install # Install composer.json dependencies
sail artisan ui bootstrap # Install bootstrap UI dependencies (resources/sass ..)
sail npm install
sail yarn install
sail npm run build

###
### Fill database
###
sail artisan migrate
sail artisan db:seed
```

# tanzpartnersuche
A typo3 extension for our dancing club, used to find a dancing partner.

Based on Typo3 10.x, Exbase and Fluid.

## How to set up a local development environment
1. Install [Docker](https://docs.docker.com/get-docker/)
1. Clone this repository
1. Open a terminal and change into the directory that you cloned into
1. Copy `template.env` to `.env` and fill in the values
1. Run `docker-compose up`
1. Visit [localhost](http://localhost:80) and complete the Typo3 installation
1. If the error "Directory /typo3conf/ext is not writable" occurs during the Typo3 installation,
   run `docker exec tanzpartnersuche_typo3_1 chown www-data:www-data typo3conf/ext`

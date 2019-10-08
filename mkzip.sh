#!/bin/bash
rm -f plg_content_nuliga.zip
zip -q -9 -x "/.gitignore" -x "/.git/*" -x "/.idea/*" -x "/mkzip.sh" -x "/.mkzip.sh.swp" -x "/updates/*" -x '/docker-compose.yml' -r plg_content_nuliga.zip .


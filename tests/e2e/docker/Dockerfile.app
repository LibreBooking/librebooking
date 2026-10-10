FROM librebooking/librebooking:develop

# Replace the image's application files with the working tree.
RUN find /var/www/html -mindepth 1 -maxdepth 1 -exec rm -rf {} +
COPY --chown=www-data:root --chmod=0775 . /var/www/html/

RUN bash /usr/local/bin/build_app.sh

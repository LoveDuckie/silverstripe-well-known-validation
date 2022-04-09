# silverstripe-well-known-validation

> A [SilverStripe](https://www.silverstripe.org/) module for conveniently allowing administrators to validate the ownership of their website or domain name with other third-party services.

## FAQ

This section is for frequently answered questions.

### Q: What does this module do?

This module handles routing for the `/.well-known` endpoint that is used by `RFC 8615` for rendering and displaying descriptive information about a website and its services. This endpoint or route is often used by third-party services (such as [keybase.io](https://keybase.io)) for validating or verifying ownership of a website and domain name.

This module enables you to configure and handle validation requests from your SilverStripe administration panel, without having to directly interact with the web-server by uploading text files for verification purposes.

### Q: How does it work?

Simply install the module by reading the installation instructions below, and ensure that you have navigated to `/dev/build?flush=all`.

Afterwards you will want to navigate to Configuration panel for your SilverStripe project, and select the **Validation** tab.

## Requirements

* **SilverStripe** `^4.0`

## Installation

```bash
composer require loveduckie/silverstripe-well-known-validation
```

**Note:** When you have completed your module, submit it to Packagist or add it as a VCS repository to your
project's `composer.json`, pointing to the private repository URL.

## License

See [License](license.md)

## Documentation

* [Documentation readme](docs/en/readme.md)

```yaml

Page:
  config_option: true
  another_config:
    - item1
    - item2
  
```

### Configuration

This section outlines any additional configuration that may be required. This module should work automatically out of the box once you have run `/dev/build?flush=all`, but you may wish to configure it further so that it can support other third-party services.

#### NGINX

It is assumed that you are serving your SilverStripe project from a NGINX server with a configuration that looks similar to the following.

```nginx
map $http_accept $webp_suffix 
{
  default   "";
  "~*webp"  ".webp";
}

server 
{
  listen 80;
  listen [::]:80;
  server_name ${WEBSITE_DOMAIN_NAMES};
  server_tokens off;
  return 301 https://${WEBSITE_DOMAIN_NAME}$request_uri;
}

server
{
  listen 443 ssl http2;
  listen [::]:443 ssl http2;
  server_name ${WEBSITE_DOMAIN_NAMES};
  server_tokens off;
  charset utf-8;

  ssl_certificate ${NGINX_CONFIG_CERTS_PATH}/fullchain.pem;
  ssl_certificate_key ${NGINX_CONFIG_CERTS_PATH}/privkey.pem;

  access_log /var/log/nginx/sites/${WEBSITE_DOMAIN_NAME}.access combined;
  error_log /var/log/nginx/sites/${WEBSITE_DOMAIN_NAME}.error warn;

  include /etc/nginx/mime.types;

  client_max_body_size 0; # Manage this in php.ini (upload_max_filesize & post_max_size)

  root /var/www/sites/${PRODUCTION_WEBSITE_DOMAIN_NAME}/public;

  # Defend against SS-2015-013 -- http://www.silverstripe.org/software/download/security-releases/ss-2015-013
  if ($http_x_forwarded_host) {
    return 400;
  }

  location / {
      try_files $uri /index.php?$query_string;
  }

  error_page 404 /assets/error-404.html;
  error_page 500 /assets/error-500.html;
  error_page 502 /assets/error-500.html;
  error_page 503 /assets/error-500.html;

  location ~* /assets/.+\.(?<extension>jpe?g|png|gif|webp)$ 
  {
    gzip_static on;
    gzip_types image/png image/x-icon image/webp image/svg+xml image/jpeg image/gif;

    add_header Vary Accept;
    expires max;
    sendfile on;
    try_files "${request_uri}.webp" "${request_uri}" $uri =404;
  }

  location ~* ^/assets/.* 
  {
    gzip_static on;
    gzip_types text/plain text/xml text/css 
    text/comma-separated-values application/json
    image/png image/jpeg image/x-icon image/webp image/svg+xml image/gif
    text/javascript application/x-javascript application/pdf
    application/atom+xml;

    expires max;
    sendfile on;
    try_files $uri =404;
  }

  location ^~ /resources/ 
  {
    gzip_static on;
    gzip_types text/plain text/xml text/css 
    text/comma-separated-values application/json
    image/png image/x-icon image/webp image/svg+xml image/jpeg image/gif
    text/javascript application/x-javascript application/javascript
    application/atom+xml;

    expires max;
    sendfile on;
    try_files $uri =404;
  }

  # location ^~ /assets/ {
  #   gzip_static on;
  #   gzip_types text/plain text/xml text/css 
  #   text/comma-separated-values application/json
  #   image/png image/jpeg image/x-icon image/webp image/svg+xml image/gif
  #   text/javascript application/x-javascript
  #   application/atom+xml;

  #   expires max;
  #   sendfile on;
  #   try_files $uri =404;
  # }

  location /index.php {
      # client_header_timeout 10000;
      # client_body_timeout 10000;
      fastcgi_read_timeout 10000;

      fastcgi_buffers 4 65k;
      fastcgi_buffer_size 64k;
      fastcgi_busy_buffers_size 128k;
      fastcgi_keep_conn on;
      fastcgi_pass   portfolio-php:9000;
      fastcgi_index  index.php;
      fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
      include        /etc/nginx/fastcgi_params;
  }

  # # Used temporarily for installation scripts
  # location ~ \.php$ {
  #     fastcgi_buffers 4 65k;
  #     fastcgi_buffer_size 64k;
  #     fastcgi_busy_buffers_size 128k;
  #     fastcgi_keep_conn on;
  #     fastcgi_pass   portfolio-php:9000;
  #     fastcgi_index  index.php;
  #     fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
  #     include        fastcgi_params;
  # }
}
```

## Maintainers

* Luc Shelton <lucshelton@gmail.com>

## Bugtracker

Bugs are tracked in the issues section of this repository. Before submitting an issue please read over
existing issues to ensure yours is unique.

If the issue does look like a new bug:

* Create a new issue
* Describe the steps required to reproduce your issue, and the expected outcome. Unit tests, screenshots
 and screencasts can help here.
* Describe your environment as detailed as possible: SilverStripe version, Browser, PHP version,
 Operating System, any installed SilverStripe modules.

Please report security issues to the module maintainers directly. Please don't file security issues in the bugtracker.

## Development and contribution

If you would like to make contributions to the module please ensure you raise a pull request and discuss with the module maintainers.
